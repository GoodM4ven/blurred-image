<?php

namespace GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts;

use Illuminate\Console\Command;

interface NpmPackageManagerStarted
{
    public function addPackages(array $keysAndVersions, bool $replaceInstead = false): NpmPackageManagerStarted;
    public function addToRemovePackages(array $keys, bool $replaceInstead = false): NpmPackageManagerStarted;
    public function comment(Command $command, string $consoleComment): NpmPackageManagerStarted|NpmPackageManagerUpdated|NpmPackageManagerInstalled;
    public function comments(Command $command, array $consoleComments): NpmPackageManagerStarted|NpmPackageManagerUpdated|NpmPackageManagerInstalled;
    public function updatePackagesFile(bool $dev = true): NpmPackageManagerUpdated;
}
