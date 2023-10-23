<nav class="flex grow items-center justify-between px-6 py-3 lg:px-8" aria-label="Global">
	<!-- Logo -->
	<div class="flex pr-20">
		<a href="/" class="-m-1.5 p-1.5">
			<span class="sr-only">Igneas</span>
			<img class="h-8 w-auto border border-0 rounded-full " src="storage/images/logo.png" alt="">
		</a>
	</div>

	<!-- Desktop nav -->
	<div class="lg:flex grow">
		@auth
		<a href="/home" class="text-sm leading-6 pl-5">Home</a>
		<a href="/profile" class="text-sm leading-6 pl-5">Profile</a>
		<a href="/messenger" class="text-sm leading-6 pl-5">Messenger</a>
		@endauth
	</div>
	<div class="hidden lg:flex lg:flex-1 lg:justify-end">
		@guest
		<a href="/login" class="text-sm leading-6 ">Log in <span aria-hidden="true">&rarr;</span></a>
		@else
		<a href="/logout" class="text-sm leading-6 ">Log Out</a>
		@endguest
	</div>
</nav>