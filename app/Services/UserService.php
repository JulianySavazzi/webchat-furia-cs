<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
     * ao criar o user, ja adiciona ele na team_furia_cs;
     * salva a senha com hash (criptografia);
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        if(isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user = $this->entity::query()->create($data);
        $idFuriaTeam = Team::query()->select('id')
            ->where('slug', 'team_furia_cs')
            ->get() ?? 1;
        $user->teams()->attach($idFuriaTeam);
        return $user;
    }

    /**
     * atualizar qualquer dado, exceto senha;
     * teams = id das teams (inicialmente só terá a team_furia_cs)
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data): mixed
    {
        $user = $this->entity::query()->findOrFail($id);
        $user->update($data);
        if (isset($data['teams'])) {
            $user->teams()->sync($data['teams']);
        }
        return $user;
    }
}
