<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ErrorsLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public $type;
    public $showContact;
    public $message;
    public function __construct($title, $type, $showContact, $message = null)
    {
        //
        $this->title = $title;
        $this->type = $type;
        $this->showContact = $showContact;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.errors');
    }
}