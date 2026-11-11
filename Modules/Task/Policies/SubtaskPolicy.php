<?php

namespace Modules\Task\Policies;

use Illuminate\Auth\Access\Response;
use Modules\Task\Models\Subtask;
use Modules\User\Models\User;

/**
 * A subtask belongs to whoever owns its parent task.
 */
class SubtaskPolicy
{
    public function update(User $user, Subtask $subtask): Response
    {
        return $this->owns($user, $subtask);
    }

    public function delete(User $user, Subtask $subtask): Response
    {
        return $this->owns($user, $subtask);
    }

    private function owns(User $user, Subtask $subtask): Response
    {
        return (int) $subtask->task()->value('user_id') === (int) $user->id
            ? Response::allow()
            : Response::denyAsNotFound('Resource not found.');
    }
}
