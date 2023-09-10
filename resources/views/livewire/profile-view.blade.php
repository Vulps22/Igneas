@extends('layouts.app')

@push('scripts')
<script type="text/javascript" src="/js/imageSelector.js"></script>
@endpush

@section('content')
<div class="flex flex-col items-center w-full h-2/3 px-72">
	<div class="h-full flex pb-5">
		<img id="selected-image" src="{{ $selectedImage }}" alt="{{ $profile->display_name }}" class="h-full object-contain rounded-lg">
	</div>
	@if(count($photos) > 1)
	<div class="w-1/3 h-1/3 flex flex-row justify-start">
		@foreach ($photos as $photo)
		<div class="w-full h-full pr-2">
			<img src="{{ $photo }}" alt="{{ $profile->display_name }}" class="h-full object-cover rounded-lg cursor-pointer" onclick="selectImage('{{ $photo }}', event)">
		</div>
		@endforeach
	</div>
	@endif

	<div class="rounded-lg border-2 border-neutral-800 mt-10 p-10 w-2/3">
		<div class="mt-2 p-2 mb-5 border-b-2 border-neutral-800">
			<h1 class="text-3xl font-bold">
				{{ $profile->display_name }}
				@if($profile->show_age)
				<span class="font-normal pl-3">{{ $profile->age() }} </span>
				@endif
			</h1>
			<h3 class="text-2xl text-italic capitalize">
				{{ $profile->sexuality }}
			</h3>
			<h3 class="text-2xl text-italic capitalize">
				{{ $profile->pronouns }}
			</h3>
		</div>

		<h3 class="mt-2 p-2 w-1/2 mx-auto text-2xl text-neutral-300">About Me</h3>
		<div class="mt-2 p-2 w-1/2 mx-auto border-2 rounded-lg border-neutral-800 bg-neutral-800">
			{{ $profile->bio}}
		</div>


		<div class="grid grid-rows gap-4 w-1/2 mx-auto pt-5 text-xl capitalize">
			<livewire:profile-item :icon="'transgender'" :value="$profile->gender" />
			<livewire:profile-item :icon="'ruler'" :value="$this->getBodyString()" :wide="true" />
			<livewire:profile-item :icon="'circle-half-stroke'" :value="$profile->ethnicity" />
			<livewire:profile-item :icon="'heart'" :value="$profile->relationship_status" />
			<livewire:profile-item :icon="'binoculars'" :value="$profile->looking_for" />
			<livewire:profile-item :icon="'arrows-up-down'" :value="$profile->position" />
			<livewire:profile-item :icon="'handcuffs'" :value="$profile->dominance" />
		</div>
	</div>
	@if($health->show_hiv_status && $health->hiv_status)
	<div class="rounded-lg border-2 border-neutral-800 mt-5 p-10 mb-5 w-2/3">
		<h3 class="text-2xl text-neutral-300">Health</h3>
		<div class="grid grid-rows gap-4 w-1/2 mx-auto pt-5 text-xl capitalize">
			<livewire:profile-item :icon="'ribbon'" :title="'HIV Status'" :value="$this->getHIVString()" />
			<livewire:profile-item :icon="'calendar'" :title="'Last Tested'" :value="$this->getLastTestString()" />
		</div>
	</div>
	@endif
</div>
@endsection