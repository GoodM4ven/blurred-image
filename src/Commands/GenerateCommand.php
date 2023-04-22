<?php

namespace GoodMaven\BlurredImage\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as ImageManager;

class GenerateCommand extends Command
{
    public $signature = 'blurred-image:generate {path : of the existing image path}';

    public $description = 'Generate a blurrable thumbnail for an image.';

    public function handle(): int
    {
        $this->comment('Validating...');
        
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error('Image file does not exist.');
            return self::FAILURE;
        }

        $image = ImageManager::make($path);

        if (!$image->mime() || !in_array($image->mime(), ['image/jpeg', 'image/png', 'image/gif'])) {
            $this->error('The given file is not an image.');
            return self::FAILURE;
        }

        if ($image->width() < 208 || $image->height() < 117) {
            $this->error('The image is too small.');
            return self::FAILURE;
        }

        $this->comment('Generating...');
        
        $thumbnail = $image->fit(208, 117, function ($constraint) {
            $constraint->upsize();
        });

        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $newFilename = "{$filename}-thumbnail.{$extension}";
        $thumbnailPath = pathinfo($path, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $newFilename;

        $thumbnail->save($thumbnailPath);

        $this->info("A blurrable image thumbnail has been generated successfully at: [{$thumbnailPath}].");

        return self::SUCCESS;
    }
}
