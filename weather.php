<?php
// Include the PHP file the contains our API keys
require('config/api.config.php');
$geocode_url = 'https://maps.googleapis.com/maps/api/geocode/json?address={zip}&key={key}';
$forecastio_url = 'https://api.forecast.io/forecast/{key}/{lat},{lng}';

// Make geocode request for latitude & longitude
$placeholders = [
	'{zip}',
	'{key}'
];
$values = [
	$_GET['zip'], // zip code from client request's URL parameter named 'zip'
	GOOGLE_APIKEY // API key defined in api.config.php
];
$url = str_replace($placeholders,$values,$geocode_url);
$response = file_get_contents($url); // file_get_contents is used here for simplicity. Usually, we'd use cURL
$g = json_decode($response);

// Grab the latitude & longitude from the geocode response
// In VB, this would be
//		g.results(0).geometry.location.lat
$lat = $g->results[0]->geometry->location->lat;
$lng = $g->results[0]->geometry->location->lng;

// Get the location to display
$location = $g->results[0]->formatted_address;

// Make forecast.io request for weather data
$placeholders = [
	'{key}',
	'{lat}',
	'{lng}'
];
$values = [
	FORECASTIO_APIKEY, // API key defined in api.config.php
	$lat, // latitude from above
	$lng  // longitude from above
];
$url = str_replace($placeholders,$values,$forecastio_url);
$response = file_get_contents($url);
$f = json_decode($response);

// Get today's current temp, high, and low
$temp = round($f->currently->temperature).'&deg;';
$high = round($f->daily->data[0]->temperatureMax).'&deg;';
$low = round($f->daily->data[0]->temperatureMin).'&deg;';

// Get summary of current condition
$summary = $f->currently->summary;
?>