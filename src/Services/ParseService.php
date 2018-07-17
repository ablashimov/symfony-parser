<?php

namespace App\Services;

use Symfony\Component\Finder\Finder;

class ParseService
{
    public function findAndParseFiles()
    {
        $finder = new Finder();
        $files = $finder->files()->in('repositories/')->name('*.php');
        foreach ($files as $file) {
            /** @var $file \Symfony\Component\Finder\SplFileInfo */
            $file->getRealPath();
            $file->getFilename();
            var_dump($file->getFilename(), $this->parse($file->getContents())[2]);
        }
    }

    public function parse($content)
    {
        $pattern = '/^[\t ]*public(\s+static)?\s+function\s+([\w]+)\(.*$/m';
        preg_match_all($pattern, $content, $matches);
        return $matches;
    }
}