<?php

namespace App\Services\Onsite;

use App\Models\Onsite\NivelOnsite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Spatie\GoogleCalendar\Event;

use Log;

class FuncionesAuxiliaresOnsiteService
{
    public function getMonthAndYearFromObject($object)
    {
        $year_month[] = null;
        
        foreach ($object as $date) {
            $da = $date->created_at;
            $dateComps = date_parse($da);
            $year = $dateComps['year'];

            if ($dateComps['month'] < 10) {
                $month = '0' . $dateComps['month'];
            } else $month = $dateComps['month'];


            $year_month[] = [
                'year_month' => $year . '/' . $month
            ];
        }

        $array_group = $this->_group_by($year_month, 'year_month');

        return $array_group;
    }

    function _group_by($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            if (isset($val[$key])) { 
                $return[$val[$key]][] = count($val);
            }
        }

        $array_group = array();

        foreach ($return as $key => $value) {

            $array_group[$key] = count($value);
        }


        return $array_group;
    }

    function createGoogleCalendarEvent($nombre, $descripcion, $fecha)
    {

        $event = new Event;

        $event->name = $nombre;
        $event->description = $descripcion;
        //$event->startDateTime = Carbon\Carbon::now();
        $event->startDateTime = $fecha;
        $event->endDateTime = $fecha->addHour();
        /* $event->addAttendee([
        'email' => 'john@example.com',
        'name' => 'John Doe',
        'comment' => 'Lorum ipsum',
    ]);
    $event->addAttendee(['email' => 'anotherEmail@gmail.com']); */

        $event->save();


        return true;
    }
}
