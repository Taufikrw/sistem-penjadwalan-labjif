<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    public $url;
    public $actionUrl;
    public $columns;
    public $hasActions;
    public $tableId;
    public $primary;
    public $hasAssistant;
    public $searchInputId;
    public $btnCreateId;
    /**
     * Create a new component instance.
     */
    public function __construct(string $url, string $actionUrl, array $columns, bool $hasActions = false, string $tableId = 'data-table', string $primary = 'id', bool $hasAssistant = false, string $searchInputId = '', string $btnCreateId = '')
    {
        $this->url = $url;
        $this->actionUrl = $actionUrl;
        $this->columns = $columns;
        $this->hasActions = $hasActions;
        $this->tableId = $tableId;
        $this->primary = $primary;
        $this->hasAssistant = $hasAssistant;
        $this->searchInputId = $searchInputId;
        $this->btnCreateId = $btnCreateId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data-table');
    }
}
