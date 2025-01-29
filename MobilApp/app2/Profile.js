import React, { useContext, useState, useEffect } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, Alert, TextInput, Modal } from 'react-native';
import axios from 'axios';
import Icon from 'react-native-vector-icons/FontAwesome';
import CustomNavBar from './CustomNavBar';
import { UserContext } from './UserContext';
import * as SecureStore from 'expo-secure-store';

const ProfileScreen = ({ navigation }) => {
  const { user, token, clearUser } = useContext(UserContext);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [errorMessage, setErrorMessage] = useState('');
  const [modalVisible, setModalVisible] = useState(false);
  const [editField, setEditField] = useState('');
  const [editValue, setEditValue] = useState('');

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const response = await axios.get('https://cats.stud.vts.su.ac.rs/api/profile', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        if (response.status === 200) {
          setName(response.data.name);
          setEmail(response.data.email);
        } else {
          Alert.alert('Error!', 'Failed to fetch profile information');
        }
      } catch (error) {
        console.error('Fetch profile error:', error.response ? error.response.data : error.message);
        Alert.alert('Error!', 'An error occurred while fetching profile information');
      }
    };

    fetchProfile();
  }, [token]);

  const handleUpdateProfile = async () => {
    let endpoint = '';
    let data = {};

    if (editField === 'name') {
      endpoint = 'http://cats.stud.vts.su.ac.rs/api/profile/name';
      data = { name: editValue };
    } else if (editField === 'email') {
      endpoint = 'http://cats.stud.vts.su.ac.rs/api/profile/email';
      data = { email: editValue };
    } else if (editField === 'password') {
      if (editValue !== confirmPassword) {
        setErrorMessage('Passwords do not match');
        return;
      }
      endpoint = 'http://cats.stud.vts.su.ac.rs/api/profile/password';
      data = { password: editValue, password_confirmation: confirmPassword };
    }

    try {
      const response = await axios.put(endpoint, data, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });

      if (response.status === 200) {
        Alert.alert('Success!', `${editField.charAt(0).toUpperCase() + editField.slice(1)} updated successfully`);
        setErrorMessage(''); // Clear any previous error messages
        if (editField === 'name') setName(editValue);
        if (editField === 'email') setEmail(editValue);
        if (editField === 'password') {
          setPassword('');
          setConfirmPassword('');
        }
        setModalVisible(false); // Close the modal
      } else {
        setErrorMessage(`Failed to update ${editField}`);
      }
    } catch (error) {
      console.error(`Update ${editField} error:`, error.response ? error.response.data : error.message);
      if (error.response && error.response.status === 422) {
        const errors = error.response.data.errors;
        if (errors[editField]) {
          setErrorMessage(errors[editField][0]);
        } else {
          setErrorMessage(`An error occurred while updating ${editField}`);
        }
      } else {
        setErrorMessage(`An error occurred while updating ${editField}`);
      }
    }
  };

  const showEditModal = (field, value) => {
    setEditField(field);
    setEditValue(value);
    setModalVisible(true);
  };

  const handleLogout = async () => {
    try {
      const response = await axios.post('https://cats.stud.vts.su.ac.rs/api/logout', {}, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });

      if (response.status === 200) {
        await SecureStore.removeItem('token'); // Clear the token from AsyncStorage
        clearUser(); // Clear the user data from the context
        navigation.reset({
          index: 0,
          routes: [{ name: 'Login' }],
        });
      } else {
        Alert.alert('Error!', 'Failed to log out');
      }
    } catch (error) {
      console.error('Logout error:', error.response ? error.response.data : error.message);
      await SecureStore.removeItem('token');
      clearUser();
      navigation.reset({
        index: 0,
        routes: [{ name: 'Login' }],
      });
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.header}>Your Profile</Text>

      <View style={styles.profileItem}>
        <Text style={styles.label}>Name: {name}</Text>
        <TouchableOpacity style={styles.editButton} onPress={() => showEditModal('name', name)}>
          <Icon name="pencil" size={20} color="#fff" />
        </TouchableOpacity>
      </View>

      <View style={styles.profileItem}>
        <Text style={styles.label}>Email: {email}</Text>
        <TouchableOpacity style={styles.editButton} onPress={() => showEditModal('email', email)}>
          <Icon name="pencil" size={20} color="#fff" />
        </TouchableOpacity>
      </View>

      <View style={styles.profileItem}>
        <Text style={styles.label}>Password: ******</Text>
        <TouchableOpacity style={styles.editButton} onPress={() => showEditModal('password', password)}>
          <Icon name="pencil" size={20} color="#fff" />
        </TouchableOpacity>
      </View>

      {errorMessage ? <Text style={styles.errorText}>{errorMessage}</Text> : null}

      <TouchableOpacity style={styles.button} onPress={handleLogout}>
        <Text style={styles.buttonText}>Log Out</Text>
      </TouchableOpacity>

      <CustomNavBar navigation={navigation} />

      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => {
          setModalVisible(!modalVisible);
        }}
      >
        <View style={styles.modalView}>
          <Text style={styles.modalText}>Edit {editField}</Text>
          {editField === 'password' ? (
            <>
              <TextInput
                style={styles.input}
                placeholder="Enter new password"
                placeholderTextColor="#aaa"
                secureTextEntry
                value={editValue}
                onChangeText={setEditValue}
              />
              <TextInput
                style={styles.input}
                placeholder="Confirm new password"
                placeholderTextColor="#aaa"
                secureTextEntry
                value={confirmPassword}
                onChangeText={setConfirmPassword}
              />
            </>
          ) : (
            <TextInput
              style={styles.input}
              placeholder={`Enter new ${editField}`}
              placeholderTextColor="#aaa"
              value={editValue}
              onChangeText={setEditValue}
            />
          )}
          <TouchableOpacity style={styles.button} onPress={handleUpdateProfile}>
            <Text style={styles.buttonText}>Save</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={[styles.button, styles.buttonClose]}
            onPress={() => setModalVisible(!modalVisible)}
          >
            <Text style={styles.buttonText}>Cancel</Text>
          </TouchableOpacity>
        </View>
      </Modal>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  header: {
    fontSize: 30,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 20,
    textAlign: 'center',
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 20,
  },
  profileItem: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 10,
    width: '100%',
    paddingHorizontal: 15,
  },
  label: {
    fontSize: 18,
    color: '#333',
    flex: 1,
  },
  editButton: {
    backgroundColor: '#835e3f',
    borderRadius: 50,
    padding: 10,
  },
  errorText: {
    color: 'red',
    marginBottom: 10,
  },
  button: {
    width: '50%',
    height: 40,
    backgroundColor: '#333',
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 5,
    marginBottom: 10,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
  },
  modalView: {
    margin: 20,
    backgroundColor: 'white',
    borderRadius: 20,
    padding: 35,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
  modalText: {
    marginBottom: 15,
    textAlign: 'center',
    fontSize: 20,
    fontWeight: 'bold',
  },
  buttonClose: {
    backgroundColor: '#aaa',
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
});

export default ProfileScreen;