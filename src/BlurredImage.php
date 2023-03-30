<?php

namespace GoodMaven\BlurredImage;

use Symfony\Component\Process\Process;

class BlurredImage
{
    public function generate(string $existingImagePath): void
    {
        $process = new Process(['php', 'artisan', 'blurred-image:generate', $existingImagePath]);

        if (!$process->isSuccessful()) {
            throw new \Exception("Blurred Image exception: Blurrable image generation has failed. Here is the output: {$process->getErrorOutput()}");
        }
    }
}
