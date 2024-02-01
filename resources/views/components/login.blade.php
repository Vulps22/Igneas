<div class="flex justify-center h-full">

	<div class="w-full max-w-md">

		<div class="rounded-lg max-md:border-0 md:border-2 border-neutral-800">

			<div class="flex justify-between px-6 py-4 md:border-b border-neutral-800">
				<h2 class="text-lg font-medium">
					Login
				</h2>
				<a class="md:hidden text-sm text-blue-600 hover:underline" onclick="showRegister()"> Not Registered? </a>
			</div>

			<div class="p-6">

				<form method="POST" action="{{ route('login') }}">

					@csrf

					<div class="space-y-4">

						<div>
							<label class="block ">Email Address</label>
							<input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white pl-5 py-2 @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>

							@error('email')
							<p class="mt-1 text-red-500">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label class="block ">Password</label>
							<input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-neutral-800 text-white pl-5 py-2 @error('password') border-red-500 @enderror" required>

							@error('password')
							<p class="mt-1 text-red-500">{{ $message }}</p>
							@enderror
						</div>

						<div class="flex items-center">
							<input type="checkbox" name="remember" id="remember" class="mr-2">
							<label for="remember">Remember Me</label>
						</div>

					</div>

					<div class="mt-4">
						<button class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Login</button>

						<a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
							Forgot password?
						</a>
					</div>

				</form>

			</div>

		</div>

	</div>

</div>