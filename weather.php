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
$response = file_get_contents($url);
$g = json_decode($response);

// Grab the latitude & longitude from the geocode response
// In VB, this would be
//		g.results(0).geometry.location.lat
$lat = $g->results[0]->geometry->location->lat;
$lng = $g->results[0]->geometry->location->lng;

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

// Choose Meteocon (icon font) character to use
switch($f->currently->icon) {
	case 'clear-day':
		$icon = 1;
		break;
	case 'clear-night':
		$icon = 2;
		break;
	case 'rain':
		$icon = 8;
		break;
	case 'snow':
		$icon = '#';
		break;
	case 'sleet':
		$icon = '$';
		break;
	case 'wind':
		$icon = 9;
		break;
	case 'fog':
		$icon = 'M';
		break;
	case 'cloudy':
		$icon = 5;
		break;
	case 'partly-cloudy-day':
		$icon = 3;
		break;
	case 'partly-cloudy-night':
		$icon = 4;
		break;
}

// Get a nicely-formatted city & state, if available
$city = null;
$state = null;

// Examine the address components of the geocoding response
foreach($g->results[0]->address_components as $component) {
	if(in_array('locality',$component->types)) { // if 'locality', this must be the city
		$city = $component->long_name;
	} elseif(in_array('administrative_area_level_1',$component->types)) { // if 'administrative_area_level_1', this must be the state
		$state = $component->short_name;
	}
}

// If the variables $city and $state both have values now, use those for the location
if($city != null && $state != null) {
	$location = "$city, $state";
} else { // otherwise, just use the formatted_address from the geocoding response
	$location = $g->results[0]->formatted_address;
}

// Get today's current temp, high, and low
$temp = round($f->currently->temperature).'&deg;';
$high = round($f->daily->data[0]->temperatureMax).'&deg;';
$low = round($f->daily->data[0]->temperatureMin).'&deg;';

// Get summary of current condition
$summary = $f->currently->summary;
?>