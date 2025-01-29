import React, { useContext, useState, useEffect } from 'react';
import { StyleSheet, Text, View, FlatList, Image, TouchableOpacity, ActivityIndicator } from 'react-native';
import CustomNavBar from './CustomNavBar';
import { UserContext } from './UserContext';
import axios from 'axios';
import Icon from 'react-native-vector-icons/FontAwesome';
import defaultImage from './assets/default.jpg';
import * as SecureStore from 'expo-secure-store';

const HomeScreen = ({ navigation }) => {
  const { user, token } = useContext(UserContext);
  const [newBooks, setNewBooks] = useState([]);
  const [popularBooks, setPopularBooks] = useState([]);
  const [mostLikedBooks, setMostLikedBooks] = useState([]);
  const [allBooks, setAllBooks] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchBooks = async () => {
      try {
        const newResponse = await axios.get('http://cats.stud.vts.su.ac.rs/api/books/new', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        const popularResponse = await axios.get('http://cats.stud.vts.su.ac.rs/api/books/popular', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        const mostLikedResponse = await axios.get('http://cats.stud.vts.su.ac.rs/api/books/most-liked', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        const allBooksResponse = await axios.get('http://cats.stud.vts.su.ac.rs/api/books', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        setNewBooks(newResponse.data.slice(0, 3)); // Limit to 3 newest books
        setPopularBooks(popularResponse.data); // No need to slice, API already limits to 3
        setMostLikedBooks(mostLikedResponse.data);
        setAllBooks(allBooksResponse.data);
      } catch (error) {
        
      } finally {
        setLoading(false);
      }
    };

    fetchBooks();
  }, [token]);

  const getFullImageUrl = (relativePath) => {
    return `http://cats.stud.vts.su.ac.rs/storage/${relativePath}`;
  };

  const renderBookItemPopular = ({ item }) => (
    <TouchableOpacity onPress={() => navigation.navigate('BookDetail', { book: item })}>
      <View style={styles.bookItem}>
      <Image source={item.image ?{ uri: getFullImageUrl(item.image)} : defaultImage} style={styles.bookCover} />
        <Text style={styles.bookTitle}>{item.title}</Text>
        <Text style={styles.bookAuthor}>{item.author}</Text>
        <Text style={styles.bookRating}>Rating: {item.average_rating ? item.average_rating.toFixed(1) : 'N/A'}<Icon name="star" size={15} color="#835e3f" /></Text>
      </View>
    </TouchableOpacity>
  );

  const renderBookItem = ({ item }) => (
    <TouchableOpacity onPress={() => navigation.navigate('BookDetail', { book: item })}>
      <View style={styles.bookItemVertical}>
        <Image source={item.image ?{ uri: getFullImageUrl(item.image)} : defaultImage} style={styles.bookCoverVertical} />
        <View style={styles.bookInfo}>
          <Text style={styles.bookTitleVertical}>{item.title}</Text>
          <Text style={styles.bookLikes}>{item.liked_by_users_count} Likes</Text>
        </View>
      </View>
    </TouchableOpacity>
  );

  const renderHorizontalBookItem = ({ item }) => (
    <TouchableOpacity onPress={() => navigation.navigate('BookDetail', { book: item })}>
      <View style={styles.bookItem}>
      <Image source={item.image ?{ uri: getFullImageUrl(item.image)} : defaultImage} style={styles.bookCover} />
        <Text style={styles.bookTitle}>{item.title}</Text>
        <Text style={styles.bookAuthor}>{item.author}</Text>
        <Text style={styles.bookDate}>Published: {item.published_year}</Text>
      </View>
    </TouchableOpacity>
  );

  const renderMostLikedBooks = ({ item }) => (
    <TouchableOpacity onPress={() => navigation.navigate('BookDetail', { book: item })}>
      <View style={styles.bookItemLike}>
      <Image source={item.image ?{ uri: getFullImageUrl(item.image)} : defaultImage} style={styles.bookCoverLike} />
        <View style={styles.bookInfo}>
          <Text style={styles.bookTitleLike}>{item.title}</Text>
          <View style={styles.likesContainer}>
            <Icon name="heart" size={20} color="#FF0000" />
            <Text style={styles.bookLike}>{item.liked_by_users_count}</Text>
          </View>
        </View>
      </View>
    </TouchableOpacity>
  );

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#835e3f" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
      style={styles.scrollViewContent}
        data={mostLikedBooks}
        renderItem={renderMostLikedBooks}
        keyExtractor={(item) => item.id ? item.id.toString() : Math.random().toString()}
        ListHeaderComponent={
          <>
            <View style={styles.welcomeContainer}>
              <Text style={styles.title}>Welcome, {user}!</Text>
              <Image style={styles.catimage} source={require('./assets/cat.png')} />
            </View>

            <Text style={styles.sectionTitle}>Popular Books</Text>
            <FlatList
              style={styles.listContainer}
              data={popularBooks}
              renderItem={renderBookItemPopular}
              keyExtractor={(item) => item.id ? item.id.toString() : Math.random().toString()}
              horizontal
              showsHorizontalScrollIndicator={false}
              contentContainerStyle={styles.listContainer}
            />

            <Text style={styles.sectionTitle}>Newest Books</Text>
            <FlatList
              style={styles.listContainer}
              data={newBooks}
              renderItem={renderHorizontalBookItem}
              keyExtractor={(item) => item.id ? item.id.toString() : Math.random().toString()}
              horizontal
              showsHorizontalScrollIndicator={false}
              contentContainerStyle={styles.listContainer}
            />

            <TouchableOpacity style={styles.offerCard} onPress={() => navigation.navigate('AllBooks')}>
              <Image style={styles.offerImage} source={require('./assets/offer.jpg')} />
              <Text style={styles.offerText}>Click here to see all of our offers.</Text>
            </TouchableOpacity>

            <Text style={styles.sectionTitle}>Most Liked Books</Text>
          </>
        }
        contentContainerStyle={styles.listContainerVertical}
      />
      <View style={styles.navBarContainer}>
        <CustomNavBar navigation={navigation} />
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  scrollViewContent: {
    marginBottom: 60,
    
  },
  welcomeContainer: {
    alignItems: 'center',
    marginBottom: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
  },
  catimage: {
    width: 100,
    height: 100,
    marginTop: 10,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 20,
    marginBottom: 10,
  },
  bookCoverLike: {
    width: 50,
    height: 80,
    marginRight: 10, 
    borderRadius: 5,
  },
  bookTitleLike: {
    fontSize: 16,
    fontWeight: 'bold',
    marginBottom: 5, 
  },
  bookLike: {
    fontSize: 14,
    color: '#FF0000',
    marginLeft: 5,
  },
  likesContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 5,
  },
  listContainer: {
    paddingTop: 10,
    paddingBottom: 10,
    paddingLeft: 20,
    paddingRight: 20,
    backgroundColor: '#e6e6e6',
    marginBottom: 15,
    borderRadius: 15,
  },
  listContainerVertical: {
    paddingTop: 10,
    paddingBottom: 10,
    paddingLeft: 20,
    paddingRight: 20,
    backgroundColor: '#fff',
    marginBottom: 15,
    borderRadius: 10,
  },
  bookItem: {
    marginRight: 15,
    alignItems: 'center',
    width: 150,
  },
  bookItemLike: {
    flexDirection: 'row', 
    alignItems: 'flex-start', 
    padding: 10,

  },
  bookItemVertical: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 15,
  },
  bookCover: {
    width: 100,
    height: 150,
    borderRadius: 5,
    marginBottom: 5,
  },
  bookCoverVertical: {
    width: 100,
    height: 150,
    borderRadius: 5,
    marginRight: 10,
  },
  bookInfo: {
    flex: 1,
  },
  bookTitle: {
    fontSize: 14,
    color: '#000',
    textAlign: 'center',
  },
  bookTitleVertical: {
    fontSize: 16,
    color: '#000',
    fontWeight: 'bold',
  },
  bookAuthor: {
    fontSize: 12,
    color: '#000',
    textAlign: 'center',
  },
  bookDate: {
    fontSize: 12,
    color: '#000',
    textAlign: 'center',
  },
  bookLikes: {
    fontSize: 14,
    color: '#000',
  },
  offerCard: {
    alignItems: 'center',
    marginVertical: 20,
    padding: 20,
    backgroundColor: '#e6e6e6',
    borderRadius: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.8,
    shadowRadius: 2,
    elevation: 5,
  },
  offerImage: {
    width: 250,
    height: 100,
    marginBottom: 10,
    borderTopRightRadius: 10,
    borderTopLeftRadius: 10,
  },
  offerText: {
    fontSize: 16,
    color: '#333',
    textAlign: 'center',
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
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
});

export default HomeScreen;