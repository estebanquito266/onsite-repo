<?php
namespace App\Enums;

use BenSampo\Enum\Enum;

final class PuestaMarchaSatisfactoriaEnum extends Enum
{
    const APROBADA = 1;
    const APROBADACONOBSERVACIONES = 2;
    const RECHAZADA = 3;

    public static function getName($puesta_en_marcha)
    {
        switch ($puesta_en_marcha) {
            case PuestaMarchaSatisfactoriaEnum::APROBADA:
                return 'Aprobada';
                break;
            case PuestaMarchaSatisfactoriaEnum::APROBADACONOBSERVACIONES:
                return 'Sí. Aprobada pero con observaciones';
                break;
            case PuestaMarchaSatisfactoriaEnum::RECHAZADA:
                return 'No. Rechazada';
                break;
            default:
                return '';
                break;
        }
    }

    public static function getOptions()
    {
        return [
            PuestaMarchaSatisfactoriaEnum::APROBADA    => PuestaMarchaSatisfactoriaEnum::getName(PuestaMarchaSatisfactoriaEnum::APROBADA),
            PuestaMarchaSatisfactoriaEnum::APROBADACONOBSERVACIONES    => PuestaMarchaSatisfactoriaEnum::getName(PuestaMarchaSatisfactoriaEnum::APROBADACONOBSERVACIONES),
            PuestaMarchaSatisfactoriaEnum::RECHAZADA    => PuestaMarchaSatisfactoriaEnum::getName(PuestaMarchaSatisfactoriaEnum::RECHAZADA),
        ];
    }

    public static function getInvertedOptions()
    {
        return [
            PuestaMarchaSatisfactoriaEnum::RECHAZADA   => PuestaMarchaSatisfactoriaEnum::getName(PuestaMarchaSatisfactoriaEnum::RECHAZADA),
            PuestaMarchaSatisfactoriaEnum::APROBADACONOBSERVACIONES   => PuestaMarchaSatisfactoriaEnum::getName(PuestaMarchaSatisfactoriaEnum::APROBADACONOBSERVACIONES),
            PuestaMarchaSatisfactoriaEnum::APROBADA   => PuestaMarchaSatisfactoriaEnum::getName(PuestaMarchaSatisfactoriaEnum::APROBADA),
        ];
    }
}
