<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewComposerMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view-composer {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new View composer class';

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
        //
    }
}
