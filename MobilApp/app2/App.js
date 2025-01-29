import React, { useState, useEffect } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { UserProvider } from './UserContext';
import * as SecureStore from 'expo-secure-store';
import LoginScreen from './Login';
import HomeScreen from './Home';
import BookScreen from './Book';
import ProfileScreen from './Profile';
import RegisterScreen from './Register';
import BookDetailScreen from './BookDetail';
import AllBooksScreen from './AllBooks';
import ScanScreen from './Scan'; 
import LikedBooksScreen from './LikedBooks';

const Stack = createNativeStackNavigator();

const App = () => {
  const [initialRoute, setInitialRoute] = useState('Login');

  useEffect(() => {
    const checkLoginStatus = async () => {
      const loggedIn = await SecureStore.getItemAsync('loggedIn');
      if (loggedIn) {
        setInitialRoute('Home');
      }
    };

    checkLoginStatus();
  }, []);

  return (
    <UserProvider>
      <NavigationContainer>
        <Stack.Navigator initialRouteName={initialRoute}>
          <Stack.Screen 
            name="Login" 
            component={LoginScreen} 
            options={{ title: 'Log In' }} 
          />
          <Stack.Screen 
            name="Home" 
            component={HomeScreen} 
          />
          <Stack.Screen 
            name="Scan" 
            component={ScanScreen} 
          />
          <Stack.Screen 
            name="Register" 
            component={RegisterScreen} 
          />
          <Stack.Screen
            name="BookDetail" 
            component={BookDetailScreen}
          />
          <Stack.Screen 
            name="Book" 
            component={BookScreen} 
          />
          <Stack.Screen 
            name="Profile" 
            component={ProfileScreen} 
          />
          <Stack.Screen 
            name="AllBooks" 
            component={AllBooksScreen} 
          />
          <Stack.Screen 
            name="LikedBooks" 
            component={LikedBooksScreen} 
          />
        </Stack.Navigator>
      </NavigationContainer>
    </UserProvider>
  );
};

export default App;