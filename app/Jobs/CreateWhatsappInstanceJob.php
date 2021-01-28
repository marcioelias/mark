<?php

namespace App\Jobs;

use App\Events\OnCreateWhatsappInstance;
use App\Models\User\WhatsappInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateWhatsappInstanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $whatsappInstance;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WhatsappInstance $whatsappInstance)
    {
        $this->whatsappInstance = $whatsappInstance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new OnCreateWhatsappInstance($this->whatsappInstance));
    }
}
