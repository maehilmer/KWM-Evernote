<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','due','isPublic', 'note_id'];

    // ein To Do gehört zu einer oder keiner notiz
    public function note() : BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    // ein To Do kann keines, eines oder mehrere Bilder haben
    public function images() : HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function labels() : BelongsToMany
    {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }
}
