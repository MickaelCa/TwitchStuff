<?php

namespace App\Controller;

use App\Service\TwitchGQL;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TwitchBlockedTermsController extends AbstractFOSRestController
{

    public SerializerInterface $s;
    public TwitchGQL $tgql;

    /**
     * TwitchBlockedTermsController constructor.
     * @param SerializerInterface $s
     * @param TwitchGQL $tgql
     */
    public function __construct(SerializerInterface $s, TwitchGQL $tgql)
    {
        $this->s = $s;
        $this->tgql = $tgql;
    }

    /**
     * @Route("/blocked-terms/{channel}", name="view_twitch_blocked_terms")
     * @Rest\View()
     * @param string $channel
     * @return JsonResponse
     */
    public function listBlockedTerms(string $channel)
    {
        $data = $this->tgql->getBlockedTerms($channel);

        return $this->json($data);
    }

    /**
     * @Route("/ablocked-terms/{channel}", name="add_twitch_blocked_terms")
     * @Rest\Post()
     * @param string $channel
     * @return JsonResponse
     */
    public function addBlockedTerm(string $channel)
    {
        $r = $this->tgql->addBlockedTerm($channel, "salutestaurevoir");

        return $this->json(dump($r));
    }

}
