<?php

namespace GoodMaven\BlurredImage\Support;

use Illuminate\Support\Facades\File;

class ComposerPackageManager
{
    public static function isPackageInstalled(string $key, $dev = false): bool
    {
        $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);

        $packageType = $dev ? 'require-dev' : 'require';

        return isset($composerJson[$packageType][$key]);
    }

    public static function isPackageAssetsAvailable(string $kebabName): bool
    {
        if (File::exists(base_path("config/$kebabName.php"))
            || File::exists(base_path("resources/views/vendor/$kebabName"))
            || File::exists(base_path("public/vendor/$kebabName"))
        ) {
            return true;
        }

        return false;
    }
}
