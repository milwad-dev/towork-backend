<?php

namespace Modules\Common\Console\Commands\Traits\MakeModuleTraits;

trait RepositoryTrait
{
    public function getRepoBodyData(string $argument)
    {
        return "<?php

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
    }
}
