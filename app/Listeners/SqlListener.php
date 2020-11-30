<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class SqlListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        if ($_SERVER['SITE_ENV'] == 'testing' && php_sapi_name() == 'cli') {
            $sql = str_replace("?", "'%s'", $event->sql);
            $log = vsprintf($sql, $event->bindings);
            Log::info('['.$event->time.'ms] '.$log);
        }
    }
}
