import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Image, ActivityIndicator, SectionList, TouchableOpacity } from 'react-native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import CustomNavBar from './CustomNavBar';
import defaultImage from './assets/default.jpg';

const LikedBooksScreen = ({ navigation }) => {
  const [likedBooks, setLikedBooks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [token, setToken] = useState('');

  useEffect(() => {
    fetchToken();
  }, []);

  useEffect(() => {
    if (token) {
      fetchLikedBooks();
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

  const fetchLikedBooks = async () => {
    try {
      const response = await axios.get('http://cats.stud.vts.su.ac.rs/api/books/liked', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      setLikedBooks(response.data);
    } catch (error) {
      console.error('Error fetching liked books:', error);
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
        <SectionList
          sections={[{ title: 'Liked Books', data: likedBooks }]}
          renderItem={renderBookItem}
          renderSectionHeader={({ section: { title } }) => (
            <Text style={styles.title}>{title}</Text>
          )}
          keyExtractor={(item) => item.id.toString()}
          contentContainerStyle={styles.listContent}
          ListEmptyComponent={() => (
            <Text style={styles.emptyText}>You haven't liked any books yet!</Text>
          )}
        />
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

export default LikedBooksScreen;