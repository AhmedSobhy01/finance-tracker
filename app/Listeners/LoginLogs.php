<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginLogs
{
    private $loginLog;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(LoginLog $loginLog)
    {
        $this->loginLog = $loginLog;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $this->loginLog->setLogInLog();
    }
}