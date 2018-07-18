<?php

namespace App\Services;

class FileWriterService
{
    const DOWNLOADS_PATH = 'storage';

    public function writeToFile(string $filename, string $content): string
    {
        $path = $this->getPath($filename);
        if (!file_exists($dir = dirname($path))) {
            mkdir($dir);
        }
        if (file_put_contents($path, $content) === false) {
            throw new \RuntimeException('File was not written');
        }

        return $path;
    }

    private function getPath(string $filename): string
    {
        return static::DOWNLOADS_PATH . $filename;
    }
}