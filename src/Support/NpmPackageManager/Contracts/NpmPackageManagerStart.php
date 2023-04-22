<?php

namespace GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts;

interface NpmPackageManagerStart
{
    public function addPackages(array $keysAndVersions, bool $replaceInstead = false): NpmPackageManagerStarted;
    public function addToRemovePackages(array $keys, bool $replaceInstead = false): NpmPackageManagerStarted;
}
