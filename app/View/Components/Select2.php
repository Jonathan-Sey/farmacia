<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select2 extends Component
{

    public $name;
    public $label;
    public $options;
    public $selected;
    public $placeholder;
    public $required;
    public $class;
    public $allowClear;
    public $maxLength;
    public $id;
    public $withStock;




    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
    $name,
    $label = null,
    $options = [],
    $selected = null,
    $placeholder = 'Seleccionar una opción',
    $required = false,
    $class = '',
    $allowClear = true,
    $maxLength = 30,
    $id = null, // Nuevo parámetro
    $withStock = false // Nuevo parámetro para mostrar stock
    )
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        //$this->selected = old($name, $selected);
        $this->selected = (string)($selected ?? old($name));
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->class = $class;
        $this->allowClear = $allowClear;
        $this->maxLength = $maxLength;
        $this->id = $id ?? $name; // Usar el name como ID por defecto
        $this->withStock = $withStock;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select2');
    }
}
