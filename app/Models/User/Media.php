<?php

namespace App\Models\User;



use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{

    use Uuid;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (auth()->guard('web')->check()) {
                $model->user_id = auth()->guard('web')->id();
            }
        });

        static::addGlobalScope('user_id', function (Builder $builder) {
            if (auth()->guard('web')->check()) {
                $builder->where((new self)->getTable().'.user_id', auth()->guard('web')->id());
            }
        });
    }
}
