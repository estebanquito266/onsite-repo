<?php

namespace App\Console\Commands;

use App\Models\Onsite\ObraOnsite;
use App\Models\User;
use Illuminate\Console\Command;

class updateObrasOnsite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onsite:updateObrasOnsite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update clave from name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        /* genero token */
        $user = User::query()->first(); // Find the user by ID
        $token = $user->createToken('MyAppToken')->accessToken;

        $this->info($token);

        exit;

        $obrasOnsite = ObraOnsite::all();

        $count = 0;

        foreach ($obrasOnsite as $obraOnsite) {
            $obraOnsite->clave = $this->getClave($obraOnsite->nombre);
            $obraOnsite->save();

            $count++;
        }

        echo $count;
    }

    public function getClave($nombre)
    {
        $clave = strtolower($nombre);
        $clave = str_replace(" ", "", $clave);
        $clave = str_replace("(", "", $clave);
        $clave = str_replace(")", "", $clave);
        $clave = $this->eliminar_tildes($clave);

        return $clave;
    }

    function eliminar_tildes($cadena)
    {

        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        $cadena = utf8_encode($cadena);

        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena
        );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena
        );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena
        );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena
        );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return $cadena;
    }
}
