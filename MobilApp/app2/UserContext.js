import React, { createContext, useState, useEffect } from 'react';
import * as SecureStore from 'expo-secure-store';

export const UserContext = createContext();

export const UserProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);

  useEffect(() => {
    const loadUser = async () => {
      try {
        const userData = await SecureStore.getItem('user');
        const userToken = await SecureStore.getItem('token');
        if (userData && userToken) {
          setUser(JSON.parse(userData));
          setToken(userToken);
        }
      } catch (error) {
        console.error('Failed to load user data', error);
      }
    };

    loadUser();
  }, []);

  const saveUser = async (user, token) => {
    try {
      if (user && token) {
        await SecureStore.setItem('user', JSON.stringify(user));
        await SecureStore.setItem('token', token);
        setUser(user);
        setToken(token);
      } else {
        console.error('Invalid user or token:', user, token);
      }
    } catch (error) {
      console.error('Failed to save user data', error);
    }
  };

  const clearUser = async () => {
    try {
      await SecureStore.removeItem('user');
      await SecureStore.removeItem('token');
      setUser(null);
      setToken(null);
    } catch (error) {
      console.error('Failed to clear user data', error);
    }
  };

  return (
    <UserContext.Provider value={{ user, token, saveUser, clearUser }}>
      {children}
    </UserContext.Provider>
  );
};