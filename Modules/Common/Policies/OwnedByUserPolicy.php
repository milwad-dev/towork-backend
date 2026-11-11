<?php

namespace Modules\Common\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

/**
 * Generic policy for models that have a `user_id` column. Foreign resources
 * are reported as "not found" so their existence is never revealed.
 */
class OwnedByUserPolicy
{
    public function view(User $user, Model $model): Response
    {
        return $this->owns($user, $model);
    }

    public function update(User $user, Model $model): Response
    {
        return $this->owns($user, $model);
    }

    public function delete(User $user, Model $model): Response
    {
        return $this->owns($user, $model);
    }

    protected function owns(User $user, Model $model): Response
    {
        return (int) $model->getAttribute('user_id') === (int) $user->getKey()
            ? Response::allow()
            : Response::denyAsNotFound('Resource not found.');
    }
}
