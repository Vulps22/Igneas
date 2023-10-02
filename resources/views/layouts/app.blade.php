<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

	<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/ac7deee7ba.js" crossorigin="anonymous"></script>
	@vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js', 'resources/js/geolocation.js'])
	@livewireScripts
	@stack('scripts')



	<script type="module" src="/js/global.js"></script>

	@auth
	<script>
		window.user = {!! json_encode(Auth::user()->id); !!};
	</script>
	@endauth

	<!-- Styles -->
	@livewireStyles
</head>

<body class="bg-neutral-900 text-white h-screen">
	<div id="app" class="h-full">
		@include('components.navbar')

		<main class="pt-4 overflow-y-auto scrollbar hooky-scrollbar" style="height: 94.5vh;">
			@yield('content')
		</main>
	</div>
</body>

</html>