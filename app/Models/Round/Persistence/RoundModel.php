<?php

namespace App\Models\Round\Persistence;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoundModel extends Model
{
    use HasFactory;

    public const TABLE_NAME = 'rounds';

    protected $table = self::TABLE_NAME;
}
