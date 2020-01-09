<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// [LDAP / OAUTH] Activation routes Authentification
Auth::routes([
    'register' => false,
    'email' => false,
    'reset' => false,
    'update' => false
    ]);

// [LDAP / OAUTH] Routes accessible qu'après authentification passage token valide
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/test_auth', 'TestController@index')->name("testAuth");
});
