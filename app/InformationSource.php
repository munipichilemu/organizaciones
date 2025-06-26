<?php

namespace App;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InformationSource: string implements HasColor, HasLabel
{
    case MINJUSTICIA = 'justicia';
    case MINEDUC = 'educacion';
    case MUNICIPAL = 'municipalidad';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MINJUSTICIA => Color::Purple,
            self::MINEDUC => Color::Pink,
            self::MUNICIPAL => Color::Sky,
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MINJUSTICIA => 'Ministerio de Justicia',
            self::MINEDUC => 'Ministerio de Educación',
            self::MUNICIPAL => 'Municipalidad de Pichilemu',
        };
    }
}
