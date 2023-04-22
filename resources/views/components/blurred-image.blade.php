@props([
    'alt' => '',
    'widthClass' => 'w-full',
    'heightClass' => 'h-full',
    'imageClasses' => null,
    'containerClasses' => '',
    'isObjectCentered' => true,
    // Settings
    'isBackground' => config('blurred-image.is-background'),
    'isEagerLoaded' => config('blurred-image.is-eager-loaded'),
    'isDisplayEnforced' => config('blurred-image.is-display-enforced'),
    // Optional (For usecase 1 and 2)
    'conversion' => '',
    // Usecase 1
    'model' => null,
    'mediaIndex' => 0,
    'collection' => 'default',
    // Usecase 2
    'media' => null,
    // Usecase 3
    'imagePath' => null,
    'thumbnailImagePath' => null,
])

@once
    @php
        if (!config('blurred-image.conversion-name') 
            || is_null($isBackground) 
            || is_null($isEagerLoaded) 
            || is_null($isDisplayEnforced)
        ) {
            throw new \Exception('Blurred Image exception: `conversion-name` is not found in a "blurred-image.php" config file.');
        }
    @endphp
@endonce

@php
    if ($media) {
        $thumbnailLink = $media->getUrl(config('blurred-image.conversion-name'));
        $hasConversion = $media->hasGeneratedConversion($conversion);
        $mediaLink = $media->getUrl($hasConversion ? $conversion : '');
    } else {
        $thumbnailLink = $model
            ? $model
                ->getMedia($collection)
                ->slice($mediaIndex, 1)
                ->first()
                ?->getUrl(config('blurred-image.conversion-name'))
            : $thumbnailImagePath;
        $hasConversion = $conversion
            ? $model
                ->getMedia($collection)
                ->slice($mediaIndex, 1)
                ->first()
                ?->hasGeneratedConversion($conversion)
            : false;
        $mediaLink = $model
            ? $model
                ->getMedia($collection)
                ->slice($mediaIndex, 1)
                ->first()
                ?->getUrl($hasConversion ? $conversion : '')
            : $imagePath;
    }
@endphp

<!-- Image Container -->
<div {{ $attributes->class([$widthClass, $heightClass, 'mx-auto'])->merge() }}>
    @if ($thumbnailLink)
        <div
            x-data="blurredImage({
                thumbnailLink: @js($thumbnailLink),
                link: @js($mediaLink),
                element: $el,
                isBackground: @js($isBackground),
                isDisplayEnforced: @js($isDisplayEnforced),
            });"
            x-intersect:enter.full="visible = true"
            x-intersect:leave.full="if (isDisplayEnforced) return visible = true; visible = false;"
            @class([
                $widthClass,
                $heightClass,
                $containerClasses,
                $imageClasses,
                'relative 2xl:mx-auto overflow-hidden image-classes',
            ])
        >
            <!-- First Placeholder -->
            <div
                x-data="{ show: false, animate: false }"
                x-cloak
                x-show="!showRealPlaceholder && show"
                x-init="$nextTick(() => {
                    show = true;
                    setTimeout(() => {
                        animate = true;
                        showRealPlaceholder = true;
                    }, 1000);
                })"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-1000"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @class([
                    $imageClasses => $imageClasses,
                    'absolute inset-0 h-[calc(100%+75rem)] w-[calc(100%+75rem)] object-fill bg-gradient-to-br from-primary-300 via-secondary-300 to-primary-300 image-classes',
                ])
            ></div>

            <!-- Second Placeholder -->
            <canvas
                x-cloak
                x-data="{ show: false }"
                x-show="show && !isReadyToShowBackground && showRealPlaceholder"
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-1000"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                width="32"
                height="32"
                x-init="setTimeout(() => show = true, 500)"
                @class([
                    $imageClasses => $imageClasses,
                    'absolute inset-0 h-full w-full image-classes',
                ])
            ></canvas>

            <!-- The Image -->
            <img
                src="{{ $mediaLink }}"
                alt="{{ $alt }}"
                loading="{{ $isEagerLoaded ? 'eager' : 'lazy' }}"
                x-init="loaded = $el.complete && $el.naturalHeight !== 0"
                x-bind:loading="visible ? 'eager' : 'lazy'"
                x-on:load="loaded = true"
                x-show="isReadyToShowImage()"
                x-cloak
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                @class([
                    $imageClasses => $imageClasses,
                    'object-center' => $isObjectCentered,
                    'absolute inset-0 h-full w-full object-cover image-classes',
                ])
            >

            @if ($slot->isNotEmpty())
                <!-- Extra Content -->
                {{ $slot }}
            @endif
        </div>
    @else
        @php
            if (config('blurred-image.throws-exception')) {
                throw new \Exception('Blurred Image exception: Image not found!');
            }
        @endphp

        <img
            x-data="{ show: false }"
            x-cloak
            x-show="show"
            x-init="$nextTick(() => show = true)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="h-full w-full object-cover"
            src="{{ asset('vendor/blurred-image/images/empty-media-placeholder.png') }}"
            alt="{{ __('Empty image placeholder') }}"
        >
    @endif
</div>
