<?php


namespace App\Service;


class ChatTools
{
    private BTTVApi $bt;
    private TwitchGQL $tgql;

    /**
     * ToolsController constructor.
     * @param BTTVApi $bt
     * @param TwitchGQL $tgql
     */
    public function __construct(BTTVApi $bt, TwitchGQL $tgql)
    {
        $this->bt = $bt;
        $this->tgql = $tgql;
    }


    public function blockBttvEmotes(string $channel)
    {
        $emotes = $this->bt->getEmotes($channel);
        $currentBlockedTerms = $this->tgql->getBlockedTerms($channel);
        foreach ($emotes as $emote) {
            if (!in_array($emote['code'], $currentBlockedTerms)) {
                $this->tgql->addBlockedTerm($channel, $emote['code']);
            }
        }
    }

    public function unblockBttvEmotes(string $channel)
    {
        $emotes = $this->bt->getEmotes($channel);
        foreach ($emotes as $emote) {
            $this->tgql->deleteBlockedTerm($channel, $emote['code']);
        }
    }

}