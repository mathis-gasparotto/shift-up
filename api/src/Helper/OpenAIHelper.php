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
}
