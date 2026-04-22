<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class LandingLayout extends Component
{
    public $pageTitle;
    public $metaDescription;
    public $metaKeywords;

    /**
     * Create a new component instance.
     */
    public function __construct($pageTitle = null, $metaDescription = null, $metaKeywords = null)
    {
        $this->pageTitle = $pageTitle;
        $this->metaDescription = $metaDescription;
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.landing');
    }
}

