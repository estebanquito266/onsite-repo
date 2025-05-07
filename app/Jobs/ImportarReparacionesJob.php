<?php

namespace App\Jobs;

use App\Events\ExportCompleted;
use App\Exports\ReparacionesOnsiteExport;
use App\Models\Notificacion;
use App\Services\Onsite\Reparacion\ImportacionService;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Log;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;

class ImportarReparacionesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;
    public $user;
    protected $importacionService;
    protected $company_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $user, ImportacionService $importacionService, $company_id)
    {
        $this->file = $file;
        $this->user = $user;
        $this->importacionService = $importacionService;
        $this->company_id = $company_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::alert('comienza importación');
        
        $mje = $this->importacionService->importar($this->file, $this->user, $this->company_id);

        $notificacion = Notificacion::create([
            'notificacion' => $mje,
            'tipo' => 'importacion'
        ]);

    

        Log::alert('finaliza importación');
    }
}
