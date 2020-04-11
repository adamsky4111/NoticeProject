<?php

namespace App\Services;

use App\Services\Interfaces\UploadFilesServiceInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class UploadFilesService implements UploadFilesServiceInterface
{

    public function uploadFiles($files, $imgDirectory)
    {
        $fileNames = [];
        foreach ($files as $file) {
            $fileNames[] = $this->uploadFile($file, $imgDirectory);
        }

        return $this->filesToString($fileNames);
    }

    public function uploadFile(File $file, $imgDirectory)
    {
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

        try {
            $file->move(
                $imgDirectory,
                $fileName
            );
        } catch (FileException $e) {

            throw $e;
        }

        return $fileName;
    }

    function filesToString($stringFiles)
    {
        return implode($stringFiles, ';');
    }

    function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}