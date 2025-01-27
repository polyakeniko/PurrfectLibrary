import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Image, ActivityIndicator, SectionList, TouchableOpacity } from 'react-native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import CustomNavBar from './CustomNavBar';
import defaultImage from './assets/default.jpg';

const BookScreen = ({ navigation }) => {
  const [borrowedBooks, setBorrowedBooks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [token, setToken] = useState('');
  const [borrowedBooksMessage, setBorrowedBooksMessage] = useState('');

  useEffect(() => {
    fetchToken();
  }, []);

  useEffect(() => {
    if (token) {
      fetchBorrowedBooks();
    }
  }, [token]);

  const fetchToken = async () => {
    try {
      const storedToken = await AsyncStorage.getItem('token');
      if (storedToken) {
        setToken(storedToken);
      }
    } catch (error) {
      console.error('Error fetching token:', error);
    }
  };

  const fetchBorrowedBooks = async () => {
    try {
      const response = await axios.get('http://cats.stud.vts.su.ac.rs/api/book-copies/borrowed', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      if (response.data && Array.isArray(response.data)) {
        const borrowedBooksData = response.data.map(item => item.book);
        setBorrowedBooks(borrowedBooksData);
      } else if (response.data && response.data.message) {
        setBorrowedBooksMessage(response.data.message);
      } else {
        console.error('Unexpected response data:', response.data);
      }
    } catch (error) {
      console.error('Error fetching borrowed books:', error.response ? error.response.data : error.message);
    } finally {
      setLoading(false);
    }
  };

  const getFullImageUrl = (relativePath) => {
    return `http://cats.stud.vts.su.ac.rs/storage/${relativePath}`;
  };

  const renderBookItem = ({ item }) => (
    <TouchableOpacity onPress={() => navigation.navigate('BookDetail', { book: item })}>
      <View style={styles.bookItem}>
        <Image source={item.image ? { uri: getFullImageUrl(item.image) } : defaultImage} style={styles.bookImage} />
        <View style={styles.bookInfo}>
          <Text style={styles.bookTitle}>{item.title}</Text>
          <Text style={styles.bookAuthor}>{item.author}</Text>
          <Text style={styles.bookYear}>Published: {item.published_year}</Text>
          <Text style={styles.bookDescription}>{item.description}</Text>
        </View>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {loading ? (
        <ActivityIndicator size="large" color="#0000ff" />
      ) : (
        <>
          {borrowedBooksMessage ? (
            <Text style={styles.emptyText}>{borrowedBooksMessage}</Text>
          ) : (
            <SectionList
              sections={[{ title: 'Borrowed Books', data: borrowedBooks }]}
              renderItem={renderBookItem}
              renderSectionHeader={({ section: { title } }) => (
                <Text style={styles.title}>{title}</Text>
              )}
              keyExtractor={(item) => item.id.toString()}
              contentContainerStyle={styles.listContent}
              ListEmptyComponent={() => (
                <Text style={styles.emptyText}>You haven't borrowed any books yet!</Text>
              )}
            />
          )}
        </>
      )}
      <CustomNavBar navigation={navigation} />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#333',
    textAlign: 'center',
    marginVertical: 20,
  },
  listContent: {
    paddingHorizontal: 20,
  },
  bookItem: {
    flexDirection: 'row',
    marginBottom: 20,
    backgroundColor: '#e6e6e6',
    borderRadius: 5,
    overflow: 'hidden',
    elevation: 2,
  },
  bookImage: {
    width: 100,
    height: 150,
    margin: 10, // Added margin to the book image
  },
  bookInfo: {
    flex: 1,
    padding: 10,
  },
  bookTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
  },
  bookAuthor: {
    fontSize: 16,
    color: '#666',
    marginTop: 5,
  },
  bookYear: {
    fontSize: 14,
    color: '#999',
    marginTop: 5,
  },
  bookDescription: {
    fontSize: 14,
    color: '#999',
    marginTop: 5,
  },
  emptyText: {
    fontSize: 18,
    color: '#999',
    textAlign: 'center',
    marginTop: 20,
  },
});

export default BookScreen;