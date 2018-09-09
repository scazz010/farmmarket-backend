<?php

namespace App\Images;

use Symfony\Component\HttpFoundation\File\File;

interface ImageManipulator {

    public function asThumbnail(File $originalImage);


}