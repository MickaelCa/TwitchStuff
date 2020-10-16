<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class BTTVApi
{
    const CHANNEL_EMOTE_ENDPOINT = 'https://api.betterttv.net/2/channels/';

    private HttpClientInterface $h;

    /**
     * BTTVApi constructor.
     * @param HttpClientInterface $h
     */
    public function __construct(HttpClientInterface $h)
    {
        $this->h = $h;
    }


    public function getEmotes(string $channel): array
    {
        $response = $this->h->request('GET', self::CHANNEL_EMOTE_ENDPOINT . $channel);
        $result = json_decode($response->getContent(), true);
        return $result['emotes'];
    }

}