<?php

namespace App\Commands;

use App\Services\ParseService;
use App\Services\UnzipService;
use App\Services\DownloadService;
use App\Services\FileWriterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Parse extends Command
{
    /** @var ProgressBar */
    protected $progressBar;

    /** @var OutputInterface */
    protected $output;

    /** @var urls */
    protected $urls;

    /** @var DownloadService */
    private $downloader;

    /** @var FileWriterService */
    private $writer;

    /** @var UnzipService */
    private $unzip;

    /** @var ParseService */
    private $parse;

    public function __construct($name = null, DownloadService $downloader, FileWriterService $writer, UnzipService $unzip, ParseService $parse)
    {
        parent::__construct();
        $this->downloader = $downloader;
        $this->writer = $writer;
        $this->unzip = $unzip;
        $this->parse = $parse;
    }

    public function configure(): void
    {
        $this->setName('parse-repositories')
            ->setDescription('Downloads repository from github')
            ->addArgument('urls', InputArgument::IS_ARRAY, 'Urls to download');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->output = $output;
        $this->urls = $input->getArgument('urls');
        foreach ($this->urls as $url) {
            $filename = str_replace('/', '-', $url);
            $zipPath = $this->writer->writeToFile($filename, $this->downloader->download($this->getUrl($url),[$this, 'onProgress']));
            $this->unzip->extractZip($zipPath);
            unlink($zipPath);
            $this->parse->findAndParseFiles($this->output);
        }
    }

    public function getUrl(string $url): string
    {
        return $url = 'https://github.com/' . $url . '/zipball/master';
    }


    public function onProgress(int $total, int $downloaded): void
    {
        if ($total <= 0) {
            return;
        }

        if (!$this->progressBar) {
            $this->progressBar = $this->createProgressBar(100);
        }

        $this->progressBar->setProgress(100 / $total * $downloaded);
    }

    public function createProgressBar(int $max): ProgressBar
    {
        $bar = new ProgressBar($this->output, $max);

        $bar->setBarCharacter('<fg=green>·</>');
        $bar->setEmptyBarCharacter('<fg=red>·</>');
        $bar->setProgressCharacter('<fg=green>ᗧ</>');
        $bar->setFormat("%current:8s%/%max:-8s% %bar% %percent:5s%% %elapsed:7s%/%estimated:-7s% %memory%");

        return $bar;
    }

}
