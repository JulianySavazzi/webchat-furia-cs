<?php

namespace App\Services;

use App\Models\User;

/**
 * @property User $entity
 */
class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct(User $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->entity::query()->create($data);
    }
}
