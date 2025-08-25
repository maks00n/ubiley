<?php

namespace App\Enum;

enum EnumWeekday: string
{
    case Monday = 'Понедельник';
    case Tuesday = 'Вторник';
    case Wednesday = 'Среда';
    case Thursday = 'Четверг';
    case Friday = 'Пятница';
    case Saturday = 'Суббота';
    case Sunday = 'Воскресенье';

    public static function getToday(): self
    {
        $dayOfWeek = (int)date('N');

        return match($dayOfWeek) {
            1 => self::Monday,
            2 => self::Tuesday,
            3 => self::Wednesday,
            4 => self::Thursday,
            5 => self::Friday,
            6 => self::Saturday,
            7 => self::Sunday,
            default => self::Monday
        };
    }

    public function getKey(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
