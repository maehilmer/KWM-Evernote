<?php

namespace App\Providers;

use App\Models\Listoverview;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */

    // Hier wird verglichen, ob ein gewisser User eine gewisse Notiz erstellt hat
    public function boot(): void
    {
        Gate::define('own-note', function (User $user, Note $note) {
            return $user->id == $note->user_id;
        });
    }
}
