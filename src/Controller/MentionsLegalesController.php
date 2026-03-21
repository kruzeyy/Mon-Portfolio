<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MentionsLegalesController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_mentions_legales', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('legal/mentions.html.twig');
    }
}
