<?php

namespace App\Services;

use App\Models\Interaction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
        if (isset($data['password'])) {
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

    /**
     * retorna o usuario logado
     *
     * @return Authenticatable|null
     */
    public function me()
    {
        return auth()->user();
    }

    /**
     * retorna o username de todos os usuarios cadastrados
     *
     * @return Collection
     */
    public function getAllUsernames()
    {
        return $this->entity::query()
            ->select( 'username')
            ->get();
    }

    /**
     * listar todos os usuaarios cadastradoos, exceto usuario logado;
     *
     * @param array $data
     * @return Collection
     */
    public function index(array $data)
    {
        $me = auth()->user();

        return User::query()->with('teams')
            ->whereNot('id', $me->id)
            ->where(function ($query) use ($data) {
                if (isset($data['name'])) {
                    $query->where('name', 'like', '%' . strtolower($data['name']) . '%');
                }
                if (isset($data['email'])) {
                    $query->where('email', 'like', '%' . strtolower($data['email']) . '%');
                }
                if (isset($data['username'])) {
                    $query->where('username', 'like', '%' . strtolower($data['username']) . '%');
                }
            })
            ->orderBy('id')
            ->get();
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function getLoginUserInteractions(array $data)
    {
        return Interaction::query()
            ->where('user_id', $data['user_id'])
            ->whereJsonContains('data->logout', [])->get();
    }
}
