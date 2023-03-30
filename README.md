<div align="center">
    بسم الله الرحمن الرحيم
</div>

# Blurred Image

[![Latest Version on Packagist](https://img.shields.io/packagist/v/goodm4ven/blurred-image.svg?style=flat-square)](https://packagist.org/packages/goodm4ven/blurred-image)
[![Total Downloads](https://img.shields.io/packagist/dt/goodm4ven/blurred-image.svg?style=flat-square)](https://packagist.org/packages/goodm4ven/blurred-image)

<!-- [![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/goodm4ven/blurred-image/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/goodm4ven/blurred-image/actions?query=workflow%3Arun-tests+branch%3Amain) -->
<!-- [![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/goodm4ven/blurred-image/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/goodm4ven/blurred-image/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain) -->

This **[TALL stack](https://tallstack.dev)** package implements [Blurhash](https://blurha.sh) in order to render images in 3 phases:

1. A blurred placeholder according to Tailwind's `primary` color.
2. A blurred version of the image; faded in.
3. The image itself; faded in.

It can also be integrated with your [Laravel Media Library](https://github.com/spatie/laravel-medialibrary) setup to do the same to its model images.

https://user-images.githubusercontent.com/121377476/228868383-fcc7971b-e0ad-4e46-8093-92290514e029.mp4


## Requirements

Again, this is a TALL stack package. You're expected to have installed:

- TailwindCSS
- Alpine.js
- Laravel (PHP, Composer, NPM)


## Installation

1. Install the package via Composer:

   ```bash
   composer require goodm4ven/blurred-image
   ```

2. Run the installation Artisan command:

   ```bash
   php artisan blurred-image:install
   ```

   - The command publishes the package assets.

   - The command publishes the package config file.
     - <details>
         <summary>
           Here is the default configuration:
         </summary><br>

         ```php
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
         ```
       </details>

   - The command adds the following front-end packages via NPM:

     - [`blurhash`](https://blurha.sh)
     - [`@alpinejs/intersect`](https://alpinejs.dev/plugins/intersect)

   - The command executes `npm install` to install these front-end packages.

   - The command asks to **optionally** install [Laravel Media Library](https://github.com/spatie/laravel-medialibrary) package via Composer.

- Optionally, you can publish the blade component's view too:

  ```bash
  php artisan vendor:publish --tag="blurred-image-views"
  ```

3. Add the package's JS script and the Intersect plugin to your Alpine setup:

   ```js
   // In [resources/js/app.js] for instance:

   import '../../public/vendor/blurred-image/js/blurred-image';

   import Alpine from 'alpinejs';
   import Intersect from '@alpinejs/intersect';

   Alpine.plugin(Intersect);

   window.Alpine = Alpine;
   Alpine.start();
   ```

   > **Note**: Don't forget about Vite.js asset compilation.


## Usage

### **With** [Laravel Media Library](https://github.com/spatie/laravel-medialibrary):

1. Set up the model's collection and add the package's [conversion](./src/Traits/HasBlurredImages.php#L10):
   ```php
   use GoodMaven\BlurredImage\Traits\HasBlurredImages;
   use Spatie\MediaLibrary\HasMedia;
   use Spatie\MediaLibrary\InteractsWithMedia;
   use Spatie\MediaLibrary\MediaCollections\Models\Media;

   class Army implements HasMedia
   {
      // ...
      use InteractsWithMedia, HasBlurredImages;

      // ...
      public function registerMediaCollections(): void
      {
          $this->addMediaCollection('banner');
      }

      public function registerMediaConversions(Media $media = null): void
      {
          $this->addBlurredThumbnailConversion();
      }
   }
   ```
   > **Note** Keep in mind that the conversion's dimensions for now are hard-coded to `208x117`; and the image should be larger therefore.

2. Upload the image to the image and hook it to the model's collection either manually through [Livewire](https://laravel-livewire.com/docs/file-uploads) or using [Filament's](https://filamentphp.com/docs/2.x/spatie-laravel-media-library-plugin/installation).

3. Either pass the model itself or the specific media to the view and use the Blade component accordingly:
   ```blade
   <x-blurred-image
       alt="some description"        {{-- Defaults to '' --}}
       widthClass="w-72"             {{-- Defaults to 'w-full' --}}
       heightClass="h-32"            {{-- Defaults to 'h-full' --}}
       imageClasses="border-2"       {{-- Defaults to null --}}
       containerClasses="rounded-md" {{-- Defaults to null --}}
       :isObjectCentered="true"      {{-- Defaults to true --}}
       :isBackground="false"         {{-- Defaults to config('blurred-image.is-background') --}}
       :isEagerLoaded="false"        {{-- Defaults to config('blurred-image.is-eager-loaded') --}}
       :isDisplayEnforced="false"    {{-- Defaults to config('blurred-image.is-display-enforced') --}}
       {{-- Case 1 - When the model is in the view --}}
       :model="$army"                {{-- REQUIRED --}}
       :mediaIndex="2"               {{-- Defaults to 0 --}}
       collection="banners"          {{-- Defaults to 'default' --}}
       {{-- Case 2 - When the media is in the view --}}
       :media="$ArmysThirdBanner"    {{-- REQUIRED --}}
   />
   ```

### **Without** Laravel Media Library:

1. Have an accessible image somewhere.

2. Generate a thumbnail to it using either:
   - `BlurredImage::generate($path)` facade method.
   - Or via the Artisan command `blurred-image:generate`.

3. Use the following Blade component wherever you wish the image to be:
   ```blade
   <x-blurred-image
       alt="some description"        {{-- Defaults to '' --}}
       widthClass="w-72"             {{-- Defaults to 'w-full' --}}
       heightClass="h-32"            {{-- Defaults to 'h-full' --}}
       imageClasses="border-2"       {{-- Defaults to null --}}
       containerClasses="rounded-md" {{-- Defaults to null --}}
       :isObjectCentered="true"      {{-- Defaults to true --}}
       :isBackground="false"         {{-- Defaults to config('blurred-image.is-background') --}}
       :isEagerLoaded="false"        {{-- Defaults to config('blurred-image.is-eager-loaded') --}}
       :isDisplayEnforced="false"    {{-- Defaults to config('blurred-image.is-display-enforced') --}}
       {{-- REQUIRED --}}
       imagePath="{{ asset('images/sky.jpg') }}"
       {{-- OPTIONAL - Will try to resolve it automatically as 'images/sky-thumbnail.jpg' if not given --}}
       thumbnailImagePath="{{ asset('images/sky-thumbnail.jpg') }}"
   />
   ```


## Todos
- [ ] Add the conversion thumbnail's width and height to the config file and organize it.


## Credits

- [TailwindCSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [Laravel](https://laravel.com)
- [Laravel Media Library](https://github.com/spatie/laravel-medialibrary)
- [Blurha.sh](https://blurha.sh)
- [Contributors](../../contributors)


## Support

Support the maintenance as well as the development of [other projects](https://github.com/sponsors/GoodM4ven) through sponsorship or one-time [donations](https://github.com/sponsors/GoodM4ven?frequency=one-time&sponsor=GoodM4ven).


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


<div align="center">
    <br>والحمد لله رب العالمين
</div>
