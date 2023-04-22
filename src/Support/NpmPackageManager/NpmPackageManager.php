<?php

namespace GoodMaven\BlurredImage\Support\NpmPackageManager;

use GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts\NpmPackageManagerStart;
use GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts\NpmPackageManagerStarted;
use GoodMaven\BlurredImage\Support\NpmPackageManager\Implementations\NpmPackageManagerStartedImpl;

class NpmPackageManager implements NpmPackageManagerStart
{
    public static array $packagesToAdd = [];

    public static array $packagesToRemove = [];

    public static function checkIfPackageInstalled(string $key, bool $dev = false): bool
    {
        $json = json_decode(file_get_contents(base_path('package.json')), true);

        return array_key_exists($key, $json[$dev ? 'devDependencies' : 'dependencies']);
    }

    public static function checkForMissingPackages(array $keys, bool $dev = false): array
    {
        $json = json_decode(file_get_contents(base_path('package.json')), true);
        $missingKeys = [];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $json[$dev ? 'devDependencies' : 'dependencies'])) {
                $missingKeys[] = $key;
            }
        }

        return $missingKeys;
    }
    
    public static function make(): NpmPackageManagerStart
    {
        return new self();
    }

    public function addPackages(array $keysAndVersions, bool $replaceInstead = false): NpmPackageManagerStarted
    {
        if ($replaceInstead) {
            static::$packagesToAdd = $keysAndVersions;
        }

        static::$packagesToAdd = array_merge(static::$packagesToAdd, $keysAndVersions);

        return new NpmPackageManagerStartedImpl($this);
    }

    public function addToRemovePackages(array $keys, bool $replaceInstead = false): NpmPackageManagerStarted
    {
        if ($replaceInstead) {
            static::$packagesToRemove = $keys;
        }

        static::$packagesToRemove = array_merge(static::$packagesToRemove, $keys);

        return new NpmPackageManagerStartedImpl($this);
    }
}
