<?php

namespace App\Jobs;

use App\Models\InfoMedica;
use App\Services\OrthancService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDicomUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // Intentará el trabajo hasta 3 veces si falla

    protected $infoMedica;
    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(InfoMedica $infoMedica, string $filePath)
    {
        $this->infoMedica = $infoMedica;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(OrthancService $orthancService): void
    {
        
         // Intentamos la subida. Si falla, la excepción subirá y Laravel reintentará el Job.
        $paciente = $this->infoMedica->paciente;
        $response = $orthancService->uploadDicom($this->filePath, $paciente);

            if (empty($response['ParentStudy'])) {
            throw new \Exception('El archivo subido no contenía un estudio DICOM válido.');
        }
// ÉXITO: Guardamos datos y borramos el archivo temporal
        $this->infoMedica->orthanc_study_id = $response['ParentStudy'];
        $this->infoMedica->status = 'completed';
        $this->infoMedica->save();

         if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    
    }   

public function failed(\Throwable $exception): void
    {
        Log::error("Job ProcessDicomUpload falló definitivamente para ID {$this->infoMedica->id_historia}: " . $exception->getMessage());
        $this->infoMedica->status = 'failed';
        $this->infoMedica->save();

        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }
}