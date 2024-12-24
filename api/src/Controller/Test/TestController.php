<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Helper\AIHelper;
use App\Service\MistralAIService;
use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestController
 * @package App\Controller
 */
//#[AsController]
#[Route(
    path: '/test',
    name: 'test',
    methods: ['GET']
)]
class TestController extends AbstractController
{
    public function __construct(private OpenAIService $openAIService) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request, MistralAIService $mistralAIService): JsonResponse
    {
        $promptResult = $mistralAIService->prompt(AIHelper::promptForSWOT("Application de billetterie basée sur la les NFTs"));

        //         $promptResult = "# Strengths
        // 1. **Technologie Innovante**: L'utilisation de NFT offre une solution de réservation sécurisée et traçable, ce qui peut attirer une clientèle intéressée par les nouvelles technologies.
        // 2. **Sécurité Améliorée**: Les NFT offrent une meilleure sécurité des billets, réduisant ainsi les risques de fraude et de contrefaçon.
        // 3. **Traçabilité**: Les NFT permettent de suivre l'authenticité des billets tout au long du processus de réservation, offrant ainsi une expérience transparente aux utilisateurs.
        // 4. **Accessibilité Mondiale**: En utilisant une plateforme basée sur la blockchain, l'application peut être accessible à un large public à travers le monde, facilitant la réservation de billets pour des événements internationaux.

        // # Weaknesses
        // 1. **Complexité Technique**: La technologie NFT peut être difficile à comprendre pour les utilisateurs non familiers avec la blockchain, ce qui peut limiter l'adoption de l'application.
        // 2. **Dépendance à la Blockchain**: Des problèmes de congestion ou de frais élevés sur la blockchain peuvent entraîner des retards ou des coûts supplémentaires pour les utilisateurs.
        // 3. **Barrières d'Entrée pour les Utilisateurs**: La nécessité de posséder un portefeuille de crypto-monnaie et de comprendre le fonctionnement des NFT peut exclure certains utilisateurs moins technophiles.

        // # Opportunities
        // 1. **Partenariats Stratégiques**: Collaborer avec des organisateurs d'événements pour intégrer la technologie NFT dans leurs processus de billetterie peut ouvrir de nouvelles opportunités commerciales.
        // 2. **Expansion de Marché**: En offrant des billets sécurisés et traçables, l'application peut attirer de nouveaux marchés, y compris les événements sportifs, les concerts et les festivals.
        // 3. **Education sur la Blockchain**: Il existe une opportunité de sensibiliser et d'éduquer le grand public sur les avantages des NFT et de la blockchain, ce qui pourrait encourager une adoption plus large de l'application.

        // # Threats
        // 1. **Réglementation**: Les réglementations gouvernementales concernant les crypto-monnaies et la blockchain pourraient limiter le développement et l'adoption de l'application.
        // 2. **Concurrence**: D'autres applications de billetterie traditionnelles ou basées sur la blockchain pourraient émerger et offrir une concurrence féroce.
        // 3. **Risques de Sécurité**: Bien que les NFT offrent une sécurité améliorée, des failles de sécurité dans la blockchain ou des erreurs dans la mise en œuvre de la technologie pourraient compromettre la confiance des utilisateurs et nuire à la réputation de l'application.
        // ";

        //         $promptResult = "# Strengths
        // - Utilisation de la technologie NFT pour garantir la propriété unique des billets, évitant ainsi la contrefaçon
        // - Facilité d'utilisation de l'application pour réserver et utiliser les billets
        // - Possibilité de stocker et transférer les billets de manière sécurisée grâce à la blockchain
        // - Potentiel d'attirer les amateurs de technologies et de crypto-monnaies intéressés par les NFT

        // # Weaknesses
        // - Nécessité pour les utilisateurs de posséder un portefeuille de crypto-monnaies pour acheter les NFT et utiliser l'application
        // - Risque de vol ou de perte des NFT en cas de piratage ou de sécurité informatique insuffisante
        // - Faible adoption des NFT et de la blockchain dans le grand public, ce qui peut limiter la clientèle potentielle
        // - Dépendance à la technologie NFT qui peut être encore en cours de développement et soumise à des bogues ou des problèmes de compatibilité

        // # Opportunities
        // - Possibilité de partenariats avec des artistes, des événements ou des organisations pour créer des NFT exclusifs et attirer une clientèle spécifique
        // - Expansion de l'application pour proposer des services complémentaires tels que la revente ou l'échange de NFT
        // - Positionnement sur un marché de niche en pleine croissance avec un fort potentiel de développement
        // - Possibilité de sensibiliser et d'éduquer le public sur les avantages et les possibilités des NFT et de la blockchain

        // # Threats
        // - Concurrence accrue sur le marché des applications de réservation de billets et des plateformes d'échange de NFT
        // - Risque de réglementation gouvernementale ou de restrictions concernant l'utilisation des NFT
        // - Volatilité des prix des crypto-monnaies pouvant impacter la valeur des NFT et des transactions effectuées sur l'application
        // - Risque de mauvaise réputation liée à des incidents de sécurité ou à des problèmes de confidentialité des données des utilisateurs";

        $result = AIHelper::getResultFromSWOTPrompt($promptResult);

        return $this->json($result); // an open-source, widely-used, server-side scripting language.

    }
}
