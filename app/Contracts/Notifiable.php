<?php

namespace App\Contracts;

interface Notifiable
{
    public function notify(string $subject, string $message): bool;
}
