@extends('layouts.app')

@section('content')
<div class="md:px-52 grid grid-cols-3 lg:grid-cols-6 md:gap-4">
	<!--create a grid of 5 columns-->
	@if(count($users) === 0)
		<div class="col-span-3 lg:col-span-6">
			<h1 class="text-2xl text-center">No users found</h1>
		</div>
	@endif

	@foreach($users as $user)
		@if($user !== auth()->user()->id)
			<livewire:profile-card :user_id="$user" />
		@endif
	@endforeach
	</div>
@endsection