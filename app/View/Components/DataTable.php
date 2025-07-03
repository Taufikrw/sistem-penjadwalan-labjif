<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    public $url;
    public $columns;
    public $hasActions;
    public $tableId;
    /**
     * Create a new component instance.
     */
    public function __construct(string $url, array $columns, bool $hasActions = false, string $tableId = 'data-table')
    {
        $this->url = $url;
        $this->columns = $columns;
        $this->hasActions = $hasActions;
        $this->tableId = $tableId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data-table');
    }
}
