<?php

namespace App\Models;

use App\Models\User\MailTemplate;
use App\Models\User\PlataformConfig;
use App\Models\User\Product;
use App\Models\User\Tag;
use App\Models\User\LeadStatus;
use App\Notifications\CustomVerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone_number', 'customer_code', 'plan_id', 'password', 'first_login_at', 'active'
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
        'first_login_at' => 'datetime',
        'active' => 'boolean'
    ];

    protected static function boot():void {
        parent::boot();

        static::creating(function (self $user) {
            $user->plan_cycle_ends = Carbon::now()->addDays(30);
        });
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification($this));
    }

    public function scopeActive($query) {
        return $query->where('active', true);
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    public function temp_password() {
        return $this->hasOne(TempPassword::class);
    }

    public function tags() {
        return $this->hasMany(Tag::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function plataformConfigs() {
        return $this->hasMany(PlataformConfig::class);
    }

    public function plataform() {
        return $this->hasOneThrough(Plataform::class, PlataformConfig::class);
    }

    public function mailTemplates() {
        return $this->hasMany(MailTemplate::class);
    }

    public function leadStatuses() {
        return $this->hasMany(LeadStatus::class);
    }
}
