<?php

return [
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'credentials' => env('FIREBASE_CREDENTIALS_PATH'), // Path to your service account JSON file
    
    // Or you can use the JSON content directly
    'credentials_json' => env('FIREBASE_CREDENTIALS_JSON'),
    
    // Firestore database
    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],
    
    // Firebase Cloud Messaging
    'fcm' => [
        'server_key' => env('FCM_SERVER_KEY'),
    ],
];