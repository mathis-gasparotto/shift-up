<?php

namespace App\Service;

use App\Entity\BusinessModelCanvas;
use App\Entity\BuyerPersona;
use App\Entity\CompetitorAnalysis;
use App\Entity\GoldenTriangle;
use App\Entity\MarketingMix4;
use App\Entity\MarketingMix5;
use App\Entity\PESTEL;
use App\Entity\SMART;
use App\Entity\STP;
use App\Entity\SWOT;
use App\Helper\FileHelper;
use App\Helper\TwigHelper;
use Knp\Snappy\Pdf;
use League\Flysystem\Config;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * class FileService
 * package App\Service
 */
class FileService
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param string $appBackUrl
     * @param string $mediaStorage
     * @param Pdf $knpSnappyPdf
     * @param FilesystemAdapter $mediaStorageLocal
     * @param Environment $twig
     */
    public function __construct(
        private string $appBackUrl,
        private string $mediaStorage,
        private Pdf $knpSnappyPdf,
        private FilesystemAdapter $mediaStorageLocal,
        private Environment $twig
    ) {
        $this->config = new Config();
    }

    /**
     * @return FilesystemAdapter
     */
    private function getMediaStorage()
    {
        return  match ($this->mediaStorage) {
            FileHelper::MEDIA_STORAGE_LOCAL => $this->mediaStorageLocal,
            default => throw new \LogicException('This storage location does not exist: ' . $this->mediaStorage)
        };
    }

    /**
     * @param object $data
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generatePdfToHtml(object $data): string
    {
        $fileParameters = match ($data::class) {
            SWOT::class => [
                'view' => TwigHelper::TWIG_VIEW_SWOT_PDF,
                'options' => FileHelper::FILE_OPTIONS_SWOT_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            BusinessModelCanvas::class => [
                'view' => TwigHelper::TWIG_VIEW_BUSINESS_MODEL_CANVAS_PDF,
                'options' => FileHelper::FILE_OPTIONS_BUSINESS_MODEL_CANVAS_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            BuyerPersona::class => [
                'view' => TwigHelper::TWIG_VIEW_BUYER_PERSONA_PDF,
                'options' => FileHelper::FILE_OPTIONS_BUYER_PERSONA_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            CompetitorAnalysis::class => [
                'view' => TwigHelper::TWIG_VIEW_COMPETITOR_ANALYSIS_PDF,
                'options' => FileHelper::FILE_OPTIONS_COMPETITOR_ANALYSIS_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            GoldenTriangle::class => [
                'view' => TwigHelper::TWIG_VIEW_GOLDEN_TRIANGLE_PDF,
                'options' => FileHelper::FILE_OPTIONS_GOLDEN_TRIANGLE_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            MarketingMix4::class => [
                'view' => TwigHelper::TWIG_VIEW_MARKETING_MIX_4_PDF,
                'options' => FileHelper::FILE_OPTIONS_MARKETING_MIX_4_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            MarketingMix5::class => [
                'view' => TwigHelper::TWIG_VIEW_MARKETING_MIX_5_PDF,
                'options' => FileHelper::FILE_OPTIONS_MARKETING_MIX_5_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            PESTEL::class => [
                'view' => TwigHelper::TWIG_VIEW_PESTEL_PDF,
                'options' => FileHelper::FILE_OPTIONS_PESTEL_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            SMART::class => [
                'view' => TwigHelper::TWIG_VIEW_SMART_PDF,
                'options' => FileHelper::FILE_OPTIONS_SMART_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            STP::class => [
                'view' => TwigHelper::TWIG_VIEW_STP_PDF,
                'options' => FileHelper::FILE_OPTIONS_STP_HTML_TO_PDF,
                'parameters' => [
                    'data' => $data
                ]
            ],
            default => throw new \LogicException('This data entity does not exist: ' . $data::class)
        };

        $fileTwigContent = $this->twig->render($fileParameters['view'], $fileParameters['parameters']);

        return $this->knpSnappyPdf->getOutputFromHtml(
            $fileTwigContent,
            $fileParameters['options'],
        );
    }

    /**
     * @param string $file
     * @param string $fileExtension
     * @param string $fileDir
     * @param string $fileName
     * @return void
     * @throws FilesystemException
     */
    public function uploadDestination(string $file, string $fileExtension, string $fileDir, string $fileName): void
    {
        $mediaStorage = $this->getMediaStorage();

        $mediaStorage->write($fileDir . '/' . $fileName . '.' . $fileExtension, $file, $this->config);
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws FilesystemException
     */
    public function fileExist(string $filePath): bool
    {
        $mediaStorage = $this->getMediaStorage();

        return $mediaStorage->fileExists($filePath);
    }

    /**
     * @param string $filePath
     * @throws FilesystemException
     */
    public function deleteFile(string $filePath): void
    {
        $mediaStorage = $this->getMediaStorage();

        $dirDestination = str_split(substr($filePath, 0, 3));

        $mediaStorage->delete($dirDestination[0] . '/' . $dirDestination[1] . '/' . $dirDestination[2] . '/' . $filePath);
    }

    /**
     * @param string $filePathToDelete
     * @throws FilesystemException
     */
    public function deleteFileIfExist(string $filePathToDelete): void
    {
        if ($this->fileExist($filePathToDelete)) {
            $this->deleteFile($filePathToDelete);
        }
    }
}
