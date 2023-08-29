@extends('layouts.app')

@section('content')
<div class="px-52 grid grid-cols-3 lg:grid-cols-6 gap-4">
	<!--create a grid of 5 columns-->

	@foreach($users as $user)
		<livewire:profile-card :user_id="$user->id" :key="$user->id" />
	@endforeach
	</div>
@endsection