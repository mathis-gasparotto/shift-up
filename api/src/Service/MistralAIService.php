<?php

namespace App\Service;

// use Partitech\PhpMistral\MistralClient;
// use Partitech\PhpMistral\Messages;
// use Partitech\PhpMistral\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
class MistralAIService
{
    /** @var HttpClientInterface */
    // private MistralClient $client;
    private HttpClientInterface $client;

    /** @var string */
    private string $baseUri = 'https://api.mistral.ai';

    /**
     * @param string $mistralApiKey
     * @param string $mistralModel
     */
    public function __construct(
        private string $mistralApiKey,
        private string $mistralModel
    ) {
        // $this->client = new MistralClient($mistralApiKey);

        $this->client = HttpClient::createForBaseUri($this->baseUri, [
            'headers' => ['Authorization' => 'Bearer ' . $mistralApiKey],
        ]);
    }

    /**
     * @param string $content
     * @return array
     */
    private function createChat(string $content): array
    {
        // $messages = new Messages();
        // $messages->addUserMessage($content);
        // return $this->client->chat(
        //     $messages,
        //     [
        //         'model' => $this->mistralModel,
        //     ]
        // );

        $response = $this->client->request('POST', 'v1/chat/completions', [
            'json' => [
                'model' => $this->mistralModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $content,
                    ]
                ],
            ],
        ]);

        return $response->toArray();
    }

    /**
     * @param string $content
     * @return string
     */
    public function prompt(string $content): string
    {
        // return $this->createChat($content)->getMessage();

        return $this->createChat($content)['choices'][0]['message']['content'];
    }
}