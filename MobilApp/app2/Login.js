import React, { useContext, useState } from 'react';
import { StyleSheet, TextInput, Text, View, TouchableOpacity, ActivityIndicator } from 'react-native';
import axios from 'axios';
import { UserContext } from './UserContext';
import { CommonActions } from '@react-navigation/native';

const LoginScreen = ({ navigation }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errorMessage, setErrorMessage] = useState('');
  const [loading, setLoading] = useState(false);
  const { saveUser } = useContext(UserContext);

  const handleLogin = async () => {
    setLoading(true);
    try {
      const response = await axios.post('https://cats.stud.vts.su.ac.rs/api/login', {
        email: email,
        password: password,
      });

      console.log('Login response:', response.data);

      if (response.status === 200) {
        const { name } = response.data.user; // Get the user's name from the response
        const { token } = response.data; // Get the token from the response
        if (name && token) {
          saveUser(name, token); // Save the user and token in the context and AsyncStorage
          // Reset the navigation stack and navigate to Home screen
          navigation.dispatch(
            CommonActions.reset({
              index: 0,
              routes: [{ name: 'Home' }],
            })
          );
        } else {
          setErrorMessage('Invalid response from server');
        }
      } else {
        setErrorMessage('Wrong Password or Email');
      }
    } catch (error) {
      if (error.response) {
        if (error.response.status === 403) {
          setErrorMessage('The account has been banned! Or the email address hasn not been verified');
        } 
        else {
          setErrorMessage('Wrong Password or Email');
        }
      } else {
        setErrorMessage('An error occurred. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Welcome!</Text>

      <TextInput
        style={styles.input}
        placeholder="Email"
        placeholderTextColor="#aaa"
        value={email}
        onChangeText={setEmail}
      />
      <TextInput
        style={styles.input}
        placeholder="Password"
        placeholderTextColor="#aaa"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />

      {errorMessage ? <Text style={styles.errorText}>{errorMessage}</Text> : null}

      {loading ? (
        <ActivityIndicator size="large" color="#835e3f" />
      ) : (
        <TouchableOpacity style={styles.button} onPress={handleLogin}>
          <Text style={styles.buttonText}>Log In</Text>
        </TouchableOpacity>
      )}

      <TouchableOpacity onPress={() => navigation.navigate('Register')}>
        <Text style={styles.registerText}>Don't have an account? Register</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
    padding: 20,
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 20,
  },
  input: {
    width: '100%',
    height: 40,
    borderColor: '#ccc',
    borderWidth: 1,
    marginBottom: 10,
    paddingHorizontal: 10,
    borderRadius: 5,
  },
  errorText: {
    color: 'red',
    marginBottom: 10,
    textAlign: 'left',
  },
  button: {
    width: '100%',
    height: 40,
    backgroundColor: '#835e3f',
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 5,
    marginBottom: 10,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
  },
  registerText: {
    color: '#333',
    fontSize: 14,
    textDecorationLine: 'underline',
  },
});

export default LoginScreen;