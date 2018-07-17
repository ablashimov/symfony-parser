<?php

namespace App\Services;

use GuzzleHttp\Client;

class DownloadService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * DownloadService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function download($url): string
    {
        $response = $this->client->request('get', $url, [
//            'progress' => [$this, 'onProgress'],
        ]);

        return $response->getBody()->getContents();
    }
}