require('./bootstrap');
require('./template');
require('./off-canvas');
require('./settings');
require('./hoverable-collapse');
require('./clock');
require('jquery-confirm/dist/jquery-confirm.min');
require('moment');
require('daterangepicker');
require('select2/dist/js/select2.min');
require('bootstrap-select/dist/js/bootstrap-select.min');
require('./delete');
require('./custom');


$.fn.selectpicker.Constructor.BootstrapVersion = '4.6.0';

const firebaseConfig = {
    apiKey: process.env.MIX_FIREBASE_API_KEY,
    authDomain: process.env.MIX_FIREBASE_AUTH_DOMAIN,
    projectId: process.env.MIX_FIREBASE_PROJECT_ID,
    storageBucket: process.env.MIX_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: process.env.MIX_FIREBASE_MESSAGING_SENDDER_ID,
    appId: process.env.MIX_FIREBASE_APP_ID,
    databaseURL: process.env.MIX_FIREBASE_DATABASE_URL,
};

// Initialize Firebase
if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
}