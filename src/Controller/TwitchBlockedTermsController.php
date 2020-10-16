<?php

namespace App\Controller;

use App\Service\TwitchGQL;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TwitchBlockedTermsController extends AbstractFOSRestController
{

    private TwitchGQL $tgql;

    /**
     * TwitchBlockedTermsController constructor.
     * @param TwitchGQL $tgql
     */
    public function __construct(TwitchGQL $tgql)
    {
        $this->tgql = $tgql;
    }

    /**
     * @Route("/blocked-terms/{channel}/list", name="view_twitch_blocked_terms", requirements={"channel"="^(#)?[a-zA-Z0-9]{4,25}$"}, methods={"GET"})
     * @Rest\View()
     * @param string $channel
     * @return JsonResponse
     */
    public function getBlockedTerms(string $channel)
    {
        return $this->json($this->tgql->getBlockedTerms($channel));
    }

    /**
     * @Route("/blocked-terms/{channel}", name="add_twitch_blocked_terms", requirements={"channel"="^(#)?[a-zA-Z0-9]{4,25}$"}, methods={"POST"})
     * @Rest\Post()
     * @Rest\RequestParam(name="term", description="term")
     * @param string $channel
     * @param ParamFetcherInterface $paramFetcher
     * @return JsonResponse
     */
    public function postBlockedTerm(string $channel, ParamFetcherInterface $paramFetcher)
    {
        return $this->json($this->tgql->addBlockedTerm($channel, $paramFetcher->get('term')));
    }

    /**
     * @Route("/blocked-terms/{channel}/delete", name="delete_twitch_blocked_terms", requirements={"channel"="^(#)?[a-zA-Z0-9]{4,25}$"}, methods={"POST"})
     * @Rest\Post ()
     * @Rest\RequestParam(name="term", description="term")
     * @param string $channel
     * @param ParamFetcherInterface $paramFetcher
     * @return JsonResponse
     */
    public function deleteBlockedTerm(string $channel, ParamFetcherInterface $paramFetcher)
    {
        return $this->json($this->tgql->deleteBlockedTerm($channel, $paramFetcher->get('term')));
    }

}
