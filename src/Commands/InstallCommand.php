<?php

namespace GoodMaven\BlurredImage\Commands;

use GoodMaven\BlurredImage\Support\ComposerPackageManager;
use GoodMaven\BlurredImage\Support\NpmPackageManager\NpmPackageManager;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    public $signature = 'blurred-image:install';

    public $description = 'Install Blurred Image package and its assets.';

    public function handle(): int
    {
        $promptForConfirmation = false;
        
        $this->info('Installing Blurred Image package...');

        // * Possible Overriding Confirmation
        if (ComposerPackageManager::isPackageAssetsAvailable('blurred-image')) {
            $promptForConfirmation = true;

            if (!$this->confirm('The existing Blurred Image configuration, assets and views will be overridden. Do you still wish to continue?', true)) {
                $this->info('Aborted Blurred Image package installation safely.');
    
                return self::FAILURE;
            }
        }

        // * Possible Laravel Media Library Installation
        if (!ComposerPackageManager::isPackageInstalled('spatie/laravel-medialibrary')) {
            $promptForConfirmation = true;

            if ($this->confirm('Do you wish to install Laravel Media Library all along?', true)) {
                $process = new Process(['composer', 'require', 'spatie/laravel-medialibrary']);
                $process->run();
                
                if (!$process->isSuccessful()) {
                    $this->error('Composer installation failed. Error output:');
                    $this->line($process->getErrorOutput());
    
                    return self::FAILURE;
                }
    
                $this->comment('Laravel Media Library package installed via Composer.');
                $this->info('Please refer back to their documentation. Or check my tall-stacker setup for it.');
                $this->newLine();
            }
        }

        if (!$promptForConfirmation) {
            $this->newLine();
        }

        // * Config
        $this->callSilently('vendor:publish', [
            '--tag' => "blurred-image-config",
            '--force',
        ]);
        $this->comment('Config file published.');

        // * Assets
        $this->callSilently('vendor:publish', [
            '--tag' => "blurred-image-assets",
            '--force',
        ]);
        $this->comment('Assets published.');

        // * Front-end Packages Addition
        if (filled(NpmPackageManager::checkForMissingPackages(['@alpinejs/intersect', 'blurhash'], true))) {
            NpmPackageManager::make()
                ->addPackages([
                    '@alpinejs/intersect' => '^3.12.0',
                    'blurhash' => '^2.0.5',
                ])
                ->comment($this, 'Blurhash package added.')
                ->comment($this, 'Alpine.js Intersect package added.')
                ->updatePackagesFile(dev: false)
                ->installPackages($this)
                ->comment($this, 'Installed packages via NPM.')
                ->comment($this, '');
        }

        $this->info('Blurred Image package has been installed successfully.');
        $this->info('Please proceed to reading the "Usage" section in the documentation.');

        return self::SUCCESS;
    }
}
