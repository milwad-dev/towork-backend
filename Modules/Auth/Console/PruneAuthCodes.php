<?php

namespace Modules\Auth\Console;

use Illuminate\Console\Command;
use Modules\Auth\Services\AuthCodeService;

class PruneAuthCodes extends Command
{
    protected $signature = 'auth:prune-codes';

    protected $description = 'Delete expired or exhausted verification / reset codes';

    public function handle(AuthCodeService $codes): int
    {
        $this->components->info($codes->prune().' code(s) pruned.');

        return self::SUCCESS;
    }
}
