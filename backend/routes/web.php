<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return 'Please use /api and the required request.';
});

$router->get('/api', function () use ($router) {
    return 'Please use /api and the required request.';
});


$router->group(['prefix' => 'api'], function () use ($router) {

    /* Everything */
    $router->get('everything', ['uses' => 'EverythingController@showAll']);



    /* All Clients*/
    $router->get('clients', ['uses' => 'ClientController@showAllClients']);

    /* One Client*/
    $router->get('clients/{id}', ['uses' => 'ClientController@showOneClient']);

    /* Create/Add Client*/
    $router->post('clients', ['uses' => 'ClientController@createClient']);

    /* Delete Client */
    /* $router->delete('clients/{id}', ['uses' => 'ClientController@delete']); */

    /* Update Client */
    /* $router->put('clients/{id}', ['uses' => 'ClientController@update']); */



    /* All Buildings*/
    $router->get('buildings', ['uses' => 'BuildingController@showAllBuildings']);

    /* One Building*/
    $router->get('buildings/{id}', ['uses' => 'BuildingController@showOneBuilding']);

    /* Create/Add Building*/
    $router->post('buildings', ['uses' => 'BuildingController@createBuilding']);



    /* All Parkings*/
    $router->get('parkings', ['uses' => 'ParkingController@showAllParkings']);

    /* One Parking*/
    $router->get('parkings/{id}', ['uses' => 'ParkingController@showOneParking']);

    /* Create/Add Parking*/
    $router->post('parkings', ['uses' => 'ParkingController@createParking']);

});
