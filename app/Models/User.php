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
            Log::info('Postbacks disabled');
            return false;
        } else {
            if ((int) $postbackFeature->limit === 0) {
                Log::info('Postbacks ilimited');
                return true;
            } else {
                $postbacksReceiveds = $this->postbacks()->where('created_at', '>=', $this->activated_at)->count() ?? 0;
                Log::info('Postbacks receiveds: '.$postbacksReceiveds);
                Log::info('Postbacks limit: '.$postbackFeature->limit);
                Log::info('Postbacks available: '. max($postbackFeature->limit - $postbacksReceiveds, 0));
                return max($postbackFeature->limit - $postbacksReceiveds, 0);
            }
        }
    }

    public function leadsAvailable() {
        $leadFeature = $this->plan->features->find(FeatureTypes::LEADS)->pivot;
        if (!$leadFeature->enabled) {
            Log::info('Leads disabled');
            return false;
        } else {
            if ((int) $leadFeature->limit === 0) {
                Log::info('Leads ilimited');
                return true;
            } else {
                $LeadsReceiveds = $this->leads()->where('created_at', '>=', $this->activated_at)->count() ?? 0;
                Log::info('Leads receiveds: '.$LeadsReceiveds);
                Log::info('Leads limit: '.$leadFeature->limit);
                Log::info('Leads available: '. max($leadFeature->limit - $LeadsReceiveds, 0));
                return max($leadFeature->limit - $LeadsReceiveds, 0);
            }
        }
    }

    public function smsAvailable() {
        $smsFeature = $this->plan->features->find(FeatureTypes::SMS)->pivot;
        if (!$smsFeature->enabled) {
            Log::info('SMS disabled');
            return false;
        } else {
            if ((int) $smsFeature->limit === 0) {
                Log::info('SMS ilimited');
                return true;
            } else {
                $smsSent = $this->actions()
                                ->where('excecuted_at', '>=', $this->activated_at)
                                ->where('action_type_id', ActionTypes::SMS)
                                ->count() ?? 0;
                Log::info('SMS sent: '.$smsSent);
                Log::info('SMS limit: '.$smsFeature->limit);
                Log::info('SMS available: '. max($smsFeature->limit - $smsSent, 0));
                return max($smsFeature->limit - $smsSent, 0);
            }
        }
    }

    public function emailAvailable() {
        $emailFeature = $this->plan->features->find(FeatureTypes::EMAILS)->pivot;
        if (!$emailFeature->enabled) {
            Log::info('E-mail disabled');
            return false;
        } else {
            if ((int) $emailFeature->limit === 0) {
                Log::info('E-mail ilimiteds');
                return true;
            } else {
                $emailSent = $this->actions()
                                ->where('excecuted_at', '>=', $this->activated_at)
                                ->where('action_type_id', ActionTypes::EMAIL)
                                ->count() ?? 0;
                Log::info('E-mail sent: '.$emailSent);
                Log::info('E-mail limit: '.$emailFeature->limit);
                Log::info('E-mail available: '. max($emailFeature->limit - $emailSent, 0));
                return max($emailFeature->limit - $emailSent, 0);
            }
        }
    }

    public function whatsappAvailable() {
        $whatsappFeature = $this->plan->features->find(FeatureTypes::WHATSAPP)->pivot;
        if (!$whatsappFeature->enabled) {
            Log::info('Whatsapp disabled');
            return false;
        } else {
            if ((int) $whatsappFeature->limit === 0) {
                Log::info('Whatsapp ilimited');
                return true;
            } else {
                $whatsappSent = $this->actions()
                                ->where('excecuted_at', '>=', $this->activated_at)
                                ->where('action_type_id', ActionTypes::WHATSAPP)
                                ->count() ?? 0;
                Log::info('Whatsapp sent: '.$whatsappSent);
                Log::info('Whatsapp limit: '.$whatsappFeature->limit);
                Log::info('Whatsapp available: '. max($whatsappFeature->limit - $whatsappSent, 0));
                return max($whatsappFeature->limit - $whatsappSent, 0);
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
}
