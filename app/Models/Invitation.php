<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['email', 'board_id'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
