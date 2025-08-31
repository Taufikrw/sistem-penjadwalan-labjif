<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Filter extends Component
{
    public $type;
    public $filters;
    /**
     * Create a new component instance.
     */
    public function __construct(string $type, array $filters = [])
    {
        $this->type = $type;
        $this->filters = $filters;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->type === 'assistant') {
            return view('components.filter-assistant');
        } elseif ($this->type === 'schedule') {
            return view('components.filter-schedule');
        }

        return '';
    }
}
