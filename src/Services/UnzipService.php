<?php

namespace App\Services;
use ZipArchive;


class UnzipService
{
    public function extractZip($filename)
    {
        $zip = new ZipArchive();
        if ($zip->open($filename) === true) {
            $zip->extractTo("repositories/");
            $zip->close();
            return;
        }

        throw new \RuntimeException('Archive was not found');
    }
}