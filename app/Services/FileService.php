<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Exception;

class FileService
{
    private $file;

    public function __construct(File $f) {
        $this->file = $f;
    }

    public function deleteFolder($link) {
        try {
            File::deleteDirectory($link);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}