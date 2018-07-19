<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class DownloadService
{
    /** @var Client */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function download(string $url, callable $progress): string
    {
        $response = $this->client->request('get', $url,['progress' => $progress]);

        return $response->getBody()->getContents();
    }
}