<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', '/user'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['https://synko-frontend.vercel.app'],  // <-- your Vite frontend

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // <-- needed for cookies / sanctum
];



