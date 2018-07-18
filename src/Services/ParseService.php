<?php

namespace App\Services;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Output\OutputInterface;

class ParseService
{
    const PATTERN = '/^[\t ]*public(\s+static)?\s+function\s+([\w]+)\(.*$/m';

    protected $output;

    public function findAndParseFiles(OutputInterface $output)
    {
        $this->output = $output;
        $finder = new Finder();
        $files = $finder->files()->in('repositories/')->name('*.php');
        foreach ($files as $file) {
            $this->getMethods($file);
        }
    }

    public function getMethods($file)
    {
        /** @var $file \Symfony\Component\Finder\SplFileInfo */
        $file->getRelativePathname();
        $file->getFilename();
        $allMethod = $this->parse($file->getContents())[2];
        foreach ($allMethod as $method) {
            $this->output->writeln("\t<fg=yellow>"."{$method}</>");
        }
    }

    public function parse(string $content): array
    {
        preg_match_all(self::PATTERN, $content, $matches);

        return $matches;
    }
}