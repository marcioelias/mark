<?php

namespace App\Models;

use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class TempPassword extends Model
{

    public function __construct()
    {
        parent::__construct();

        $this->temp_password = Str::random(8);
    }

    protected $fillable = [
        'user_id', 'temp_password'
    ];

    /* protected $hidden = [
        'temp_password'
    ]; */

    public function user() {
        return $this->belongsTo(User::class);
    }
}
