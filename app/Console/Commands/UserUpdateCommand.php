<?php

namespace App\Console\Commands;
Use App\User;
use Auth;
use Faker\Factory as Faker;

use Illuminate\Console\Command;

class UserUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user {email : Email of the user we intend editing.} {password : Password to this account.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Updates the details of the user who's email was entered as a command flag";

    /**
     * The user to be updated.
     *
     * @var object
     */
    protected $user;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $credentials = [
            'email' => $this->argument('email'),
            'password' => $this->argument('password')
        ];
        if(!Auth::attempt($credentials)){
            return $this->error('Invalid credentials!');
        }
        
        $faker = Faker::create();
        $details = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'timezone' => Auth::user()->assignTimezone()->timezone
        ];
        Auth::user()->update($details);
       
        $this->info('Success');
    }

}
