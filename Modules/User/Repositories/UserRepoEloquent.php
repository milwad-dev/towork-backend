<?php

namespace Modules\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class UserRepoEloquent
{
    /**
     * Get latest users.
     *
     * @return Builder|Model
     */

    public function query()
    {
        return new User();
    }

    public function getLatest()
    {
        return $this->query()->latest();
    }

    public function findByEmail(string $email)
    {
        return $this->query()->where('email', $email)->firstOrFail();
    }

    public function update(array $data , int $id): void
    {
        $model = $this->query()->where('id' , $id)->firstOrFail();

        if (!isset($data['password'])){
            $data['password'] = $model->password;
        }else{
            $data['password'] = bcrypt($data['password']);
        }

        $model->update($data);
    }

    public function destroy(int $id)
    {
        $this->query()->where('id' , $id)->delete();
    }
}
