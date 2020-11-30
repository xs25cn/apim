<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Swoole\WebSocket\Server;

class WebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webScoket {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run webScoket 8080';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arg = $this->argument('action');
        switch ($arg) {
            case 'start':
                $this->info('webScoket server start');
                $this->start();
                break;
        }
    }

    private function start()
    {
        $server = new Server("0.0.0.0", 8080);
        $server->on('open', function (Server $server, $request) {
            echo "server:{$request->fd}\n";
        });
        $server->on('message', function (Server $server, $frame) {
            $path = $frame->data;
            $handle = popen("tail -f -n 1 {$path}", "r");
            while (!feof($handle)) {
                $buffer = fgets($handle);
                $server->push($frame->fd, $buffer);
            }
            pclose($handle);
        });
        $server->on('close', function ($server, $fd) {
            echo "client {$fd} closed\n";
        });
        $server->start();
    }
}
