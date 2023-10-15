@extends('layouts.app')

@section('content')

<form action="{{ route('profile.save') }}" method="POST">

	@if (session('success'))
	<div class="alert alert-success mx-10 px-10" id="success-alert">
		{{ session('success') }}
	</div>

	@elseif (session('error'))
	<div class="alert alert-danger mx-10 px-10">
		{{ session('error') }}
	</div>
	@endif
	<div class="flex justify-center">

		<div class="w-1/2">
			
				@csrf

				<div class="rounded-lg border-2 border-neutral-800 p-10 mb-5">
					<h2 class="text-lg font-bold mb-5">Profile Images</h2>

					<!-- Profile image uploader -->
					<div class="grid grid-cols-3 gap-4 mt-5">
						@for ($i = 0; $i < 6; $i++) <livewire:photo-card :position="$i" />
						@endfor
					</div>
				</div>

				<div class="rounded-lg border-2 border-neutral-800 p-10">
					<!-- UserProfile fields -->

					<input type="hidden" name="user" value="{{ Auth::user()->id }}">

					<div class="grid grid-cols-6 gap-1 pt-2">
						<label for="display_name">Display Name:</label>
						<input class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" type="text" name="display_name" value="{{$profile->display_name}}">
					</div>

					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="bio">Bio:</label>
						<textarea class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white col-span-5 px-2 py-2" name="bio" value="{{$profile->bio}}"></textarea>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label for="height" class="mr-2">Height:</label>
						<div class="col-span-5 flex">
							<input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white col-span-5 px-2 py-2 md:w-1/6" type="number" name="height" value="{{$profile->height}}">
							<sub class="ml-2 text-xl">cm</sub>
						</div>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="weight">Weight:</label>
						<div class="col-span-5 flex">
							<input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white col-span-5 px-2 py-2 md:w-1/6" type="number" name="weight" value="{{ $profile->weight }}">
							<sub class="ml-2 text-xl">kg</sub>
						</div>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="body_type">Body Type:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="body_type">
							<option value="">Prefer Not To Say</option>
							<option value="thin" {{ $profile->body_type == 'thin' ? 'selected' : '' }}>Thin</option>
							<option value="muscular" {{ $profile->body_type == 'muscular' ? 'selected' : '' }}>Muscular</option>
							<option value="average" {{ $profile->body_type == 'average' ? 'selected' : '' }}>Average</option>
							<option value="chubby" {{ $profile->body_type == 'chubby' ? 'selected' : '' }}>Chubby</option>
							<option value="fat" {{ $profile->body_type == 'fat' ? 'selected' : '' }}>Fat</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label for="sexuality">Sexuality:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="sexuality">
							<option value="">Prefer Not To Say</option>
							<option value="a-sexual" {{ $profile->sexuality == 'a-sexual' ? 'selected' : '' }}>A-Sexual</option>
							<option value="bi" {{ $profile->sexuality == 'bi' ? 'selected' : '' }}>Bi-Sexual</option>
							<option value="demi" {{ $profile->sexuality == 'demi' ? 'selected' : '' }}>Demi-Sexual</option>
							<option value="gay" {{ $profile->sexuality == 'gay' ? 'selected' : '' }}>Gay / Lesbian</option>
							<option value="straight" {{ $profile->sexuality == 'straight' ? 'selected' : '' }}>Straight</option>
							<option value="pan" {{ $profile->sexuality == 'pan' ? 'selected' : '' }}>Pan-Sexual</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="position">Position:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="position">
							<option value="">Prefer Not To Say</option>
							<option value="top" {{ $profile->position == 'top' ? 'selected' : '' }}>Top</option>
							<option value="bottom" {{ $profile->position == 'bottom' ? 'selected' : '' }}>Bottom</option>
							<option value="verse" {{ $profile->position == 'verse' ? 'selected' : '' }}>Verse</option>
							<option value="verse_top" {{ $profile->position == 'verse_top' ? 'selected' : '' }}>Verse Top</option>
							<option value="verse_bottom" {{ $profile->position == 'verse_bottom' ? 'selected' : '' }}>Verse Bottom</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="dominance">Dominance:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="dominance">
							<option value="">Prefer Not To Say</option>
							<option value="dom" {{ $profile->dominance == 'dom' ? 'selected' : '' }}>Dom</option>
							<option value="sub" {{ $profile->dominance == 'sub' ? 'selected' : '' }}>sub</option>
							<option value="switch" {{ $profile->dominance == 'switch' ? 'selected' : '' }}>Switch</option>
							<option value="switch_dom" {{ $profile->dominance == 'switch_dom' ? 'selected' : '' }}>Switch Dom</option>
							<option value="switch_sub" {{ $profile->dominance == 'switch_sub' ? 'selected' : '' }}>Switch Sub</option>

						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="ethnicity">Ethnicity:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="ethnicity">
							<option value="">Prefer Not To Say</option>
							<option value="white" {{ $profile->ethnicity == 'white' ? 'selected' : '' }}>White</option>
							<option value="black" {{ $profile->ethnicity == 'black' ? 'selected' : '' }}>Black</option>
							<option value="asian" {{ $profile->ethnicity == 'asian' ? 'selected' : '' }}>Asian</option>
							<option value="arabic" {{ $profile->ethnicity == 'arabic' ? 'selected' : '' }}>Arabic</option>
							<option value="hispanic" {{ $profile->ethnicity == 'hispanic' ? 'selected' : '' }}>Hispanic</option>
							<option value="indian" {{ $profile->ethnicity == 'indian' ? 'selected' : '' }}>Indian</option>
							<option value="native_american" {{ $profile->ethnicity == 'native_american' ? 'selected' : '' }}>Native American</option>
							<option value="pacific_islander" {{ $profile->ethnicity == 'pacific_islander' ? 'selected' : '' }}>Pacific Islander</option>
							<option value="mixed" {{ $profile->ethnicity == 'mixed' ? 'selected' : '' }}>Mixed</option>
							<option value="other" {{ $profile->ethnicity == 'other' ? 'selected' : '' }}>Other</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="relationship_status">Relationship Status:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="relationship_status">
							<option value="">Prefer Not To Say</option>
							<option value="single" {{ $profile->relationship_status == 'single' ? 'selected' : '' }}>Single</option>
							<option value="dating" {{ $profile->relationship_status == 'dating' ? 'selected' : '' }}>Dating</option>
							<option value="not_looking" {{ $profile->relationship_status == 'not_looking' ? 'selected' : '' }}>Not Looking</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="looking_for">Looking For:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="looking_for">
							<option value="">Prefer Not To Say</option>
							<option value="friends" {{ $profile->looking_for == 'friends' ? 'selected' : '' }}>Friends</option>
							<option value="relationship" {{ $profile->looking_for == 'relationship' ? 'selected' : '' }}>Relationship</option>
							<option value="right_now" {{ $profile->looking_for == 'right_now' ? 'selected' : '' }}>Right Now</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="gender">Gender:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="gender">
							<option value="">Prefer Not To Say</option>
							<option value="male" {{ $profile->gender == 'male' ? 'selected' : '' }}>Male</option>
							<option value="female" {{ $profile->gender == 'female' ? 'selected' : '' }}>Female</option>
							<option value="trans_male" {{ $profile->gender == 'trans_male' ? 'selected' : '' }}>Trans Male</option>
							<option value="trans_female" {{ $profile->gender == 'trans_female' ? 'selected' : '' }}>Trans Female</option>
							<option value="non-binary" {{ $profile->gender == 'non-binary' ? 'selected' : '' }}>Non-binary</option>
							
							
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="pronouns">Pronouns:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="pronouns">
							<option value="">Prefer Not To Say</option>
							<option value="he/him" {{ $profile->pronouns == 'he/him' ? 'selected' : '' }}>He/Him</option>
							<option value="she/her" {{ $profile->pronouns == 'she/her' ? 'selected' : '' }}>She/Her</option>
							<option value="they/them" {{ $profile->pronouns == 'they/them' ? 'selected' : '' }}>They/Them</option>
							<option value="ask" {{ $profile->pronouns == 'ask' ? 'selected' : '' }}>Ask Me</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="show_location">Show Location:</label>
						<input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" type="checkbox" name="show_location" {{ $profile->show_location ? 'checked' : '' }}>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label class="mr-2" for="show_age">Show Age:</label>
						<input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" type="checkbox" name="show_age" {{ $profile->show_age ? 'checked' : '' }}>
					</div>
				</div>
				<div class="rounded-lg border-2 border-neutral-800 p-10 mt-10">
					<!-- UserHealth fields -->
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label for="hiv_status">HIV Status:</label>
						<select class="col-span-5 mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" name="hiv_status">
							<option value="">Prefer Not To Say</option>
							<option value="positive">Positive</option>
							<option value="untraceable">Positive (Untraceable)</option>
							<option value="negative">Negative</option>
						</select>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label for="last_STI_test">Last STI Test:</label>
						<input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white col-span-5 px-2 py-2" type="date" name="last_STI_test" value="{{ $sexual_health->last_STI_test }}">
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label for="on_prep">On PrEP:</label>
						<input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" type="checkbox" name="on_prep" {{ $sexual_health->on_prep ? 'checked' : '' }}>
					</div>
					<div class="grid grid-cols-6 gap-1 pt-2">
						<label for="show_hiv_status">Show Status:</label>
						<input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white px-2 py-2" type="checkbox" name="show_hiv_status" {{ $sexual_health->show_hiv_status ? 'checked' : '' }}>
					</div>
				</div>

				<button type="submit" class=" bg-primary p-2 px-3 mt-3 rounded">Save</button>

		</div>
	</div>
</form>
@endsection