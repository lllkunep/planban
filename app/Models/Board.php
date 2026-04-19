<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Board extends Model
{
    protected $fillable = ['name'];
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'last_active'])
            ->withTimestamps();
    }

    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function hasUser(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function setNewOwner(User $user)
    {
        DB::transaction(function () use ($user) {
            DB::table('board_user')
                ->where('board_id', $this->id)
                ->where('role', 'owner')
                ->update(['role' => 'admin']);

            $this->members()->updateExistingPivot($user->id, [
                'role' => 'owner',
            ]);
        });
    }
}
