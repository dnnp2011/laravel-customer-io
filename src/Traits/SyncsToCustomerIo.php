<?php

namespace Tether\LaravelCustomerIo\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Tether\LaravelCustomerIo\Jobs\RemoveModelFromCustomerIo;
use Tether\LaravelCustomerIo\Jobs\SyncModelToCustomerIo;

trait SyncsToCustomerIo
{
    protected static function bootSyncsToCustomerIo()
    {
        static::created(function ($model) {
            if ($model->email !== null) {
                $model->syncToCustomerIo();
            }
        });

        static::updated(function ($model) {
            if ($model->email !== null) {
                $model->syncToCustomerIo();
            }
        });

        static::deleting(function ($model) {
            if ($model->email !== null) {
                $model->removeFromCustomerIo();
            }
        });
    }

    /**
     * @return string
     */
    public function getCustomerIoIdAttribute(): string
    {
        $model = Str::of(get_class($this))
            ->afterLast('\\')
            ->snake();

        return "{$model}_{$this->id}";
    }

    /**
     * Set the data that's synced with customer io with a key value array.
     *
     * @return array
     */
    public function toCustomerIoArray(): array
    {
        return array_replace($this->attributesToArray(), [
            'created_at' => optional($this->created_at)->timestamp,
            'updated_at' => optional($this->updated_at)->timestamp,
        ]);
    }

    /**
     * Map the attributes which should be synced to customer io into a key value array.
     *
     * @return array
     */
    public function getCustomerIoData(): array
    {
        return array_merge($this->toCustomerIoArray(), [
            'id'              => $this->customer_io_id,
            'model_class'     => $this->getMorphClass(),
            'model_id'        => $this->id,
            'app_environment' => App::environment(),
            'locale'          => App::getLocale(),
            'timezone'        => config('app.timezone'),
            '_timestamp'      => now()->timestamp,
        ]);
    }

    public function syncToCustomerIo()
    {
        return SyncModelToCustomerIo::dispatch($this->fresh());
    }

    public function syncToCustomerIoNow()
    {
        return SyncModelToCustomerIo::dispatchNow($this->fresh());
    }

    public function removeFromCustomerIo()
    {
        return RemoveModelFromCustomerIo::dispatch($this->fresh());
    }

    public function removeFromCustomerIoNow()
    {
        return RemoveModelFromCustomerIo::dispatchNow($this->fresh());
    }
}
