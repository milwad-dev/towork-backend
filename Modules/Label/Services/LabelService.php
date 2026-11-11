<?php

namespace Modules\Label\Services;

use Modules\Label\Models\Label;
use Modules\User\Models\User;

class LabelService
{
    public function store(User $user, array $data): Label
    {
        return $user->labels()->create($data);
    }

    public function update(Label $label, array $data): Label
    {
        $label->update($data);

        return $label;
    }

    public function delete(Label $label): void
    {
        $label->delete();
    }
}
