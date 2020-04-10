<?php

namespace App\Services\Interfaces;

interface UploadFilesServiceInterface
{
    public function uploadFiles($files);
    public function uploadFile($file);
}