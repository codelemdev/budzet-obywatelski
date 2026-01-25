<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case User = 'user';
    case Admin = 'admin';
    case Moderator = 'moderator';

    public function label(): string
    {
        return match ($this) {
            self::User => 'Użytkownik',
            self::Admin => 'Administrator',
            self::Moderator => 'Moderator',
        };
    }
}
