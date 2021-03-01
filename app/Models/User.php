<?php

namespace App\Models;

use App\Constants\ActionTypes;
use App\Constants\FeatureTypes;
use App\Models\User\Action;
use App\Models\User\Lead;
use App\Models\User\MailTemplate;
use App\Models\User\PlataformConfig;
use App\Models\User\Product;
use App\Models\User\Tag;
use App\Models\User\LeadStatus;
use App\Models\User\Postback;
use App\Models\User\SmsUserTransaction;
use App\Models\User\WhatsappInstance;
use App\Notifications\CustomVerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'doc_number',
        'zip_code',
        'street_name',
        'street_number',
        'neighborhood',
        'city',
        'state',
        'email',
        'phone_area',
        'phone_number',
        'customer_code',
        'plan_id',
        'password',
        'first_login_at',
        'active',
        'activated_at'
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
        'activated_at' => 'datetime',
        'active' => 'boolean'
    ];

    protected static function boot():void {
        parent::boot();

        /* static::creating(function (self $user) {
            $user->plan_cycle_ends = Carbon::now()->addDays(30);
        }); */
    }

    public function profileComplete() {
        return $this->name &&
                $this->email &&
                $this->doc_number &&
                $this->street_name &&
                $this->city;
    }

    public function postbacksAvailable() {
        $postbackFeature = $this->plan->features->find(FeatureTypes::POSTBACKS)->pivot;
        if (!$postbackFeature->enabled) {
            return false;
        } else {
            if ((int) $postbackFeature->limit === 0) {
                return true;
            } else {
                $postbacksReceiveds = $this->postbacks()->where('created_at', '>=', $this->activated_at)->count() ?? 0;
                return max($postbackFeature->limit - $postbacksReceiveds, 0);
            }
        }
    }

    public function leadsAvailable() {
        $leadFeature = $this->plan->features->find(FeatureTypes::LEADS)->pivot;
        if (!$leadFeature->enabled) {
            return false;
        } else {
            if ((int) $leadFeature->limit === 0) {
                return true;
            } else {
                $LeadsReceiveds = $this->leads()->where('created_at', '>=', $this->activated_at)->count() ?? 0;
                return max($leadFeature->limit - $LeadsReceiveds, 0);
            }
        }
    }

    public function smsAvailable() {
        $smsFeature = $this->plan->features->find(FeatureTypes::SMS)->pivot;
        if (!$smsFeature->enabled) {
            return false;
        } else {
            if ((int) $smsFeature->limit === 0) {
                return true;
            } else {
                return SmsUserTransaction::smsAvailable($this->id);
            }
        }
    }

    public function emailAvailable() {
        $emailFeature = $this->plan->features->find(FeatureTypes::EMAILS)->pivot;
        if (!$emailFeature->enabled) {
            return false;
        } else {
            if ((int) $emailFeature->limit === 0) {
                return true;
            } else {
                $emailSent = $this->actions()
                                ->where('excecuted_at', '>=', $this->activated_at)
                                ->where('action_type_id', ActionTypes::EMAIL)
                                ->count() ?? 0;
                return max($emailFeature->limit - $emailSent, 0);
            }
        }
    }

    public function whatsappAvailable() {
        $whatsappFeature = $this->plan->features->find(FeatureTypes::WHATSAPP)->pivot;
        if (!$whatsappFeature->enabled) {
            return false;
        } else {
            if ((int) $whatsappFeature->limit === 0) {
                return true;
            } else {
                // $whatsappSent = $this->actions()
                //                 ->where('excecuted_at', '>=', $this->activated_at)
                //                 ->where('action_type_id', ActionTypes::WHATSAPP)
                //                 ->count() ?? 0;
                // return max($whatsappFeature->limit - $whatsappSent, 0);
                $whatsappInstancesCount = $this->whatsappInstances->count();
                return max($whatsappFeature->limit - $whatsappInstancesCount, 0);
            }
        }
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

    public function postbacks() {
        return $this->hasMany(Postback::class);
    }

    public function leads() {
        return $this->hasMany(Lead::class);
    }

    public function actions() {
        return $this->hasMany(Action::class);
    }

    public function whatsappInstances() {
        return $this->hasMany(WhatsappInstance::class);
    }

    public function smsUserTransactions() {
        return $this->hasMany(SmsUserTransaction::class);
    }
}
