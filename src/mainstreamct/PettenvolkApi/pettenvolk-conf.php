<?php

return [
    
    // API environment details, such as API keys, should be configured in .env
    'api_key' => env('PETTENVOLK_API_KEY', 'test_api_key');
    'app_mode' => env('APP_ENV', 'local');

];
