import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Image, ScrollView, ActivityIndicator, TextInput, TouchableOpacity, Modal, KeyboardAvoidingView, Platform, TouchableWithoutFeedback, Keyboard } from 'react-native';
import axios from 'axios';
import * as SecureStore from 'expo-secure-store';
import CustomNavBar from './CustomNavBar';
import Icon from 'react-native-vector-icons/FontAwesome';
import Toast from 'react-native-toast-message';
import defaultImage from './assets/default.jpg';

const BookDetailScreen = ({ route, navigation }) => {
  const { book } = route.params;
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);
  const [reviewText, setReviewText] = useState('');
  const [rating, setRating] = useState(0);
  const [submitting, setSubmitting] = useState(false);
  const [modalVisible, setModalVisible] = useState(false);
  const [token, setToken] = useState('');
  const [availableCopies, setAvailableCopies] = useState(0);
  const [reserved, setReserved] = useState(false);
  const [reservationId, setReservationId] = useState(null);
  const [liked, setLiked] = useState(false);

  const getFullImageUrl = (relativePath) => {
    return `http://cats.stud.vts.su.ac.rs/storage/${relativePath}`;
  };

  const fetchToken = async () => {
    const storedToken = await SecureStore.getItem('token');
    if (storedToken) {
      setToken(storedToken);
    }
  };

  const fetchReviews = async () => {
    try {
      const response = await axios.get(`http://cats.stud.vts.su.ac.rs/api/books/${book.id}/reviews`);
      setReviews(response.data);
    } catch (error) {
      console.error('Error fetching reviews:', error);
    } finally {
      setLoading(false);
    }
  };

  const fetchAvailableCopies = async () => {
    try {
      const response = await axios.get(`http://cats.stud.vts.su.ac.rs/api/books/${book.id}/available-copies`);
      setAvailableCopies(response.data.available_copies);
    } catch (error) {
      console.error('Error fetching available copies:', error);
    }
  };

  const loadLikedState = async () => {
    try {
      const likedBooks = await SecureStore.getItem('likedBooks');
      if (likedBooks) {
        const likedBooksArray = JSON.parse(likedBooks);
        setLiked(likedBooksArray.includes(book.id));
      }
    } catch (error) {
      console.error('Error loading liked state:', error);
    }
  };

  const saveLikedState = async (likedState) => {
    try {
      const likedBooks = await SecureStore.getItem('likedBooks');
      let likedBooksArray = likedBooks ? JSON.parse(likedBooks) : [];
      if (likedState) {
        likedBooksArray.push(book.id);
      } else {
        likedBooksArray = likedBooksArray.filter(id => id !== book.id);
      }
      await AsyncStorage.setItem('likedBooks', JSON.stringify(likedBooksArray));
    } catch (error) {
      console.error('Error saving liked state:', error);
    }
  };

  const loadReservationState = async () => {
    try {
      const reservedBooks = await SecureStore.getItem('reservedBooks');
      if (reservedBooks) {
        const reservedBooksArray = JSON.parse(reservedBooks);
        const reservation = reservedBooksArray.find(item => item.bookId === book.id);
        if (reservation) {
          setReserved(true);
          setReservationId(reservation.reservationId);
        }
      }
    } catch (error) {
      console.error('Error loading reservation state:', error);
    }
  };

  const saveReservationState = async (reservedState, reservationId = null) => {
    try {
      const reservedBooks = await SecureStore.getItem('reservedBooks');
      let reservedBooksArray = reservedBooks ? JSON.parse(reservedBooks) : [];
      if (reservedState) {
        reservedBooksArray.push({ bookId: book.id, reservationId });
      } else {
        reservedBooksArray = reservedBooksArray.filter(item => item.bookId !== book.id);
      }
      await AsyncStorage.setItem('reservedBooks', JSON.stringify(reservedBooksArray));
    } catch (error) {
      console.error('Error saving reservation state:', error);
    }
  };

  useEffect(() => {
    fetchToken();
    fetchReviews();
    fetchAvailableCopies();
    loadLikedState();
    loadReservationState();
  }, [book.id]);

  const renderStars = (rating, onPress) => {
    const stars = [];
    for (let i = 1; i <= 5; i++) {
      stars.push(
        <TouchableOpacity key={i} onPress={() => onPress(i)}>
          <Icon
            name="star"
            size={30}
            color={i <= rating ? "#FFD700" : "#CCCCCC"}
          />
        </TouchableOpacity>
      );
    }
    return stars;
  };

  const handleReviewSubmit = async () => {
    setSubmitting(true);
    try {
      const response = await axios.post(
        `http://cats.stud.vts.su.ac.rs/api/books/${book.id}/reviews`,
        {
          rating,
          review: reviewText,
        },
        {
          headers: {
            Authorization: `Bearer ${token}`, // Include the Bearer token
          },
        }
      );
      setReviewText('');
      setRating(0);
      setModalVisible(false);
      fetchReviews(); // Fetch reviews again after submitting a review
    } catch (error) {
      if (error.response) {
        console.error('Error response:', error.response.data);
        console.error('Error status:', error.response.status);
        console.error('Error headers:', error.response.headers);
      } else if (error.request) {
        console.error('Error request:', error.request);
      } else {
        console.error('Error message:', error.message);
      }
      console.error('Error config:', error.config);
    } finally {
      setSubmitting(false);
    }
  };

  const handleLikeToggle = async () => {
    try {
      if (liked) {
        await axios.delete(
          `http://cats.stud.vts.su.ac.rs/api/books/${book.id}/like`,
          {
            headers: {
              Authorization: `Bearer ${token}`, // Include the Bearer token
            },
          }
        );
        Toast.show({
          type: 'info',
          text1: 'Book Unliked',
          text2: 'You have unliked this book.',
        });
      } else {
        await axios.post(
          `http://cats.stud.vts.su.ac.rs/api/books/${book.id}/like`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`, // Include the Bearer token
            },
          }
        );
        Toast.show({
          type: 'success',
          text1: 'Book Liked',
          text2: 'You have liked this book.',
        });
      }
      setLiked(!liked);
      saveLikedState(!liked);
    } catch (error) {
      console.error('Error toggling like:', error);
    }
  };

  const handleReservationToggle = async () => {
    try {
      if (reserved) {
        await axios.post(
          `http://cats.stud.vts.su.ac.rs/api/book-copies/${book.id}/available`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        await axios.delete(
          `http://cats.stud.vts.su.ac.rs/api/reservations/book-copy/${book.id}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        Toast.show({
          type: 'info',
          text1: 'Reservation Cancelled',
          text2: 'You have cancelled the reservation for this book.',
        });
        setReservationId(null);
      } else {
        await axios.post(
          `http://cats.stud.vts.su.ac.rs/api/book-copies/${book.id}/reserve`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        const response = await axios.post(
          `http://cats.stud.vts.su.ac.rs/api/reservations/${book.id}`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        const newReservationId = response.data.reservation_id;
        Toast.show({
          type: 'success',
          text1: 'Book Reserved',
          text2: 'You have reserved this book.',
        });
        setReservationId(newReservationId);
      }
      setReserved(!reserved);
      saveReservationState(!reserved, reservationId);
      fetchAvailableCopies(); // Fetch available copies after reserving or undoing reservation
    } catch (error) {
      console.error('Error toggling reservation:', error);
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
      keyboardVerticalOffset={Platform.OS === 'ios' ? 64 : 0}
    >
      <TouchableWithoutFeedback onPress={Keyboard.dismiss}>
        <ScrollView contentContainerStyle={styles.scrollViewContent}>
          {loading ? (
            <ActivityIndicator size="large" color="#0000ff" />
          ) : (
            <>
              <View style={styles.headerContainer}>
                <View style={styles.bookCoverContainer}>
                  <Image source={book.image ? { uri: getFullImageUrl(book.image) } : defaultImage} style={styles.bookCover} />
                  <TouchableOpacity style={styles.heartIcon} onPress={handleLikeToggle}>
                    <Icon name={liked ? "heart" : "heart-o"} size={30} color="#FF0000" />
                  </TouchableOpacity>
                </View>
                <View style={styles.titleContainer}>
                  <Text style={styles.bookTitle}>{book.title}</Text>
                </View>
                <Text style={styles.bookAuthor}>{book.author}</Text>
                <Text style={styles.bookYear}>Published: {book.published_year}</Text>
                <Text style={styles.bookDescription}>{book.description}</Text>
                <Text style={styles.bookAvailableCopies}>Available Copies: {availableCopies}</Text>
                <TouchableOpacity
                  style={[styles.reserveButton, availableCopies === 0 && !reserved && styles.reserveButtonDisabled]}
                  onPress={handleReservationToggle}
                  disabled={availableCopies === 0 && !reserved} // Disable button if no copies are available and not reserved
                >
                  <Text style={styles.reserveButtonText}>{reserved ? 'Undo Reservation' : 'Reserve this book'}</Text>
                </TouchableOpacity>
                <Text style={styles.sectionTitle}>Reviews</Text>
                {reviews.map((item, index) => (
                  <View key={index} style={styles.reviewItem}>
                    <Text style={styles.reviewUser}>{item.user?.name || 'Anonymous'}</Text>
                    <View style={styles.reviewRating}>{renderStars(item.rating, () => {})}</View>
                    <Text style={styles.reviewText}>{item.review}</Text>
                    <Text style={styles.reviewDate}>{item.review_date}</Text>
                  </View>
                ))}
              </View>
              <TouchableOpacity style={styles.writeReviewButton} onPress={() => setModalVisible(true)}>
                <Text style={styles.writeReviewButtonText}>Write a Review</Text>
              </TouchableOpacity>
            </>
          )}
        </ScrollView>
      </TouchableWithoutFeedback>
      <View style={styles.navBarContainer}>
        <CustomNavBar navigation={navigation} />
      </View>
      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => {
          setModalVisible(!modalVisible);
        }}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalContent}>
            <Text style={styles.sectionTitle}>Write a Review</Text>
            <View style={styles.starsContainer}>
              {renderStars(rating, setRating)}
            </View>
            <TextInput
              style={styles.reviewInput}
              placeholder="Write your review here..."
              value={reviewText}
              onChangeText={setReviewText}
              multiline
            />
            <TouchableOpacity style={styles.submitButton} onPress={handleReviewSubmit} disabled={submitting}>
              <Text style={styles.submitButtonText}>{submitting ? 'Submitting...' : 'Send Review'}</Text>
            </TouchableOpacity>
            <TouchableOpacity style={styles.cancelButton} onPress={() => setModalVisible(false)}>
              <Text style={styles.cancelButtonText}>Cancel</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
      <Toast />
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  scrollViewContent: {
    padding: 20,
    alignItems: 'center',
    paddingBottom: 80,
  },
  reserveButtonDisabled: {
    backgroundColor: '#5c3e2b', // Darker shade of brown
  },
  headerContainer: {
    alignItems: 'center',
    marginBottom: 20,
  },
  bookCoverContainer: {
    position: 'relative',
  },
  bookCover: {
    width: 200,
    height: 300,
    borderRadius: 5,
    marginBottom: 20,
  },
  heartIcon: {
    position: 'absolute',
    top: -10,
    right: -10,
  },
  titleContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 10,
  },
  bookTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    textAlign: 'center',
    marginRight: 10,
  },
  bookAuthor: {
    fontSize: 18,
    color: '#666',
    textAlign: 'center',
    marginBottom: 10,
  },
  bookYear: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
    marginBottom: 10,
  },
  bookDescription: {
    fontSize: 16,
    color: '#999',
    textAlign: 'center',
  },
  bookAvailableCopies: {
    fontSize: 16,
    color: '#000',
    textAlign: 'center',
    marginTop: 10,
  },
  reserveButton: {
    backgroundColor: '#835e3f',
    padding: 10,
    borderRadius: 5,
    alignItems: 'center',
    marginTop: 10,
  },
  reserveButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 20,
    marginBottom: 10,
  },
  reviewList: {
    width: '100%',
  },
  reviewItem: {
    backgroundColor: '#f9f9f9',
    padding: 20,
    borderRadius: 5,
    marginBottom: 10,
    width: 300,
  },
  reviewUser: {
    fontSize: 18,
    fontWeight: 'bold',
  },
  reviewRating: {
    flexDirection: 'row',
    marginVertical: 5,
  },
  reviewText: {
    fontSize: 16,
    color: '#333',
    marginTop: 5,
  },
  reviewDate: {
    fontSize: 14,
    color: '#999',
    marginTop: 5,
  },
  writeReviewButton: {
    backgroundColor: '#835e3f',
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
    marginTop: 20,
  },
  writeReviewButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  navBarContainer: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    height: 60,
    backgroundColor: '#fff',
    justifyContent: 'center',
    alignItems: 'center',
    borderTopWidth: 1,
    borderTopColor: '#ccc',
  },
  modalContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
  },
  modalContent: {
    width: '80%',
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 20,
    alignItems: 'center',
  },
  starsContainer: {
    flexDirection: 'row',
    marginBottom: 10,
  },
  reviewInput: {
    height: 100,
    borderColor: '#ccc',
    borderWidth: 1,
    borderRadius: 5,
    padding: 10,
    marginBottom: 10,
    textAlignVertical: 'top',
    width: '100%',
  },
  submitButton: {
    backgroundColor: '#835e3f',
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
    width: '100%',
    marginBottom: 10,
  },
  submitButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  cancelButton: {
    backgroundColor: '#ccc',
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
    width: '100%',
  },
  cancelButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});

export default BookDetailScreen;