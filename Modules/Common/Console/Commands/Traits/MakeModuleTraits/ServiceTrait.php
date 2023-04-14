<?php

namespace Modules\Common\Console\Commands\Traits\MakeModuleTraits;

trait ServiceTrait
{
    public function getServiceBodyData(string $argument)
    {
        return "<?php

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
    }
}
