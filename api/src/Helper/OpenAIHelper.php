<?php

namespace App\Helper;

final class OpenAIHelper
{
    /** @var string  */
    public const OPENAI_MODEL_TYPE_INSTRUCT = 'INSTRUCT';

    /** @var string  */
    public const OPENAI_MODEL_TYPE_TURBO = 'TURBO';


    /** @var string  */
    public const OPENAI_MODEL_TYPE_USED = self::OPENAI_MODEL_TYPE_TURBO;

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForSWOT(string $projectDescription): string
    {
        return "Fait une analyse SWOT, en séparant les 4 parties par un titre de niveau 1 avec 'Strengths', 'Weaknesses', 'Opportunities', 'Threats', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromSWOTPrompt(string $result): array
    {
        $resultArray = preg_split('/^# (.*)/m', $result, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $resultArray = array_map('trim', $resultArray);
        $resultArray = array_map(fn($item) => str_replace("\r\n", "\n", $item), $resultArray);
        $resultArray = array_map(fn($item) => str_replace("\n\n", "\n", $item), $resultArray);
        $resultArray = array_chunk($resultArray, 2);
        return array_combine(array_column($resultArray, 0), array_column($resultArray, 1));
    }
}