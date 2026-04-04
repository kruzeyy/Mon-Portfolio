<?php

namespace App\Controller;

use App\DataProvider\PortfolioDataProvider;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly PortfolioDataProvider $data,
        #[Autowire('%env(MAILER_FROM)%')]
        private readonly string $mailerFrom,
        #[Autowire('%env(CONTACT_TO)%')]
        private readonly string $contactTo,
    ) {
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{name: string, email: string, message: string} $data */
            $data = $form->getData();

            $email = (new Email())
                ->from($this->mailerFrom)
                ->to($this->contactTo)
                ->replyTo($data['email'])
                ->subject(sprintf('[Contact site] %s', $data['name']))
                ->text(sprintf(
                    "Nom : %s\nEmail : %s\n\n%s",
                    $data['name'],
                    $data['email'],
                    $data['message']
                ));

            try {
                $this->mailer->send($email);
                $this->addFlash('success', 'Merci ! Votre message a bien été envoyé. Je vous répondrai dès que possible.');
            } catch (TransportExceptionInterface) {
                $this->addFlash('error', 'L'envoi du message a échoué. Réessayez plus tard ou écrivez-moi directement à l'adresse indiquée dans le pied de page.');
            }

            return $this->redirect($this->generateUrl('app_home').'#contact');
        }

        return $this->render('home/index.html.twig', [
            'contact_form'     => $form,
            'projects'         => $this->data->projects(),
            'skills'           => $this->data->skills(),
            'client_skills'    => $this->data->clientSkills(),
            'trust_points'     => $this->data->trustPoints(),
            'flagship_pillars' => $this->data->flagshipPillars(),
            'process_steps'    => $this->data->processSteps(),
            'faqs'             => $this->data->faqs(),
            'why_artisan'      => $this->data->whyArtisan(),
        ]);
    }
}
