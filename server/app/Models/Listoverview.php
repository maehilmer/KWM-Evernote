<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listoverview extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'isPublic'];

    // eine Liste kann keine, eine oder mehrere Notizen haben
    public function notes() : HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'listoverview_user')
            ->withPivot('user_id') // Zusätzliches Feld aus der Zwischentabelle listoverview_user
            ->withTimestamps();
    }
}
