<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/projets/my-healthday', name: 'app_project_myhealthday')]
    public function myHealthDay(): Response
    {
        return $this->render('project/my_healthday.html.twig');
    }

    #[Route('/projets/time-travel-agency', name: 'app_project_timetravel')]
    public function timeTravelAgency(): Response
    {
        return $this->render('project/time_travel_agency.html.twig');
    }

    #[Route('/projets/football-manager-lite', name: 'app_project_football_lite')]
    public function footballManagerLite(): Response
    {
        return $this->render('project/football_manager_lite.html.twig');
    }

    #[Route('/projets/lyon-alerte-360', name: 'app_project_lyon_alerte')]
    public function lyonAlerte360(): Response
    {
        return $this->render('project/lyon_alerte_360.html.twig');
    }
}
