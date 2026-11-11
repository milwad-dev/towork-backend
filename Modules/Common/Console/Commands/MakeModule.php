<?php

namespace Modules\Common\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {name : Module name, e.g. Comment}';

    protected $description = 'Create a new module skeleton inside Modules/';

    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $base = base_path('Modules/'.$name);

        if (File::isDirectory($base)) {
            $this->components->error("Module [{$name}] already exists.");

            return self::FAILURE;
        }

        foreach (['Database/Migrations', 'Database/Factories', 'Http/Controllers', 'Http/Requests', 'Http/Resources', 'Models', 'Providers', 'Routes', 'Services', 'Tests/Feature'] as $dir) {
            File::makeDirectory($base.'/'.$dir, 0755, true);
        }

        File::put($base."/Providers/{$name}ServiceProvider.php", <<<PHP
        <?php

        namespace Modules\\{$name}\\Providers;

        use Modules\\Common\\Providers\\ModuleServiceProvider;

        class {$name}ServiceProvider extends ModuleServiceProvider
        {
            protected string \$module = '{$name}';
        }

        PHP);

        File::put($base.'/Routes/api.php', <<<'PHP'
        <?php

        use Illuminate\Support\Facades\Route;

        Route::middleware('auth:sanctum')->group(function () {
            //
        });

        PHP);

        $this->components->info("Module [{$name}] created.");
        $this->components->bulletList([
            "Add \"Modules\\\\{$name}\\\\\": \"Modules/{$name}/\" to composer.json autoload and run composer dump-autoload.",
            "Register Modules\\{$name}\\Providers\\{$name}ServiceProvider in bootstrap/providers.php.",
        ]);

        return self::SUCCESS;
    }
}
