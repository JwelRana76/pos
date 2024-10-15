<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InlineInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $id = '',
        public $class = '',
        public $type = '',
        public $name = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inline-input');
    }
}
