<?php

namespace GoodMaven\BlurredImage\Support\NpmPackageManager\Implementations;

use Exception;
use GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts\NpmPackageManagerInstalled;
use GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts\NpmPackageManagerStarted;
use GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts\NpmPackageManagerUpdated;
use GoodMaven\BlurredImage\Support\NpmPackageManager\NpmPackageManager;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class NpmPackageManagerStartedImpl extends NpmPackageManager implements NpmPackageManagerStarted
{
    public function __construct(private NpmPackageManager $manager)
    {
    }

    public function comment(Command $command, string $consoleComment): NpmPackageManagerStarted|NpmPackageManagerUpdated|NpmPackageManagerInstalled
    {
        $command->comment($consoleComment);

        return $this;
    }

    public function comments(Command $command, array $consoleComments): NpmPackageManagerStarted|NpmPackageManagerUpdated|NpmPackageManagerInstalled
    {
        foreach ($consoleComments as $comment) {
            $command->comment($comment);
        }

        return $this;
    }

    public function updatePackagesFile(bool $dev = true): NpmPackageManagerUpdated
    {
        if (!file_exists(base_path('package.json'))) {
            throw new Exception('Blurred Image exception: [package.json] file ins\'t found.');
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), associative: true);

        $packages[$configurationKey] = array_merge(
            static::$packagesToAdd,
            Arr::except(
                array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
                static::$packagesToRemove,
            ),
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );

        return new NpmPackageManagerUpdatedImpl($this->manager);
    }
}
