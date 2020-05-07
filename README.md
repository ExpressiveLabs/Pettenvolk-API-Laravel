# Pettenvolk API for Laravel
### Depreciation notice: the Pettenvolk API is moving to Laravel Passport authentication. For authenticating your users using their Pettenvolk account, refer to the Passport documentation. A complete overview of API endpoints will be available in the developer portal in the near future.

Recommended tools to complete this integration:<br/>
☕🎶💻⏰

This is the official package to integrate the Pettenvolk API (a.k.a. Chameleon) with your Laravel application. API keys can be acquired using the Pettenvolk Developer Tools at https://www.pettenvolk.com/developer (currently only available to invited testers). Docs for this API can be found at https://chameleon.pettenvolk.com.

## Table of contents
1. [Installation](#installation)<br/>
   1. [Using Composer](#using-composer)
2. [Configuration](#configuration)
3. [Usage](#usage)<br/>
   1. [Authentication](#authentication)<br/>
   1. [Fetching users](#fetching-users)
4. [Bugs](#bugs)
5. [Some final notes](#some-final-notes)

## Installation
### Using Composer
First of all, install the package. Y'all know the drill, but as a reminder:<br/>
```composer require mainstreamct/pettenvolk-api```

After installing, edit your config/app.php:
```php
   // Providers array:
   mainstreamct\PettenvolkApi\PettenvolkApiServiceProvider::class,
   
   // Aliases array:
   'Pettenvolk' => mainstreamct\PettenvolkApi\Pettenvolk::class,
```
...and execute the following command:<br/>
```php artisan vendor:publish```

## Configuration
1. Get your API key at https://www.pettenvolk.com/developer
2. Create `PETTENVOLK_API_KEY` in your .env file and store the API key in it

## Usage
### Authentication
Before you can authenticate users using the Pettenvolk API, you must add two lines to your create_users_table migration (or add the corresponding columns to your Users table):
```php
  $table->string('pettenvolk_uid')->nullable();
  $table->string('pettenvolk_api_token')->nullable();
```
Note that this API token is reset at login and is required to perform any other interaction with the API, so store it carefully.

Authenticating users with Pettenvolk Passport is a breeze. For authentication using only Pettenvolk Passport, you could do something like this:<br/>
```php
// Start function
public function authenticateWithPettenvolk($request Request) {
   // Search API for user
   $pettenvolk = new Pettenvolk;
   $auth = $pettenvolk->auth($request->email, $request->password);
  
   // Search own DB for user
   $user = User::where('pettenvolk_uid', $auth->id)->first();
  
   // If no user is found, create user
   if(!$user) {
      $create = new User;
      $create->email = $auth->email;
      $create->password = $auth->password;
      $create->pettenvolk_uid = $auth->id;
      $create->pettenvolk_api_token =$auth->api_token;
      // Your user code ...
      $create->save();
   }

   // Update the user's API token
   $user->update(['pettenvolk_api_token' => $auth->api_token]);

   // Authenticate the user
   Auth::loginUsingId($user->id);

   // Perform your redirects and/or other authentication functions
}
```

### Fetching users
Getting a user's details from the API is easy:
```php
  Pettenvolk::user('user_id_goes_here');
```
This returns a user's ```name```, ```avatar```, ```color```, ```bg``` (background image), ```type```, ```verified``` (boolean), ```email```, and ```username```. Please note that in order to perform this request, a user needs to be logged in and have an API token. This token can optionally be passed by including an ```api_token``` parameter (it's taken from the Auth facade by default).

## Bugs
Found a bug? Message us at bugs@pettenvolk.com or fill out the bug report form in your Pettenvolk Tester Environment.

## Some final notes
This API is constantly monitored and implementations are often checked on compliance to the API terms. We store every request made to this API, together with some additional details like returned status codes, in order to ensure the best possible experience when using our API service.

Want to know how to fully comply to the API terms? Read https://www.pettenvolk.com/legal/api for more information.

Copyright © 2018 MainstreamCT<br/>
This package is published under the GNU AGPLv3 license. Additional API terms apply.
