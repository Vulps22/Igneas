<?php

use Illuminate\Support\Facades\Route;

$route = Route::currentRouteName();
?>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>
	<link rel="icon" type="image/png" href="/storage/images/logonbg.png">
	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

	<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/ac7deee7ba.js" crossorigin="anonymous"></script>
</head>

<body>

	<div id="app">
		<main>
			@yield('content')
		</main>
	</div>
</body>

<footer>
</footer>


</html>