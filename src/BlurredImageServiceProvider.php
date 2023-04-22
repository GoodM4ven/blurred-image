<?php

namespace GoodMaven\BlurredImage;

use GoodMaven\BlurredImage\Commands\InstallCommand;
use GoodMaven\BlurredImage\Commands\GenerateCommand;
use GoodMaven\BlurredImage\Components\BlurredImage;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BlurredImageServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        // * Registering Blade component
        Blade::component('blurred-image', BlurredImage::class);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('blurred-image')
            ->hasConfigFile()
            ->hasViews()
            ->hasAssets()
            ->hasCommands([
                InstallCommand::class,
                GenerateCommand::class,
            ]);
    }
}
