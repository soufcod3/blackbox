<?php

namespace App\Service;

use App\Entity\Series;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getPopularity(Series $series): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/search/tv?api_key=ec6e22ffc2f15b1a8f64249691a780c9&language=fr&query='. str_replace('-', '+' ,$series->getSlug())
            // modèle = https://api.themoviedb.org/3/search/movie?api_key={api_key}&query=Jack+Reacher
        );

        return $response->toArray()['results'][0];
    }
}