<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['title','url', 'note_id', 'todo_id', 'user_id'];

    // Bild kann zu einem Nutzer gehören
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Bild kann zu einer Notiz gehören
    public function note() : BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    // Bild kann zu einem To Do gehören
    public function todo() : BelongsTo
    {
        return $this->belongsTo(Todo::class);
    }
}
