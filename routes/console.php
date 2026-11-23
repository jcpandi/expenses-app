<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:reset-password {email}', function (string $email) {
    $user = \App\Models\User::where('email', $email)->first();
    if (! $user) {
        $this->error("No account with email $email.");

        return 1;
    }
    $password = $this->secret('New password (at least 8 characters)');
    if (strlen((string) $password) < 8) {
        $this->error('Password must be at least 8 characters.');

        return 1;
    }
    $user->update(['password' => $password]);
    $this->info('Password updated.');
})->purpose('Set a new password for the app account');
