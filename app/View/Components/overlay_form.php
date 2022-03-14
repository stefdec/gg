<?php

namespace App\View\Components;

use Illuminate\View\Component;

class overlay_form extends Component
{
    public $formText;
    public $formAction;
    public $formId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($formText, $formAction, $formId)
    {
        $this->formText = $formText;
        $this->formAction = $formAction;
        $this->formId = $formId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.overlay_form');
    }
}
