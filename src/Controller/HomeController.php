<?php

namespace App\Controller;

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
                $this->addFlash(
                    'success',
                    'Merci ! Votre message a bien été envoyé. Je vous répondrai dès que possible.'
                );
            } catch (TransportExceptionInterface) {
                $this->addFlash(
                    'error',
                    'L’envoi du message a échoué. Réessayez plus tard ou écrivez-moi directement à l’adresse indiquée dans le pied de page.'
                );
            }

            return $this->redirect($this->generateUrl('app_home').'#contact');
        }

        return $this->render('home/index.html.twig', [
            'contact_form' => $form,
            'projects' => $this->projects(),
            'skills' => $this->skills(),
            'flagship_pillars' => $this->flagshipPillars(),
            'process_steps' => $this->processSteps(),
            'faqs' => $this->faqs(),
            'trust_points' => $this->trustPoints(),
            'why_artisan' => $this->whyArtisanWeb(),
            'client_skills' => $this->clientSkills(),
        ]);
    }

    /**
     * @return list<array{title: string, meta: string, image: string, url: string, badge?: string, badge_class?: string, image_fit?: string, image_alt?: string}>
     */
    private function projects(): array
    {
        return [
            [
                'title' => 'MyHealthDay',
                'badge' => 'Application iOS',
                'meta' => 'SwiftUI, HealthKit, Combine · Tâches quotidiennes, suivi santé (pas, poids, graphiques) et recettes minceur avec filtres.',
                'image' => 'images/myhealthday-icon.png',
                'image_alt' => 'Logo MyHealthDay — application iOS',
                'image_fit' => 'contain',
                'url' => $this->generateUrl('app_project_myhealthday'),
            ],
            [
                'title' => 'TimeTravel Agency',
                'badge' => 'Projet de cours (M1/M2)',
                'badge_class' => 'project-badge--course',
                'meta' => 'React, Vite, Tailwind, Framer Motion, API Mistral · Webapp « agence de voyages dans le temps » : landing, destinations, quiz, chatbot. Contrainte du module : utiliser l’IA de A à Z (conception, code, contenus).',
                'image' => 'https://placehold.co/800x500/1e1033/e9d5ff?text=TimeTravel+Agency',
                'url' => $this->generateUrl('app_project_timetravel'),
            ],
            [
                'title' => 'Football Manager Lite',
                'badge' => 'Projet perso',
                'badge_class' => 'project-badge--personal',
                'meta' => 'React, TypeScript, Vite · Mini jeu de management football : ligue à 12 équipes, calendrier aller-retour, moteur de match simplifié, vues tableau de bord / effectif / calendrier / jour de match, sauvegarde localStorage.',
                'image' => 'https://placehold.co/800x500/14532d/bef264?text=FM+Lite',
                'url' => $this->generateUrl('app_project_football_lite'),
            ],
            [
                'title' => 'Lyon Alerte 360',
                'badge' => 'Projet de cours · Full-stack',
                'badge_class' => 'project-badge--course',
                'meta' => 'Réalisé en formation. React, Tailwind, Node.js, Express · Alertes et infos pour Lyon : notifications temps réel (météo, séismes, crues), carte, activités & événements, chat global.',
                'image' => 'https://placehold.co/800x500/9a3412/ffedd5?text=Lyon+Alerte+360',
                'url' => $this->generateUrl('app_project_lyon_alerte'),
            ],
        ];
    }

    /**
     * @return list<array{icon: string, title: string, subtitle: string|null, items: list<string>}>
     */
    private function flagshipPillars(): array
    {
        return [
            [
                'icon' => 'fa-solid fa-compass-drafting',
                'title' => 'Conception & design',
                'subtitle' => null,
                'items' => [
                    'Architecture one-page (navigation fluide)',
                    '100 % responsive (mobile first)',
                    'Vitesse d’affichage optimisée',
                    'Sécurité HTTPS (certificat SSL inclus)',
                ],
            ],
            [
                'icon' => 'fa-solid fa-layer-group',
                'title' => 'Contenu stratégique',
                'subtitle' => 'Les 5 blocs',
                'items' => [
                    'Hero : accroche percutante & appel à l’action clair',
                    'Liste d’expertises détaillée',
                    'Galerie de réalisations (6 photos)',
                    'Bloc réassurance (garanties, diplômes, labels)',
                    'Contact : formulaire + plan Google Maps',
                ],
            ],
            [
                'icon' => 'fa-solid fa-chart-line',
                'title' => 'Visibilité & référencement',
                'subtitle' => null,
                'items' => [
                    'SEO local (métier + ville)',
                    'Configuration Google Business Profile (Maps)',
                    'Indexation via Google Search Console',
                ],
            ],
            [
                'icon' => 'fa-solid fa-shield-halved',
                'title' => 'Forfait sérénité',
                'subtitle' => '150 € / an',
                'items' => [
                    'Hébergement & nom de domaine inclus',
                    'Maintenance & mises à jour techniques',
                    'Modifications mineures incluses toute l’année',
                ],
            ],
        ];
    }

    /**
     * @return list<array{step: string, title: string, text: string}>
     */
    private function processSteps(): array
    {
        return [
            [
                'step' => '01',
                'title' => 'Échange & cadrage',
                'text' => 'Appel ou visio gratuit pour comprendre votre activité, vos clients et vos priorités (24–48 h de réponse visée).',
            ],
            [
                'step' => '02',
                'title' => 'Proposition écrite',
                'text' => 'Devis détaillé : périmètre, délais indicatifs, modalités. Aucune surprise sur le périmètre convenu.',
            ],
            [
                'step' => '03',
                'title' => 'Conception & validation',
                'text' => 'Maquette ou structure de pages selon le pack ; vos retours sont intégrés avant mise en ligne.',
            ],
            [
                'step' => '04',
                'title' => 'Mise en ligne & passation',
                'text' => 'Publication, tests sur mobile, et si prévu une prise en main rapide pour vos futurs ajustements.',
            ],
        ];
    }

    /**
     * @return list<array{q: string, a: string}>
     */
    private function faqs(): array
    {
        return [
            [
                'q' => 'Travaillez-vous uniquement sur Lyon ?',
                'a' => 'Je suis basé autour de Lyon et travaille surtout avec des clients en Auvergne-Rhône-Alpes, mais les échanges à distance (visio, documents partagés) fonctionnent très bien pour un site vitrine.',
            ],
            [
                'q' => 'Combien de temps pour un site vitrine ?',
                'a' => 'Pour l’offre site vitrine clé en main, comptez en général 2 à 5 semaines selon vos contenus. Les délais précis sont indiqués sur le devis.',
            ],
            [
                'q' => 'Qui paie le nom de domaine et l’hébergement ?',
                'a' => 'Souvent les abonnements sont à votre nom (vous restez propriétaire). Je vous guide pour les souscrire ou je peux les intégrer au périmètre sur devis.',
            ],
            [
                'q' => 'Puis-je modifier le site moi-même après ?',
                'a' => 'Oui, selon la solution retenue : je peux prévoir une courte formation ou une documentation simple pour mettre à jour textes et images sans tout casser.',
            ],
            [
                'q' => 'Et les mentions légales & le RGPD ?',
                'a' => 'Je vous oriente vers des modèles adaptés et l’essentiel à afficher (coordonnées, hébergeur, politique de confidentialité si formulaire). Pour un avis juridique contraignant, un professionnel du droit reste la référence.',
            ],
        ];
    }

    /**
     * @return list<array{icon: string, title: string, text: string}>
     */
    private function trustPoints(): array
    {
        return [
            [
                'icon' => 'fa-solid fa-file-signature',
                'title' => 'Devis transparent',
                'text' => 'Périmètre et livrables posés par écrit avant démarrage.',
            ],
            [
                'icon' => 'fa-solid fa-mobile-screen',
                'title' => 'Mobile d’abord',
                'text' => 'Vos clients vous trouvent sur téléphone ; le site est pensé pour ça.',
            ],
            [
                'icon' => 'fa-solid fa-handshake',
                'title' => 'Relation directe',
                'text' => 'Un interlocuteur unique — pas d’agence opaque ni de sous-traitance cachée.',
            ],
        ];
    }

    /**
     * @return array{intro: string, points: list<array{icon: string, title: string, text: string}>}
     */
    private function whyArtisanWeb(): array
    {
        return [
            'intro' => 'Accompagnement de A à Z : de la réservation de votre nom de domaine à la mise en ligne, je m’occupe de toute la partie technique.',
            'points' => [
                [
                    'icon' => 'fa-solid fa-fingerprint',
                    'title' => 'Indépendance numérique',
                    'text' => 'Ne dépendez plus uniquement du bouche-à-oreille ou des plateformes tierces. Soyez propriétaire de votre image.',
                ],
                [
                    'icon' => 'fa-solid fa-images',
                    'title' => 'Preuve de qualité',
                    'text' => 'Centralisez vos plus belles réalisations et vos avis clients pour rassurer vos futurs chantiers instantanément.',
                ],
                [
                    'icon' => 'fa-solid fa-map-location-dot',
                    'title' => 'Visibilité locale 24h/7',
                    'text' => 'Permettez aux clients de votre secteur de vous trouver sur Google, même quand vous n’êtes pas joignable.',
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    private function clientSkills(): array
    {
        return [
            'Sites vitrines & pages de présentation',
            'Référencement local de base (titres, textes, vitesse)',
            'Formulaires de contact & emails transactionnels',
            'Hébergement, nom de domaine & mise en ligne',
        ];
    }

    /**
     * @return list<array{icon: string, label: string}>
     */
    private function skills(): array
    {
        return [
            ['icon' => 'fa-brands fa-html5', 'label' => 'HTML'],
            ['icon' => 'fa-brands fa-css3-alt', 'label' => 'CSS'],
            ['icon' => 'fa-brands fa-js', 'label' => 'JavaScript'],
            ['icon' => 'fa-solid fa-code', 'label' => 'TypeScript'],
            ['icon' => 'fa-brands fa-react', 'label' => 'React'],
            ['icon' => 'fa-brands fa-node-js', 'label' => 'Node.js'],
            ['icon' => 'fa-brands fa-php', 'label' => 'PHP'],
            ['icon' => 'fa-brands fa-symfony', 'label' => 'Symfony'],
            ['icon' => 'fa-brands fa-swift', 'label' => 'Swift / SwiftUI'],
            ['icon' => 'fa-solid fa-database', 'label' => 'Bases de données'],
            ['icon' => 'fa-solid fa-pen-ruler', 'label' => 'UI / UX'],
        ];
    }
}
