<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
