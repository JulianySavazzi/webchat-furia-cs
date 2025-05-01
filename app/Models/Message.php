<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'team_id',
        'user_from',
        'user_to',
        'content',
        'read_at'
    ];

    protected function casts(): array
    {
        return [
            'team_id' => 'integer',
            'user_from' => 'integer',
            'user_to' => 'integer',
            'content' => 'string',
            'read_at' => 'datetime'
        ];
    }

//relationships

    /**
     * sender - user quem envia a mensagem
     *
     * @return BelongsTo
     */
    public function userFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_from');
    }

    /**
     * recipient - user quem recebe a mensagem
     *
     * @return BelongsTo
     */
    public function userTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_to');
    }

    /**
     * team - grupo onde sera enviada a mensagem;
     * nesse caso, quando o user envia mensagem na team_furia_cs;
     *
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
