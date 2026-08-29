<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AuditService;


class UserObserver
{
    public function created(User $user): void
    {
        AuditService::log(
            'user.registered',
            $user,
            [],
            ['name' => $user->name, 'email' => $user->email]
        );
    }

    public function updated(User $user): void
    {
        // Ban status পরিবর্তন
        if ($user->isDirty('is_banned')) {
            AuditService::log(
                $user->is_banned ? 'user.banned' : 'user.unbanned',
                $user,
                ['is_banned' => $user->getOriginal('is_banned')],
                ['is_banned' => $user->is_banned]
            );
        }

        // Password পরিবর্তন
        if ($user->isDirty('password')) {
            AuditService::log('user.password_changed', $user);
        }
    }
}
