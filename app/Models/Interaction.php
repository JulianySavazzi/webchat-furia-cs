<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'message_id',
        'furia_bot_id',
        'data',
    ];

    /**
     * @var string[]
     */
    protected function casts(): array
    {
        return  [
            'user_id' => 'integer',
            'message_id' => 'integer',
            'furia_bot_id' => 'integer',
            'data' => 'array',
        ];
    }
}
