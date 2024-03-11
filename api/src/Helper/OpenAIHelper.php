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
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForBusinessModelCanvas(string $projectDescription): string
    {
        return "Fait moi un Business Model Canvas, en séparant les 9 parties par un titre de niveau 1 avec 'Key Partners', 'Key Activities', 'Key Resources', 'Value Propositions', 'Customer Relationships', 'Channels', 'Customer Segments', 'Cost Structure', 'Revenue Streams', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromBusinessModelCanvasPrompt(string $result): array
    {
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForBuyerPersona(string $projectDescription): string
    {
        return "Fait moi un Buyer Persona, en séparant les 6 parties par un titre de niveau 1 avec 'Personal Info', 'Professional Info', 'Goals Challenges', 'Communication Channels', 'Values Fears', 'Negative Info', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromBuyerPersonaPrompt(string $result): array
    {
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForSMART(string $projectDescription): string
    {
        return "Fait moi un SMART, en séparant les 5 parties par un titre de niveau 1 avec 'Specific', 'Measurable', 'Achievable', 'Relevant', 'Timed', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromSMARTPrompt(string $result): array
    {
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForPESTEL(string $projectDescription): string
    {
        return "Fait moi un PESTEL, en séparant les 6 parties par un titre de niveau 1 avec 'Political', 'Economic', 'Social', 'Technological', 'Environmental', 'Legal', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromPESTELPrompt(string $result): array
    {
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForSTP(string $projectDescription): string
    {
        return "Fait moi un STP, en séparant les 3 parties par un titre de niveau 1 avec 'Segmentation', 'Targeting', 'Positioning', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromSTPPrompt(string $result): array
    {
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForMarketingMix4(string $projectDescription): string
    {
        return "Fait moi un 4P, en séparant les 4 parties par un titre de niveau 1 avec 'Product', 'Price', 'Place', 'Promotion', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromMarketingMix4Prompt(string $result): array
    {
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForMarketingMix5(string $projectDescription): string
    {
        return "Fait moi un 5P, en séparant les 5 parties par un titre de niveau 1 avec 'Product', 'Price', 'Place', 'Promotion', 'People', du projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromMarketingMix5Prompt(string $result): array
    {
        return GlobalHelper::splitStringByMarkdownTitle1($result);
    }
}