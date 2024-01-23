<div>
	<!-- Desktop Nav Bar -->
	<nav class="relative px-4 pt-1 flex justify-between items-center bg-none">
		<a href="/" class="-m-1.5 p-1.5">
			<span class="sr-only">Igneas</span>
			<img class="h-8 w-auto border border-0 rounded-full" src="storage/images/logo.png" alt="">
		</a>
		@auth
		<div class="lg:hidden">
			<button class="navbar-burger flex items-center text-white p-3">
				<svg class="block h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<title>Mobile menu</title>
					<path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
				</svg>
			</button>
		</div>
		<ul class="hidden absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 lg:flex lg:mx-auto lg:flex lg:items-center lg:w-auto lg:space-x-6">
			<li><a class="text-sm text-gray-400 hover:text-gray-500" href="/home">The Grid</a></li>

			<li><a class="text-sm text-blue-600 font-bold" href="/profile">Profile</a></li>

			<li><a class="text-sm text-gray-400 hover:text-gray-500" href="/messenger">Messenger</a></li>
		</ul>
		<a class="hidden lg:inline-block lg:ml-auto lg:mr-3 py-2 px-6 bg-gray-50 hover:bg-gray-100 text-sm text-gray-900 font-bold  rounded-xl transition duration-200" href="#">Log Out</a>
		@endauth
		@guest
		<div class="lg:flex lg:flex-1 lg:justify-end">
			<a href="/login" class="text-sm leading-6 ">Log in <span aria-hidden="true">&rarr;</span></a>
		</div>
		@endguest
	</nav>

	<!-- Mobile Nav Bar -->
	<div id="mobile-nav" class="navbar-menu relative z-50 hidden">
		<!--<div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>-->
		@auth
		<nav class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-6 px-6 bg-neutral-800 border-r overflow-y-auto">
			<div class="flex items-center mb-8">
				<a class="mr-auto text-3xl font-bold leading-none" href="#">
					<img src="storage/images/logo.png" class="h-10 w-auto border border-0 rounded-full" alt="">
				</a>
				<button class="navbar-close">
					<svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
				</button>
			</div>
			<div>
				<ul>
					<li class="mb-1">
						<a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="/home">The Grid</a>
					</li>
					<li class="mb-1">
						<a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="/profile">Profile</a>
					</li>
					<li class="mb-1">
						<a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="/messenger">Messenger</a>
					</li>
				</ul>
			</div>
			<div class="mt-auto">
				<div class="pt-6">
					<a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded" href="/logout">Log Out</a>
				</div>
				<p class="my-4 text-xs text-center text-gray-400">
					<span>Copyright ©2023 Igneas</span>
				</p>
			</div>
		</nav>
		@endauth
	</div>
	<div id="mobile-nav-messenger" hidden>
    @include('components.navbar-messenger')
  </div>
</div>