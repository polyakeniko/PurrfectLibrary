// CustomNavBar.js
import React from 'react';
import { View, TouchableOpacity, StyleSheet } from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';

const CustomNavBar = ({ navigation }) => {
  return (
    <View style={styles.navBar}>
     
      <TouchableOpacity 
        style={styles.button} 
        onPress={() => navigation.navigate('Home')}
      >
        <Icon name="home" size={30} color="#fff" />
      </TouchableOpacity>

    
      <TouchableOpacity 
        style={styles.button} 
        onPress={() => navigation.navigate('Book')}
      >
        <Icon name="book" size={30} color="#fff" />
      </TouchableOpacity>
      <TouchableOpacity 
        style={styles.button} 
        onPress={() => navigation.navigate('LikedBooks')}
      >
        <Icon name="heart" size={30} color="#fff" />
      </TouchableOpacity>
      <TouchableOpacity 
        style={styles.button} 
        onPress={() => navigation.navigate('Scan')}
      >
        <Icon name="qrcode" size={30} color="#fff" />
      </TouchableOpacity>
     
      <TouchableOpacity 
        style={styles.button} 
        onPress={() => navigation.navigate('Profile')}
      >
        <Icon name="user" size={30} color="#fff" />
      </TouchableOpacity>
    
    </View>
  );
};

const styles = StyleSheet.create({
  navBar: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    width: '100%',
    height: 60,
    backgroundColor: '#835e3f',
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
  },
  button: {
    justifyContent: 'center',
    alignItems: 'center',
    width: 60,
    height: 60,
  },
});

export default CustomNavBar;
