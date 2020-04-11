<?php

namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\File\File;

interface UploadFilesServiceInterface
{
    public function uploadFiles($files, $imgDirectory);

    public function uploadFile(File $file, $imgDirectory);
}