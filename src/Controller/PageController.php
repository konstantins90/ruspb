<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/page')]
class PageController extends AbstractController
{
    #[Route('/impressum', name: 'app_page_impressum', methods: ['GET'])]
    public function impressum(): Response
    {
        return $this->render('page/impressum.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/agb', name: 'app_page_agb', methods: ['GET'])]
    public function agb(): Response
    {
        return $this->render('page/agb.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/kontakt', name: 'app_page_kontakt', methods: ['GET'])]
    public function kontakt(): Response
    {
        return $this->render('page/kontakt.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/wir', name: 'app_page_wir', methods: ['GET'])]
    public function wir(): Response
    {
        return $this->render('page/wir.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
