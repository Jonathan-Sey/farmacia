<?php

namespace App\Mail;

use App\Models\Devoluciones;
use App\Models\Notificaciones;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class validacion extends Mailable
{

    public $devolucion;
    public $notificaciones;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $devolucion, $notificaciones )
    {
        $this->devolucion = $devolucion;
        $this->notificaciones = $notificaciones;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */

   
    public function build()
    {

        $devoluciones = Devoluciones::with(['sucursal','productos','usuario'])->where('id',$this->devolucion->id)
        ->latest()
        ->get();

        $notificaciones = Notificaciones::where('id', $this->notificaciones->id)->first();
        
        return $this->subject('Autorización requerida para devolución')
        ->view('emails.verificacion')
        ->with([
            'devolucion' => $this->devolucion,
            'url' => route('devoluciones.autorizar',[ $this->devolucion->id, $this->notificaciones->id]),
            'devoluciones' => $devoluciones,

        ]);
       
    }
}
