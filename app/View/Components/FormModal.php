<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormModal extends Component
{
    public $modalId;
    public $ajaxUrl;
    public $formId;
    
    /**
     * Create a new component instance.
     */
    public function __construct(string $modalId, string $ajaxUrl, ?string $formId = null)
    {
        $this->modalId = $modalId;
        $this->ajaxUrl = $ajaxUrl;
        $this->formId = $formId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-modal');
    }
}
