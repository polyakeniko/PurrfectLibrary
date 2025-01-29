import { CameraView, useCameraPermissions } from "expo-camera";
import {
  SafeAreaView,
  StyleSheet,
  View
} from "react-native";
import { useEffect, useState } from "react";
import * as SecureStore from 'expo-secure-store';
import { useNavigation } from '@react-navigation/native';

export default function ScanScreen() {
  const [permission, requestPermission] = useCameraPermissions();
  const isPermissionGranted = Boolean(permission?.granted);
  const [qrValue, setQrValue] = useState(null);
  const navigation = useNavigation();

  useEffect(() => {
    requestPermission();
  }, []);

  const handleBarcodeScanned = async ({ data }) => {
    if (data && !qrValue) {
      console.log(data, " QR CODE DATA ");
      setQrValue(data);

      // Parse the QR code data
      const item = JSON.parse(data);

      // Store the scanned data in AsyncStorage
      await SecureStore.setItem('scannedBook', data);

      // Navigate to the BookDetail screen with the book data
      navigation.navigate('BookDetail', { book: item });
    }
  };

  return (
    <SafeAreaView style={StyleSheet.absoluteFillObject}>
      {isPermissionGranted ? (
        <CameraView
          style={StyleSheet.absoluteFillObject}
          facing="back"
          onBarcodeScanned={handleBarcodeScanned}
        />
      ) : null}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
});