<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/redirect', function () {
	$query = http_build_query([
		'client_id' => 3,
		'redirect_uri' => 'http://localhost:8000/callback',
		'response_type' => 'code',
		'scope' => '',
	]);

	return redirect('http://localhost:8000/oauth/authorize?'.$query);
})->name('get.token');

Route::get('/callback',function (Request $request){
	$guzzle = new GuzzleHttp\Client;

	$response = $guzzle->post('http://localhost:8000/oauth/token', [
		'form_params' => [
			'grant_type' => 'client_credentials',
			'client_id' => 'client-id',
			'client_secret' => 'client-secret',
			'scope' => 'your-scope',
			'code' =>$request->code,
		],
	]);

	return json_decode((string) $response->getBody(), true)['access_token'];
});
