<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta name="description" content="A simple weather application powered by the Google Geocoding API and the Forecast.io API.">
	
	<link rel="icon" type="image/png" href="assets/images/favicon.png">
	<link rel="stylesheet" type="text/css" href="assets/styles.css">

	<title>How's the Weather?</title>
</head>
<body>
	<div class="container">
		<header>
			<h1>How's the Weather?</h1>
		</header>
		<div id="search-form">
			<form method="get" action="index.php">
				<input type="tel" name="zip" autocomplete="off" placeholder="zip">
				<button type="submit">Go</button>
			</form>
		</div>
		<div class="result">
			<?php
			if(!empty($_GET['zip'])) {
				// Check to see if the client request includes a non-empty 'zip'.
				// If so, they must have searched for a zip code. Proceed with fetching weather
				include('weather.php');
			} else {
				// Client must be visiting the page for the first time, and has not
				// searched for a zip code.
				$location = '&nbsp;';
				$temp = '&nbsp;';
				$summary = '&nbsp;';
				$high = '&nbsp;';
				$low = '&nbsp;';
			}
			?>
			<div class="weather">
				<div class="location">
					<?php echo $location ?>
				</div>
				<div class="conditions">
					<div class="temp">
						<?php echo $temp ?>
					</div>
					<div class="summary">
						<?php echo $summary ?>
					</div>
					<div class="highlow"><?php echo $high ?> / <?php echo $low ?></div>
				</div>
			</div>
		</div>
	</div>
	<footer>
		<p>Powered by <img src="assets/images/google-maps-logo.png" alt="Google Maps"> + <img src="assets/images/dark-sky-logo.png" alt="Forecast.io"></p>
	</footer>
</body>
</html>