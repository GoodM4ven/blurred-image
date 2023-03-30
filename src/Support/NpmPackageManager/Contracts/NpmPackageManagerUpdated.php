<?php

namespace GoodMaven\BlurredImage\Support\NpmPackageManager\Contracts;

use Illuminate\Console\Command;

interface NpmPackageManagerUpdated
{
    public function comment(Command $command, string $consoleComment): NpmPackageManagerStarted|NpmPackageManagerUpdated|NpmPackageManagerInstalled;
    public function comments(Command $command, array $consoleComments): NpmPackageManagerStarted|NpmPackageManagerUpdated|NpmPackageManagerInstalled;
    public function installPackages(Command $command): NpmPackageManagerInstalled;
}
