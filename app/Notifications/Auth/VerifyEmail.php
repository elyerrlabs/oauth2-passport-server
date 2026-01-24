<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

final class VerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail implements ShouldQueue
{

    use Queueable;
}
