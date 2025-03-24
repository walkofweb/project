<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyA4taV9kptmPnocbJ0HBfNSkVB5iMnoQ0Q",
        authDomain: "project234-83f99.firebaseapp.com",
        projectId: "project234-83f99",
        storageBucket: "project234-83f99.firebasestorage.app",
        messagingSenderId: "781091026231",
        appId: "1:781091026231:web:69021e5f4f10321540d6b2"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function requestPermission() {
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                console.log("Notification permission granted.");
                messaging.getToken({ vapidKey: "your-vapid-key" }).then((currentToken) => {
                    if (currentToken) {
                        console.log("FCM Token:", currentToken);
                        // Send this token to your Laravel backend for storing
                    } else {
                        console.log("No registration token available.");
                    }
                });
            } else {
                console.log("Notification permission denied.");
            }
        });
    }

    messaging.onMessage((payload) => {
        console.log("Message received: ", payload);
        new Notification(payload.notification.title, {
            body: payload.notification.body,
            icon: payload.notification.icon
        });
    });
</script>

<button onclick="requestPermission()">Enable Notifications</button>
