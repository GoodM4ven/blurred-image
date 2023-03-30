<?php

namespace GoodMaven\BlurredImage\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class BlurredImage extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('blurred-image::components.blurred-image');
    }
}
