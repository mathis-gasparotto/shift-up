<?php

namespace App\Helper;

final class AIHelper
{
    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForSWOT(string $projectDescription): string
    {
        return "Fait une analyse SWOT, en Français.
        Le format en json souhaité est le suivant :
        - strengths (array of string)
        - weaknesses (array of string)
        - opportunities (array of string)
        - threats (array of string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromSWOTPrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'strengths' => $jsonData['strengths'],
                'weaknesses' => $jsonData['weaknesses'],
                'opportunities' => $jsonData['opportunities'],
                'threats' => $jsonData['threats']
            ];
        }
        throw new \Exception('Something went wrong with the SWOT prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForBusinessModelCanvas(string $projectDescription): string
    {
        return "Fait moi un Business Model Canvas, en Français.
        Le format en json souhaité est le suivant :
        - keyPartners (array of string)
        - keyActivities (array of string)
        - keyResources (array of string)
        - valuePropositions (array of string)
        - customerRelationships (string)
        - channels (array of string)
        - customerSegments (array of string)
        - costStructure (array of string)
        - revenueStreams (array of string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromBusinessModelCanvasPrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'keyPartners' => $jsonData['keyPartners'],
                'keyActivities' => $jsonData['keyActivities'],
                'keyResources' => $jsonData['keyResources'],
                'valuePropositions' => $jsonData['valuePropositions'],
                'customerRelationships' => $jsonData['customerRelationships'],
                'channels' => $jsonData['channels'],
                'customerSegments' => $jsonData['customerSegments'],
                'costStructure' => $jsonData['costStructure'],
                'revenueStreams' => $jsonData['revenueStreams'],
            ];
        }
        throw new \Exception('Something went wrong with the Business Model Canvas prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForBuyerPersona(string $projectDescription): string
    {
        return "Fait moi un Buyer Persona, en Français.
        Le format en json souhaité est le suivant :
        - personalInfo :
            - first_name (string)
            - age (int)
            - location (string)
            - job (string)
        - goalsChallenges (string)
        - communicationChannels (array of string)
        - valuesFears (string)
        - motivation (string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromBuyerPersonaPrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'personalInfo' => $jsonData['personalInfo'],
                'goalsChallenges' => $jsonData['goalsChallenges'],
                'communicationChannels' => $jsonData['communicationChannels'],
                'valuesFears' => $jsonData['valuesFears'],
                'motivation' => $jsonData['motivation'],
            ];
        }
        throw new \Exception('Something went wrong with the Buyer Persona prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForSMART(string $projectDescription): string
    {
        return "Fait moi un SMART, en Français.
        Le format en json souhaité est le suivant :
        - specific (string)
        - measurable (string)
        - achievable (string)
        - relevant (string)
        - timed (string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromSMARTPrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'specific' => $jsonData['specific'],
                'measurable' => $jsonData['measurable'],
                'achievable' => $jsonData['achievable'],
                'relevant' => $jsonData['relevant'],
                'timed' => $jsonData['timed'],
            ];
        }

        throw new \Exception('Something went wrong with the SMART prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForPESTEL(string $projectDescription): string
    {
        return "Fait moi un PESTEL, en Français.
        Le format en json souhaité est le suivant :
        - political (string)
        - economic (string)
        - social (string)
        - technological (string)
        - environmental (string)
        - legal (string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromPESTELPrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'political' => $jsonData['political'],
                'economic' => $jsonData['economic'],
                'social' => $jsonData['social'],
                'technological' => $jsonData['technological'],
                'environmental' => $jsonData['environmental'],
                'legal' => $jsonData['legal'],
            ];
        }

        throw new \Exception('Something went wrong with the PESTEL prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForSTP(string $projectDescription): string
    {
        return "Fait moi un STP, en Français.
        Le format en json souhaité est le suivant :
        - segmentation (string)
        - targeting (string)
        - positioning (string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromSTPPrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'segmentation' => $jsonData['segmentation'],
                'targeting' => $jsonData['targeting'],
                'positioning' => $jsonData['positioning'],
            ];
        }

        throw new \Exception('Something went wrong with the STP prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForMarketingMix4(string $projectDescription): string
    {
        return "Fait moi un 4P, en Français.
        Le format en json souhaité est le suivant :
        - product (string)
        - price (string)
        - place (string)
        - promotion (string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromMarketingMix4Prompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'product' => $jsonData['product'],
                'price' => $jsonData['price'],
                'place' => $jsonData['place'],
                'promotion' => $jsonData['promotion'],
            ];
        }
        throw new \Exception('Something went wrong with the 4P prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForMarketingMix5(string $projectDescription): string
    {
        return "Fait moi un 5P, en Français.
        Le format en json souhaité est le suivant :
        - product (string)
        - price (string)
        - place (string)
        - promotion (string)
        - people (string)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromMarketingMix5Prompt(string $result): array
    {

        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'product' => $jsonData['product'],
                'price' => $jsonData['price'],
                'place' => $jsonData['place'],
                'promotion' => $jsonData['promotion'],
                'people' => $jsonData['people'],
            ];
        }
        throw new \Exception('Something went wrong with the 5P prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForCompetitorAnalysis(string $projectDescription): string
    {
        return "Fait moi une analyse concurrentielle, en Français.
        Le format en json souhaité est le suivant :
        - xAxisLabel: nom de l'axe X
        - yAxisLabel: nom de l'axe Y
        - competitors:
            - name: nom du concurent
            - xPosition: position sur l'axe X (valeur entre -10 et 10)
            - yPosition: position sur l'axe Y (valeur entre -10 et 10)
        - ourPosition:
            - xPosition: position de notre projet sur l'axe X (valeur entre -10 et 10)
            - yPosition: position de notre projet sur l'axe Y (valeur entre -10 et 10)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromCompetitorAnalysisPrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);
        if ($jsonData) {
            return [
                'xAxisLabel' => $jsonData['xAxisLabel'],
                'yAxisLabel' => $jsonData['yAxisLabel'],
                'competitors' => array_map(function ($competitor) {
                    return [
                        'name' => $competitor['name'],
                        'xPosition' => (float) $competitor['xPosition'],
                        'yPosition' => (float) $competitor['yPosition']
                    ];
                }, $jsonData['competitors']),
                'ourPosition' => [
                    'xPosition' => (float) $jsonData['ourPosition']['xPosition'],
                    'yPosition' => (float) $jsonData['ourPosition']['yPosition']
                ]
            ];
        }
        throw new \Exception('Something went wrong with the Competitor Analysis prompt');
    }

    /**
     * @param string $projectDescription
     * @return string
     */
    public static function promptForGoldenTriangle(string $projectDescription): string
    {
        return "Fait moi un triangle d'or, en Français.
        Le format en json souhaité est le suivant :
        - topLabel: nom de l'axe X
        - leftLabel: nom de l'axe Y
        - rightLabel: nom de l'axe Z
        - brands:
            - name: nom du concurent
            - topPosition: position par rapport au top (valeur entre 0 et 100)
            - leftPosition: position par rapport au left (valeur entre 0 et 100)
            - rightPosition: position par rapport au right (valeur entre 0 et 100)
        - ourPosition:
            - topPosition: position de notre projet par rapport au top (valeur entre 0 et 100)
            - leftPosition: position de notre projet par rapport au left (valeur entre 0 et 100)
            - rightPosition: position de notre projet par rapport au right (valeur entre 0 et 100)
        Le projet suivant : $projectDescription";
    }

    /**
     * @param string $result
     * @return array
     */
    public static function getResultFromGoldenTrianglePrompt(string $result): array
    {
        $jsonData = GlobalHelper::getArrayFromJSONPromptResult($result);

        if ($jsonData) {
            return [
                'topLabel' => $jsonData['topLabel'],
                'leftLabel' => $jsonData['leftLabel'],
                'rightLabel' => $jsonData['rightLabel'],
                'brands' => array_map(function ($brand) {
                    return [
                        'name' => $brand['name'],
                        'topPosition' => (float) $brand['topPosition'],
                        'leftPosition' => (float) $brand['leftPosition'],
                        'rightPosition' => (float) $brand['rightPosition']
                    ];
                }, $jsonData['brands']),
                'ourPosition' => [
                    'topPosition' => (float) $jsonData['ourPosition']['topPosition'],
                    'leftPosition' => (float) $jsonData['ourPosition']['leftPosition'],
                    'rightPosition' => (float) $jsonData['ourPosition']['rightPosition']
                ]
            ];
        }

        throw new \Exception('Something went wrong with the Golden Triangle prompt');
    }
}