<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description','user_id','listoverview_id'];

    // eine Notiz kann keines, eines oder mehrere Bilder haben
    public function images() : HasMany
    {
        return $this->hasMany(Image::class);
    }

    // eine Notiz kann zu einer oder keiner Liste gehören
    public function listoverviews() : BelongsTo
    {
        return $this->belongsTo(Listoverview::class);
    }

    // eine Notiz kann keine, eine oder mehrere To Dos haben
    public function todos() : HasMany
    {
        return $this->hasMany(Todo::class);
    }

    // eine Notiz kann zu einem oder keinem Nutzer gehören
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function labels() : BelongsToMany
    {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }
}
