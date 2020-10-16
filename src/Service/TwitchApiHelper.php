<?php


namespace App\Service;


use Error;
use TwitchApi\Exceptions\ClientIdRequiredException;
use TwitchApi\Exceptions\InvalidLimitException;
use TwitchApi\Exceptions\InvalidOffsetException;
use TwitchApi\Exceptions\InvalidTypeException;
use TwitchApi\Exceptions\TwitchApiException;
use TwitchApi\TwitchApi;

/**
 * Class TwitchApiWrapper
 * @package App\Service
 */
class TwitchApiHelper
{

    private TwitchApi $t;
    private array $channelIdCache = [];

    /**
     * TwitchApiHelper constructor.
     * @param TwitchApi $t
     */
    public function __construct(TwitchApi $t)
    {
        $this->t = $t;
    }

    /**
     * @param string $channel
     * @return string
     * @throws InvalidLimitException
     * @throws InvalidOffsetException
     * @throws InvalidTypeException
     * @throws TwitchApiException
     */
    public function getChannelId(string $channel): string
    {
        $r = $this->t->searchChannels($channel);
        if ($r['_total'] > 0 && $r['channels'][0]['name'] == strtolower($channel)) {
            $this->channelIdCache[$channel] = (string)$r['channels'][0]['_id'];
            return $this->channelIdCache[$channel];
        } else {
            throw new Error('Channel ' . $channel . ' not found');
        }
    }


}