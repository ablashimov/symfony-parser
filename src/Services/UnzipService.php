<?php

namespace App\Services;

use ZipArchive;

class UnzipService
{
    protected $path = 'repositories/';

    public function extractZip(string $filename): void
    {
        $zip = new ZipArchive();
        if ($zip->open($filename) !== true) {
            throw new \RuntimeException('Archive was not found');
        }

        $zip->extractTo($this->path);
        $zip->close();

        return;
    }
}