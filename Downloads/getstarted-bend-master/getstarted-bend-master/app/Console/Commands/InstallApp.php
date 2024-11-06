<?php

namespace App\Console\Commands;

use App\Models\Empresa\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install {--class=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instalación inicial del Sistema Integral de Gestión de Servicios';

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
        $this->info('Iniciando proceso de instalación '. date("Y-m-d H:i:s"));

        $class = $this->option('class') ?? 'DatabaseSeeder';

        Artisan::call('migrate:fresh');

        // $nombre =  $this->ask('Indique el nombre de la empresa: ');

        // $empresa = new Empresa;
        // $empresa->nombre = $nombre;
        // $empresa->save();

        Artisan::call('db:seed', ['--class' => $class]);
        Artisan::call('jwt:secret', ['--force' => true]);

        $this->info('Terminado! '. date("Y-m-d H:i:s"));
    }
}
