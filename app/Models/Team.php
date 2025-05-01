<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Team extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'slug' => 'string',
            'owner_id' => 'string',
            'is_public' => 'boolean',
        ];
    }

//relationships

    /**
     * Dono do grupo - moderador (por enquanto apenas o FURIA_BOT)
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Participantes da team - todos os usuarios cadastrados;
     * ao criar a conta, o user sera relacionado a team_furia_cs;
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teams_users',
            'team_id', 'user_id')
            ->withPivot(['message_id']);
    }

    /**
     * mensagens enviadas - uma team tem varias mensagens,
     * mas cada mensagem se relaciona com uma team;
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
