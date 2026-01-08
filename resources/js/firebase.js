// Init Firebase
const firebaseConfig = {
    apiKey: "API_KEY",
    authDomain: "PROJECT_ID.firebaseapp.com",
    projectId: "PROJECT_ID",
    messagingSenderId: "SENDER_ID",
    appId: "APP_ID"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// Request permission saat halaman sudah siap
window.addEventListener('load', () => {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            messaging.getToken().then(token => {
                console.log('FCM Token:', token);
                // TODO: kirim token ke Laravel (opsional)
            });
        }
    });
});
