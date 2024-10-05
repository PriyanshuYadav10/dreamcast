<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set the Telegram webhook';

    public function handle()
    {
        $url = route('telegram.webhook');
        Telegram::setWebhook(['url' => $url]);
        $this->info("Webhook is set to $url");
    }
}
