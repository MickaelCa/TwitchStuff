<?php


namespace App\Service;


use Error;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TwitchApi\TwitchApi;

class TwitchGQL
{

    const GQL_ENDPOINT = 'https://gql.twitch.tv/gql#origin=twilight';

    private TwitchApiHelper $twh;
    private TwitchApi $twa;
    private HttpClientInterface $h;

    private string $gqlOauth;
    private string $gqlClientID;

    /**
     * TwitchGQL constructor.
     * @param TwitchApiHelper $twh
     * @param TwitchApi $twa
     * @param HttpClientInterface $h
     */
    public function __construct(TwitchApiHelper $twh, TwitchApi $twa, HttpClientInterface $h)
    {
        $this->twh = $twh;
        $this->twa = $twa;
        $this->h = $h;
    }


    /**
     * @param string $gqlOauth
     * @param string $gqlClientID
     */
    public function setCredentials(string $gqlOauth, string $gqlClientID): void
    {
        $this->gqlOauth = $gqlOauth;
        $this->gqlClientID = $gqlClientID;
    }

    /**
     * @param array $query
     * @return array
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    private function gqlQuery(array $query): array
    {
        try {
            $response = $this->h->request('POST', self::GQL_ENDPOINT, [
                'body' => json_encode($query),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'OAuth ' . $this->gqlOauth,
                    'Client-Id' => $this->gqlClientID,
                    'Content-Type' => 'text/plain;charset=UTF-8'
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new Error($e->getMessage());
        } finally {
            $responseData = json_decode($response->getContent(), true);
        }

        return $responseData;
    }


    public function getBlockedTerms(string $channel)
    {
        $query = [
            [
                "operationName" => "StandaloneChatTermsContainerQuery",
                "variables" => [
                    "channelLogin" => $channel
                ],
                "extensions" => [
                    "persistedQuery" => [
                        "version" => 1,
                        "sha256Hash" => "3fc85ffea475aa47b5b6d1445a08188dca9610dcf78bcd2dbb19fb1ce9368ef1"
                    ]
                ]
            ]
        ];

        $responseData = $this->gqlQuery($query);
        $blockedTerms = [];

        foreach ($responseData[0]['data']['user']['blockedTerms'] as $blockedTerm) {
            foreach ($blockedTerm['phrases'] as $phrase) {
                $blockedTerms[] = $phrase;
            }
        }

        return $blockedTerms;

    }

    public function removeBlockedTerm(string $channel, string $term)
    {

    }

    public function addBlockedTerm(string $channel, string $term)
    {

        $channelId = $this->twh->getChannelId($channel);

        $query = [
            [
                "operationName" => "Chat_StandaloneAddChannelBlockedTerm",
                "variables" => [
                    "input" => [
                        "channelID" => $channelId,
                        "phrases" => [$term],
                        "isModEditable" => true
                    ]
                ],
                "extensions" => [
                    "persistedQuery" => [
                        "version" => 1,
                        "sha256Hash" => "fa2d11ef5f0b4b5054204f872ebd4969378b987548a0b13e49e48c324a9297c8"
                    ]
                ]
            ]
        ];

        $result = $this->gqlQuery(dump($query));

        return $result;
    }
}