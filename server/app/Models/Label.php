<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    // ein label gehört zu einem oder keinem nutzer
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function todos() : BelongsToMany
    {
        return $this->belongsToMany(Todo::class)->withTimestamps();
    }

    public function notes() : BelongsToMany
    {
        return $this->belongsToMany(Note::class)->withTimestamps();
    }
}
