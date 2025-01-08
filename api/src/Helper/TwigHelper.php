<?php

namespace App\Helper;

/**
 * class TwigHelper
 * package App\Helper
 */
final class TwigHelper
{
    /** @var string  */
    public const TWIG_VIEW_SWOT_PDF = '/pdf/swot_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_BUSINESS_MODEL_CANVAS_PDF = '/pdf/business_model_canvas_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_BUYER_PERSONA_PDF = '/pdf/buyer_persona_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_COMPETITOR_ANALYSIS_PDF = '/pdf/competitor_analysis_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_GOLDEN_TRIANGLE_PDF = '/pdf/golden_triangle_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_MARKETING_MIX_4_PDF = '/pdf/marketing_mix_4_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_MARKETING_MIX_5_PDF = '/pdf/marketing_mix_5_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_PESTEL_PDF = '/pdf/pestel_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_SMART_PDF = '/pdf/smart_pdf.html.twig';

    /** @var string  */
    public const TWIG_VIEW_STP_PDF = '/pdf/stp_pdf.html.twig';

    /** @var string[]  */
    public const TWIG_VIEWS_PDF = [
        self::TWIG_VIEW_SWOT_PDF,
        self::TWIG_VIEW_BUSINESS_MODEL_CANVAS_PDF,
        self::TWIG_VIEW_BUYER_PERSONA_PDF,
        self::TWIG_VIEW_COMPETITOR_ANALYSIS_PDF,
        self::TWIG_VIEW_GOLDEN_TRIANGLE_PDF,
        self::TWIG_VIEW_MARKETING_MIX_4_PDF,
        self::TWIG_VIEW_MARKETING_MIX_5_PDF,
        self::TWIG_VIEW_PESTEL_PDF,
        self::TWIG_VIEW_SMART_PDF,
        self::TWIG_VIEW_STP_PDF,
    ];
}
