<?php


$router->get('/', ['uses' => 'MessageController@index']);
$router->get('/search', ['uses' => 'MessageController@search']);
