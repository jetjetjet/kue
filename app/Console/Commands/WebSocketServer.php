<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Libs\KapeWs;
use Ratchet\App;
use Ratchet\Server\EchoServer;

class WebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $server_ip = gethostbyname(gethostname());
		$app = new App($server_ip, 8910, '0.0.0.0');
		$app->route('/kapews', new KapeWs, array('*'));
        $app->run();
    }
}