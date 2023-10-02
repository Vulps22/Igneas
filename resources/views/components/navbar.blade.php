<nav class="flex grow items-center justify-between px-6 py-3 lg:px-8" aria-label="Global">
	<!-- Logo -->
	<div class="flex pr-20">
		<a href="/" class="-m-1.5 p-1.5">
			<span class="sr-only">Hooky</span>
			<img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
		</a>
	</div>

	<!-- Desktop nav -->
	<div class="lg:flex grow">
		@auth
		<a href="/home" class="text-sm leading-6 pl-5">Home</a>
		<a href="/profile" class="text-sm leading-6 pl-5">Profile</a>
		<a href="/messenger" class="text-sm leading-6 pl-5">Mesenger</a>
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