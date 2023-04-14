<?php

namespace Modules\Common\Console\Commands;

use File;
use Illuminate\Console\Command;
use Modules\Common\Console\Commands\Traits\MakeModuleTraits\{ServiceProviderTrait, RepositoryTrait, ServiceTrait, RouteTrait};

class MakeModule extends Command
{
    use ServiceProviderTrait;
    use RepositoryTrait;
    use ServiceTrait;
    use RouteTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';

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
     * @return int
     */
    public function handle()
    {
        $argument = $this->argument('name');

        File::makeDirectory('Modules/'.$argument);

        // Databases
        File::makeDirectory('Modules/'.$argument.'/Database');
        File::makeDirectory('Modules/'.$argument.'/Database/Migrations');

        // Providers
        File::makeDirectory('Modules/'.$argument.'/Providers');
        File::put('Modules/'.$argument.'/Providers/'.$argument.'ServiceProvider.php', $this->pathServiceProvider($argument));

        // Repositories
        File::makeDirectory('Modules/'.$argument.'/Repositories');
        File::put('Modules/'.$argument.'/Repositories/'.$argument.'Repo.php', $this->getRepoBodyData($argument));

        // Http
        File::makeDirectory('Modules/'.$argument.'/Http');
        File::makeDirectory('Modules/'.$argument.'/Http/Controllers');

        // Models
        File::makeDirectory('Modules/'.$argument.'/Models');

        // routes
        File::makeDirectory('Modules/'.$argument.'/routes');
        File::put('Modules/'.$argument.'/routes/'.strtolower($argument).'_routes.php', $this->getRouteBodyData($argument));

        // Services
        File::makeDirectory('Modules/'.$argument.'/Services');
        File::put('Modules/'.$argument.'/Services/'.$argument.'Service.php', $this->getServiceBodyData($argument));

        // Views
        File::makeDirectory('Modules/'.$argument.'/Resources');
        File::makeDirectory('Modules/'.$argument.'/Resources/Views');

        $this->info("Your Module $argument Make Successfully");
    }
}
