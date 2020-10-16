<?php

namespace App\Controller;

use App\Service\BTTVApi;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BTTVController extends AbstractFOSRestController
{
    private BTTVApi $bt;

    /**
     * BTTVController constructor.
     * @param BTTVApi $bt
     */
    public function __construct(BTTVApi $bt)
    {
        $this->bt = $bt;
    }


    /**
     * @Route("/bttv/{channel}/emotes", name="view_bttv_emotes", requirements={"channel"="^(#)?[a-zA-Z0-9]{4,25}$"}, methods={"GET"})
     * @Rest\View()
     * @param string $channel
     * @return JsonResponse
     */
    public function getEmotes(string $channel)
    {
        return $this->json($this->bt->getEmotes($channel));
    }


}