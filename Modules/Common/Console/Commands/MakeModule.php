<?php

namespace Modules\Common\Console\Commands;

use File;
use Illuminate\Console\Command;

class MakeModule extends Command
{
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
        $router = '$router';

        $pathServiceProvider = $this->pathServiceProvider($argument);

        // -------------------------------------------------------------------------------------
        $pathRepo = "<?php

namespace Modules\\$argument\Repositories;

use Modules\Common\Contracts\Interface\RepositoriesInterface;
use Modules\Common\Repositories\CommonRepoEloquent;
use Modules\\$argument\Models\\$argument;

class {$argument}Repo implements RepositoriesInterface
{
    private \$class = $argument::class;

    public function index()
    {

    }

    public function findById(\$id)
    {

    }

    public function delete(\$id)
    {

    }

    private function query()
    {
        return CommonRepoEloquent::query(\$this->class);
    }
}
";
        $route = "<?php

use Illuminate\Support\Facades\Route;

Route::group([], function ($router) {

});
        ";

        $service = "<?php

namespace Modules\\{$argument}\Services;

use Modules\Common\Contracts\Interface\ServicesInterface;
use Modules\\{$argument}\Models\\{$argument};
use Modules\Common\Repositories\CommonRepoEloquent;

class {$argument}Service implements ServicesInterface
{
    private \$class = {$argument}::class;

    public function store(\$request)
    {
        return \$this->query()->create([

        ]);
    }

    public function update(\$request, \$id)
    {
         return \$this->query()->whereId(\$id)->update([

        ]);
    }

    private function query()
    {
        return CommonRepoEloquent::query(\$this->class);
    }
}
        ";

        File::makeDirectory('Modules/'.$argument);

        // Databases
        File::makeDirectory('Modules/'.$argument.'/Database');
        File::makeDirectory('Modules/'.$argument.'/Database/Migrations');

        // Providers
        File::makeDirectory('Modules/'.$argument.'/Providers');
        File::put('Modules/'.$argument.'/Providers/'.$argument.'ServiceProvider.php', $pathServiceProvider);

        // Repositories
        File::makeDirectory('Modules/'.$argument.'/Repositories');
        File::put('Modules/'.$argument.'/Repositories/'.$argument.'Repo.php', $pathRepo);

        // Http
        File::makeDirectory('Modules/'.$argument.'/Http');
        File::makeDirectory('Modules/'.$argument.'/Http/Controllers');

        // Models
        File::makeDirectory('Modules/'.$argument.'/Models');

        // routes
        File::makeDirectory('Modules/'.$argument.'/routes');
        File::put('Modules/'.$argument.'/routes/'.strtolower($argument).'_routes.php', $route);

        // Services
        File::makeDirectory('Modules/'.$argument.'/Services');
        File::put('Modules/'.$argument.'/Services/'.$argument.'Service.php', $service);

        // Views
        File::makeDirectory('Modules/'.$argument.'/Resources');
        File::makeDirectory('Modules/'.$argument.'/Resources/Views');

        $this->info("Your Module $argument Make Successfully");
    }

    private function pathServiceProvider(string $argument)
    {
        return "<?php

namespace Modules\\$argument\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class {$argument}ServiceProvider extends ServiceProvider
{
    public $/namespace = 'Modules\\$argument\Http\Controllers';

    public function register()
    {
        $/this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $/this->loadViewsFrom(__DIR__ . '/../Resources/Views/', '{$argument}');
        Route::middleware('web')->namespace($/this->namespace)->group(__DIR__ . '/../routes/{$argument}_routes.php');
    }
}
";
    }
}
