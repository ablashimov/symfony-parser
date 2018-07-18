<?php

namespace App\Services;

class FileWriterService
{
    public function writeToFile(string $filename, string $content): string
    {
        $path = $filename;
        if (!file_exists($dir = dirname($path))) {
            mkdir($dir);
        }
        if (file_put_contents($path, $content) === false) {
            throw new \RuntimeException('File was not written');
        }

        return $path;
    }
}