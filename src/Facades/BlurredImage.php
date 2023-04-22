<?php

namespace GoodMaven\BlurredImage\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void generate(string $existingImagePath)
 *
 * @see \GoodMaven\BlurredImage\BlurredImage
 */
class BlurredImage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \GoodMaven\BlurredImage\BlurredImage::class;
    }
}
