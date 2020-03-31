<?php

namespace App;
use Illuminate\Support\Arr;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'timezone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $timezones = ['CET', 'CST', 'GMT+1'];

    public function assignTimezone()
    {
        $index = rand(0,2);
        $this->timezone = $this->timezones[$index];
        return $this;
    }

    /**
	* Records the update of a user
	* 
	* @param string $description
    */
    public function recordUpdate() {
        $this->updates()->updateOrCreate(
            ['user_id' => $this->id],
            ['changes' => $this->getUpdateChanges()]
        );
    }

    /**
	* Fetch the updateChanges on the user model
	* 
	* @return array|null
    */
    public function getUpdateChanges() {
        if($this->wasChanged()) { 
            $userChanges = Arr::except($this->getChanges(), ['updated_at']);
            $updates = Updates::where('user_id', $this->id)->first();
            $updateTableChanges = array_merge($updates['changes'] ?? [], $userChanges);
    		return $updateTableChanges;
    	}
    }

    /**
	* Gets the updates of a user
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
	public function updates(){
		return $this->hasOne(Updates::class);
	}
}
