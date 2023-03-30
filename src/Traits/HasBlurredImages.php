<?php

namespace GoodMaven\BlurredImage\Traits;

use Exception;

trait HasBlurredImages
{
    // ! You must add this method to your conversions!
    public function addBlurredThumbnailConversion()
    {
        $this->addMediaConversion($this->thumbnailConversionName())
            ->width(208)
            ->height(117)
            ->sharpen(10);
    }

    // * An Accessor
    public function getBlurredImageThumbnailUrl(string $collection = 'default', int $mediaIndex = 0): string
    {
        $this->initialChecks($collection, $mediaIndex);

        return $this->getMedia($collection)
            ->slice($mediaIndex, 1)
            ->first()
            ->getUrl($this->thumbnailConversionName());
    }

    protected function thumbnailConversionName(): string
    {
        return config('blurred-image.conversion-name');
    }

    protected function initialChecks(string $collection, int $mediaIndex): void
    {
        $media = $this->getMedia($collection)
            ->slice($mediaIndex, 1)
            ->first();

        if (!$media) {
            throw new Exception("Blurred Image exception: No media found for the \"{$collection}\" collection. Double check your collection and media, please.");
        } elseif ($media?->responsive_images) {
            throw new Exception("Blurred Image exception: The found media has responsive images. There's no point of using the BlurredImage package then!");
        } elseif (!$media?->hasGeneratedConversion($this->thumbnailConversionName())) {
            throw new Exception("Blurred Image exception: The found media does not have a generated blur-thumbnail. Please generate one for it using the Artisan command or the BlurredImage::generate() facade's method.");
        }
    }
}
