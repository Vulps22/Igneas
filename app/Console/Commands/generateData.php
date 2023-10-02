<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserHealth;
use App\Models\UserImage;
use App\Models\UserProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use MatanYadaev\EloquentSpatial\Objects\Point;

class generateData extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:generate-data';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$lastUserId = User::orderBy('id', 'desc')->first()->id ?? 0;

		for ($i = 0; $i < 100; $i++) {
			// Generate random user data
			$email = "user" . ($lastUserId + $i + 1) . "@example.com";
			$dob = date('Y-m-d', rand(strtotime('1950-01-01'), strtotime('2005-01-01')));
			$password = 'password';

			$this->info($email);
			// Create the user
			$user = User::create([
				'email' => $email,
				'date_of_birth' => $dob,
				'password' => Hash::make($password),
				'terms_accepted' => now(),
			]);

			// Create the user profile
			$profile = UserProfile::create([
				'user_id' => $user->id,
				'display_name' => "User {$i}",
				'sexuality' => ['Straight', 'Gay', 'Bisexual', 'Pansexual'][rand(0, 3)],
				'bio' => "Ipsem Lorem Ipsum",
				'height' => rand(150, 200),
				'weight' => rand(50, 100),
				'body_type' => ['Slim', 'Athletic', 'Average', 'Curvy', 'Full Figured'][rand(0, 4)],
				'position' => ['Top', 'Bottom', 'Versatile'][rand(0, 2)],
				'dominance' => ['Dominant', 'Submissive', 'Switch'][rand(0, 2)],
				'ethnicity' => ['Asian', 'Black', 'Hispanic', 'Middle Eastern', 'White'][rand(0, 4)],
				'relationship_status' => ['Single', 'In a relationship', 'Married', 'Divorced'][rand(0, 3)],
				'looking_for' => ['Casual', 'Dating', 'Relationship', 'Friendship'][rand(0, 3)],
				'gender' => ['Male', 'Female', 'Non-binary'][rand(0, 2)],
				'pronouns' => ['He/Him', 'She/Her', 'They/Them'][rand(0, 2)],
				'show_location' => rand(0, 1),
				'show_age' => rand(0, 1),
			]);

			// Create the user location
			$latitude = rand(-90, 90) + (rand(0, 999999) / 1000000);
			$longitude = rand(-180, 180) + (rand(0, 999999) / 1000000);

			$user->location = new Point($latitude, $longitude);
			$user->save();

			// Create the user health data
			$health = UserHealth::create([
				'user_id' => $user->id,
				'hiv_status' => ['Positive', 'Negative', 'Unknown'][rand(0, 2)],
				'last_sti_test' => date('Y-m-d', rand(strtotime('2010-01-01'), strtotime('2021-01-01'))),
				'on_prep' => rand(0, 1),
				'show_hiv_status' => rand(0, 1),
			]);

			// create user images
			for ($j = 0; $j < 6; $j++) {
				UserImage::create([
					'user_id' => $user->id,
					'position' => $j,
				]);
			}
		}


		$this->info('Dummy data generated successfully!');
	}
}
