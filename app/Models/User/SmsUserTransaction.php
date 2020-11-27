<?php

namespace App\Models\User;

use App\Constants\TransactionTypes;
use App\Models\User;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SmsUserTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'quantity',
        'transaction_type_id'
    ];

    public function scopeSmsAvailable(Builder $query, User $user) {
        $balance = $query->select(DB::raw('sum(case transaction_type_id when \''.TransactionTypes::IN.'\' then quantity else quantity * (-1) end) as total'))
            ->where('user_id', $user->id)
            ->first()->total;

        return max($balance, 0);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function smsTransactionType() {
        return $this->belongsTo(SmsUserTransaction::class);
    }
}
