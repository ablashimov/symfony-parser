<?php

namespace App\Services;

use Symfony\Component\Finder\Finder;

class ParseService
{
    public function findAndParseFiles($output)
    {
        $finder = new Finder();
        $files = $finder->files()->in('repositories/')->name('*.php');
        foreach ($files as $file) {
            /** @var $file \Symfony\Component\Finder\SplFileInfo */
            $file->getRelativePathname();
            $file->getFilename();
            $allMethod = $this->parse($file->getContents())[2];
            //var_dump($file->getFilename(),$this->parse($file->getContents())[2]);
            foreach ($allMethod as $method) {
                $output->writeln("\t<fg=yellow>" . $method . '</>');
            }
        }
    }

    public function parse(string $content): array
    {
        $pattern = '/^[\t ]*public(\s+static)?\s+function\s+([\w]+)\(.*$/m';
        preg_match_all($pattern, $content, $matches);
        return $matches;
    }
}