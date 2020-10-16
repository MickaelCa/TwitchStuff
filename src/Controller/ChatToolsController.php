<?php

namespace App\Controller;

use App\Service\BTTVApi;
use App\Service\ChatTools;
use App\Service\TwitchGQL;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatToolsController extends AbstractFOSRestController
{
    private ChatTools $ct;

    /**
     * ChatToolsController constructor.
     * @param ChatTools $ct
     */
    public function __construct(ChatTools $ct)
    {
        $this->ct = $ct;
    }


    /**
     * @Route("/tools/{channel}/block-bttv-emotes", name="tools_block_bttv_emotes", requirements={"channel"="^(#)?[a-zA-Z0-9]{4,25}$"}, methods={"GET"})
     * @Rest\View()
     * @param string $channel
     * @return JsonResponse
     */
    public function blockBttvEmotes(string $channel)
    {
        $this->ct->blockBttvEmotes($channel);
        return $this->json('SeemsGood');
    }

    /**
     * @Route("/tools/{channel}/unblock-bttv-emotes", name="tools_unblock_bttv_emotes", requirements={"channel"="^(#)?[a-zA-Z0-9]{4,25}$"}, methods={"GET"})
     * @Rest\View()
     * @param string $channel
     * @return JsonResponse
     */
    public function unblockBttvEmotes(string $channel)
    {
        $this->ct->unblockBttvEmotes($channel);
        return $this->json('SeemsGood');
    }


}