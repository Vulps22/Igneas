<!-- display the login-form and login-register components side-by-side-->
@extends('layouts.app')

@section('content')
<div class="mx-auto text-center pb-10">
	@if(session('error'))
	<div class="bg-red-500 text-white p-3">
		{{ session('error') }}
	</div>
	@endif

	@if ($errors->any())
	<div class="bg-red-500 text-white p-3">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
</div>

<div class="flex flex-col md:flex-row">
	<div class="grow pl-52 h-full">
		<x-login />
	</div>
	<div class="grow pr-52 h-full">
		<x-register />
	</div>
</div>
@endsection