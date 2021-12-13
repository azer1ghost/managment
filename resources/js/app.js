require('./bootstrap');
require('jquery-confirm');
require('./delete');
require('./custom');

const firebaseConfig = {
    apiKey: "AIzaSyAUcgmjbHOEs5e83KO9tOBRQokUgvgseaY",
    authDomain: "mobilmanagement-35055.firebaseapp.com",
    projectId: "mobilmanagement-35055",
    storageBucket: "mobilmanagement-35055.appspot.com",
    messagingSenderId: "774485323317",
    appId: "1:774485323317:web:483587d8210795b83ccb7c",
    databaseURL: "https://mobilmanagement-35055-default-rtdb.firebaseio.com/",
};

// Initialize Firebase
if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
}