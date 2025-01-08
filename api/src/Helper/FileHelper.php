<?php

namespace App\Helper;

use DateTime;

/**
 * class FileHelper
 * package App\Helper
 */
final class FileHelper
{
    /** @var string  */
    public const FILE_PDF_EXTENSION = 'pdf';

    /** @var string  */
    public const MEDIA_STORAGE_LOCAL = 'media.storage.local';

    /** @var array  */
    public const FILE_OPTIONS_SWOT_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_BUSINESS_MODEL_CANVAS_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_BUYER_PERSONA_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_COMPETITOR_ANALYSIS_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_GOLDEN_TRIANGLE_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_MARKETING_MIX_4_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_MARKETING_MIX_5_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_PESTEL_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_SMART_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_OPTIONS_STP_HTML_TO_PDF = [
        'margin-top' => 0,
        'margin-right' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'page-size' => 'A4'
    ];

    /** @var array  */
    public const FILE_EXTENSIONS = [
        self::FILE_PDF_EXTENSION
    ];
}
