<?php

namespace App\Service;

use App\Helper\OpenAIHelper;
use OpenAI\Client;
use OpenAI\Responses\Chat\CreateResponse as CreateChatResponse;
use OpenAI\Responses\Completions\CreateResponse as CreateCompletionResponse;

/**
 *
 */
class OpenAIService
{
    /**
     * @param string $openAIModel
     * @param string $openAIInstructModel
     * @param Client $client
     */
    public function __construct(
        private string $openAIModel,
        private string $openAIInstructModel,
        private Client $client
    )
    {}

    /**
     * @param string $content
     * @return CreateChatResponse
     */
    private function createChat(string $content): CreateChatResponse
    {
        return $this->client->chat()->create([
            'model' => $this->openAIModel,
            'messages' => array([
                'role' => 'user',
                'content' => $content
            ]),
        ]);
    }

    /**
     * @param string $content
     * @return CreateCompletionResponse
     */
    private function createCompletion(string $content): CreateCompletionResponse
    {
        return $this->client->completions()->create([
            'model' => $this->openAIInstructModel,
            'prompt' => $content
        ]);
    }

    /**
     * @param string $content
     * @return string
     */
    public function prompt(string $content): string
    {
        return "# Strengths
            1. **Technologie Innovante**: L'utilisation de NFT offre une solution de réservation sécurisée et traçable, ce qui peut attirer une clientèle intéressée par les nouvelles technologies.
            2. **Sécurité Améliorée**: Les NFT offrent une meilleure sécurité des billets, réduisant ainsi les risques de fraude et de contrefaçon.
            3. **Traçabilité**: Les NFT permettent de suivre l'authenticité des billets tout au long du processus de réservation, offrant ainsi une expérience transparente aux utilisateurs.
            4. **Accessibilité Mondiale**: En utilisant une plateforme basée sur la blockchain, l'application peut être accessible à un large public à travers le monde, facilitant la réservation de billets pour des événements internationaux.
            
            # Weaknesses
            1. **Complexité Technique**: La technologie NFT peut être difficile à comprendre pour les utilisateurs non familiers avec la blockchain, ce qui peut limiter l'adoption de l'application.
            2. **Dépendance à la Blockchain**: Des problèmes de congestion ou de frais élevés sur la blockchain peuvent entraîner des retards ou des coûts supplémentaires pour les utilisateurs.
            3. **Barrières d'Entrée pour les Utilisateurs**: La nécessité de posséder un portefeuille de crypto-monnaie et de comprendre le fonctionnement des NFT peut exclure certains utilisateurs moins technophiles.
            
            # Opportunities
            1. **Partenariats Stratégiques**: Collaborer avec des organisateurs d'événements pour intégrer la technologie NFT dans leurs processus de billetterie peut ouvrir de nouvelles opportunités commerciales.
            2. **Expansion de Marché**: En offrant des billets sécurisés et traçables, l'application peut attirer de nouveaux marchés, y compris les événements sportifs, les concerts et les festivals.
            3. **Education sur la Blockchain**: Il existe une opportunité de sensibiliser et d'éduquer le grand public sur les avantages des NFT et de la blockchain, ce qui pourrait encourager une adoption plus large de l'application.
            
            # Threats
            1. **Réglementation**: Les réglementations gouvernementales concernant les crypto-monnaies et la blockchain pourraient limiter le développement et l'adoption de l'application.
            2. **Concurrence**: D'autres applications de billetterie traditionnelles ou basées sur la blockchain pourraient émerger et offrir une concurrence féroce.
            3. **Risques de Sécurité**: Bien que les NFT offrent une sécurité améliorée, des failles de sécurité dans la blockchain ou des erreurs dans la mise en œuvre de la technologie pourraient compromettre la confiance des utilisateurs et nuire à la réputation de l'application.
        ";
        return match (OpenAIHelper::OPENAI_MODEL_TYPE_USED) {
            OpenAIHelper::OPENAI_MODEL_TYPE_INSTRUCT => $this->createCompletion($content)->choices[0]->text,
            OpenAIHelper::OPENAI_MODEL_TYPE_TURBO => $this->createChat($content)->choices[0]->message->content,
        };
    }
}
