<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Baja()
 * @method static static Media()
 * @method static static Alta()
 */
final class Prioridad extends Enum
{
    const BAJA = 1;
    const MEDIA = 2;
    const ALTA = 3;

    public static function getName($prioridad)
    {
        switch ($prioridad) {
            case Prioridad::BAJA:
                return 'Baja';
                break;
            case Prioridad::MEDIA:
                return 'Media';
                break;
            case Prioridad::ALTA:
                return 'Alta';
                break;
            default:
                return '';
                break;
        }
    }

    public static function getOptions()
    {
        return [
            Prioridad::BAJA    => Prioridad::getName(Prioridad::BAJA),
            Prioridad::MEDIA    => Prioridad::getName(Prioridad::MEDIA),
            Prioridad::ALTA    => Prioridad::getName(Prioridad::ALTA),
        ];
    }

    public static function getInvertedOptions()
    {
        return [
            Prioridad::ALTA   => Prioridad::getName(Prioridad::ALTA),
            Prioridad::MEDIA   => Prioridad::getName(Prioridad::MEDIA),
            Prioridad::BAJA   => Prioridad::getName(Prioridad::BAJA),
        ];
    }
}
