<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Thumbnail Conversion Name (Laravel Media Library)
     |--------------------------------------------------------------------------
     |
     | The conversion name for the Blurhash thumbnail that will be generated.
     |
     | Warning: This shouldn't be used as a conversion name again on the model.
     |
     */

    'conversion-name' => env('BLURRED_IMAGE_CONVERSION_NAME', 'good-thumbnail'),


    /*
     |--------------------------------------------------------------------------
     | Is Background Styled
     |--------------------------------------------------------------------------
     |
     | Determine whether the default images setup is done using the CSS property
     | `background-image: url()`, instead of an `<img>` element.
     |
     */

    'is-background' => env('BLURRED_IMAGE_IS_BACKGROUND', false),


    /*
     |--------------------------------------------------------------------------
     | Is Eager Loaded
     |--------------------------------------------------------------------------
     |
     | Determine whether images shuold begin loading even before they're
     | intersected with (fully) in the view window.
     |
     | Check Alpine.js Intersect plugin: https://alpinejs.dev/plugins/intersect
     |
     */

    'is-eager-loaded' => env('BLURRED_IMAGE_IS_EAGER_LOADED', false),


    /*
     |--------------------------------------------------------------------------
     | Is Display Enforced
     |--------------------------------------------------------------------------
     |
     | Decide whether images should fade in even if they're not intersected with
     | (fully) in the view window.
     |
     | Check Alpine.js Intersect plugin: https://alpinejs.dev/plugins/intersect
     |
     */

    'is-display-enforced' => env('BLURRED_IMAGE_IS_DISPLAY_ENFORCED', false),


    /*
     |--------------------------------------------------------------------------
     | Throwing Not Found Exceptions
     |--------------------------------------------------------------------------
     |
     | Should the package throw an exception when a targeted image isn't found?
     | If false, then the empty image placeholder will be displayed instead.
     |
     */

    'throws-exception' => env('BLURRED_IMAGE_THROWS_EXCEPTION', false),

];
