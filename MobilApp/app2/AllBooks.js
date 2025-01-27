import React, { useContext, useState, useEffect } from 'react';
import { StyleSheet, Text, View, FlatList, Image, TouchableOpacity, ActivityIndicator } from 'react-native';
import CustomNavBar from './CustomNavBar';
import { UserContext } from './UserContext';
import axios from 'axios';
import defaultImage from './assets/default.jpg';
const AllBooksScreen = ({ navigation }) => {
  const { token } = useContext(UserContext);
  const [books, setBooks] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchBooks = async () => {
      try {
        const response = await axios.get('http://cats.stud.vts.su.ac.rs/api/books', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        setBooks(response.data);
      } catch (error) {
        console.error('Error fetching books:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchBooks();
  }, [token]);

  const getFullImageUrl = (relativePath) => {
    return `http://cats.stud.vts.su.ac.rs/storage/${relativePath}`;
  };

  const renderBookItem = ({ item }) => (
    <TouchableOpacity onPress={() => navigation.navigate('BookDetail', { book: item })}>
      <View style={styles.bookItem}>
        <Image source={item.image ?{ uri: getFullImageUrl(item.image)} : defaultImage} style={styles.bookCover} />
        <Text style={styles.bookTitle}>{item.title}</Text>
        <Text style={styles.bookAuthor}>{item.author}</Text>
        <Text style={styles.bookDate}>Published: {item.published_year}</Text>
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
        data={books}
        renderItem={renderBookItem}
        keyExtractor={(item) => item.id ? item.id.toString() : Math.random().toString()}
        numColumns={2}
        contentContainerStyle={styles.listContainer}
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
    justifyContent: 'center',
    alignItems: 'center',
  },
  listContainer: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  bookItem: {
    flex: 1,
    margin: 10,
    alignItems: 'center',
    width: 150,
    marginBottom: 70,
  },
  bookCover: {
    width: 100,
    height: 150,
    borderRadius: 5,
    marginBottom: 5,
  },
  bookTitle: {
    fontSize: 14,
    color: '#000',
    textAlign: 'center',
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

export default AllBooksScreen;