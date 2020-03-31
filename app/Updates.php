<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Updates extends Model
{

    protected $guarded = [];

    protected $casts = ['changes' => 'array'];
    

    /**
	* Gets the owning user
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
