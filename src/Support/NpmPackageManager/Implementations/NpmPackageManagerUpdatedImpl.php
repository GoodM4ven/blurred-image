<?php

namespace GoodMaven\BlurredImage\Support\NpmPackageManager\Implementations;

use GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts\NpmPackageManagerInstalled;
use GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts\NpmPackageManagerUpdated;
use GoodMaven\BlurredImage\Support\NpmPackageManager\NpmPackageManager;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class NpmPackageManagerUpdatedImpl extends NpmPackageManagerStartedImpl implements NpmPackageManagerUpdated
{
    public function __construct(private NpmPackageManager $manager)
    {
    }

    public function installPackages(Command $command): NpmPackageManagerInstalled
    {
        $process = new Process(['npm', 'install']);
        $process->run();

        if (!$process->isSuccessful()) {
            $command->warn($process->getErrorOutput());
        }
        
        return new NpmPackageManagerInstalledImpl($this->manager);
    }
}
