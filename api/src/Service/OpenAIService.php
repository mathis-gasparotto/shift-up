<?php

namespace App\Service;


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
    public function __construct(private string $openAIModel, private string $openAIInstructModel, private Client $client)
    {
    }

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
        return $this->createCompletion($content)->choices[0]->text;
    }
}
