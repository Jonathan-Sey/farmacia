<?php

namespace App\Mail;

use App\Models\DetalleDevolucion;
use App\Models\Devoluciones;
use App\Models\Notificaciones;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class validacion extends Mailable
{

    public $solicitud;
    public $notificaciones;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $solicitud, $notificaciones)
    {
    
        $this->solicitud = $solicitud;
        $this->notificaciones = $notificaciones;
    }

    /**
     * Build the message.
     *
     * @return $this
     */


    public function build()
    {

       return $this->subject('Autorización requerida para devolución')
           ->view('emails.verificacion')
           ->with([
               'url' => route('devoluciones.autorizar', [$this->solicitud->id, $this->notificaciones->id]),
               'datos' => $this->solicitud,

           ]);
    }
}
