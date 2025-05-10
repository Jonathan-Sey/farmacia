<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LimpiarImagenesTemp extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:temp-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina imagenes temporales antiguas';

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
        $tempPath = public_path('uploads/temp');
        $files = File::files($tempPath);
        $now = Carbon::now();
        $deletedCount = 0;

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($file->getMTime());

            // Eliminar archivos con mas de 2 horas de antigüedad
            if ($now->diffInHours($lastModified) > 2) {
                File::delete($file->getPathname());
                $deletedCount++;
                $this->info("Eliminado: ".$file->getFilename());
            }
        }

        $this->info("Se eliminaron {$deletedCount} imagenes temporales antiguas.");
        return 0;
    }
}
