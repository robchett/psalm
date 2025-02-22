<?php

namespace Psalm\Tests\ReturnTypeProvider;

use Psalm\Tests\TestCase;
use Psalm\Tests\Traits\InvalidCodeAnalysisTestTrait;
use Psalm\Tests\Traits\ValidCodeAnalysisTestTrait;

class SprintfTest extends TestCase
{
    use InvalidCodeAnalysisTestTrait;
    use ValidCodeAnalysisTestTrait;

    public function providerValidCodeParse(): iterable
    {
        yield 'sprintfDNonEmpty' => [
            'code' => '<?php
                $val = sprintf("%d", implode("", array()));
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfFormatNonEmpty' => [
            'code' => '<?php
                $val = sprintf("%s %s", "", "");
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfArgnumFormatNonEmpty' => [
            'code' => '<?php
                $val = sprintf("%2\$s %1\$s", "", "");
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfLiteralFormatNonEmpty' => [
            'code' => '<?php
                $val = sprintf("%s hello", "");
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfStringPlaceholderLiteralIntParamFormatNonEmpty' => [
            'code' => '<?php
                $val = sprintf("%s", 15);
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfStringPlaceholderIntParamFormatNonEmpty' => [
            'code' => '<?php
                $val = sprintf("%s", crc32(uniqid()));
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfStringPlaceholderFloatParamFormatNonEmpty' => [
            'code' => '<?php
                $val = sprintf("%s", microtime(true));
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfStringPlaceholderIntStringParamFormatNonEmpty' => [
            'code' => '<?php
                $tmp = rand(0, 10) > 5 ? time() : implode("", array()) . "hello";
                $val = sprintf("%s", $tmp);
            ',
            'assertions' => [
                '$val===' => 'non-empty-string',
            ],
        ];

        yield 'sprintfStringPlaceholderLiteralStringParamFormat' => [
            'code' => '<?php
                $val = sprintf("%s", "");
            ',
            'assertions' => [
                '$val===' => 'string',
            ],
        ];

        yield 'sprintfStringPlaceholderStringParamFormat' => [
            'code' => '<?php
                $val = sprintf("%s", implode("", array()));
            ',
            'assertions' => [
                '$val===' => 'string',
            ],
        ];

        yield 'sprintfStringArgnumPlaceholderStringParamsFormat' => [
            'code' => '<?php
                $val = sprintf("%2\$s%1\$s", "", implode("", array()));
            ',
            'assertions' => [
                '$val===' => 'string',
            ],
        ];

        yield 'sprintfStringPlaceholderIntStringParamFormat' => [
            'code' => '<?php
                $tmp = rand(0, 10) > 5 ? time() : implode("", array());
                $val = sprintf("%s", $tmp);
            ',
            'assertions' => [
                '$val===' => 'string',
            ],
        ];

        yield 'printfSimple' => [
            'code' => '<?php
                $val = printf("%s", "hello");
            ',
            'assertions' => [
                '$val===' => 'int<0, max>',
            ],
        ];

        yield 'sprintfEmptyStringFormat' => [
            'code' => '<?php
                $val = sprintf("", "abc");
            ',
            'assertions' => [
                '$val===' => '\'\'',
            ],
            'ignored_issues' => [
                'InvalidArgument',
            ],
        ];

        yield 'sprintfPaddedEmptyStringFormat' => [
            'code' => '<?php
                $val = sprintf("%0.0s", "abc");
            ',
            'assertions' => [
                '$val===' => '\'\'',
            ],
            'ignored_issues' => [
                'InvalidArgument',
            ],
        ];

        yield 'sprintfComplexPlaceholderNotYetSupported1' => [
            'code' => '<?php
                $val = sprintf(\'%*.0s\', 0, "abc");
            ',
            'assertions' => [
                '$val===' => 'string',
            ],
        ];

        yield 'sprintfComplexPlaceholderNotYetSupported2' => [
            'code' => '<?php
                $val = sprintf(\'%0.*s\', 0, "abc");
            ',
            'assertions' => [
                '$val===' => 'string',
            ],
        ];

        yield 'sprintfComplexPlaceholderNotYetSupported3' => [
            'code' => '<?php
                $val = sprintf(\'%*.*s\', 0, 0, "abc");
            ',
            'assertions' => [
                '$val===' => 'string',
            ],
        ];
    }

    public function providerInvalidCodeParse(): iterable
    {
        return [
            'sprintfOnlyFormat' => [
                'code' => '<?php
                    $x = sprintf("hello");
                ',
                'error_message' => 'TooFewArguments',
            ],
            'sprintfTooFewArguments' => [
                'code' => '<?php
                    $x = sprintf("%s hello %d", "a");
                ',
                'error_message' => 'TooFewArguments',
            ],
            'sprintfTooManyArguments' => [
                'code' => '<?php
                    $x = sprintf("%s hello", "a", "b");
                ',
                'error_message' => 'TooManyArguments',
            ],
            'sprintfInvalidFormat' => [
                'code' => '<?php
                    $x = sprintf(\'"%" hello\', "a");
                ',
                'error_message' => 'InvalidArgument',
            ],
            'printfOnlyFormat' => [
                'code' => '<?php
                    printf("hello");
                ',
                'error_message' => 'TooFewArguments',
            ],
            'printfTooFewArguments' => [
                'code' => '<?php
                    printf("%s hello %d", "a");
                ',
                'error_message' => 'TooFewArguments',
            ],
            'printfTooManyArguments' => [
                'code' => '<?php
                    printf("%s hello", "a", "b");
                ',
                'error_message' => 'TooManyArguments',
            ],
            'printfInvalidFormat' => [
                'code' => '<?php
                    printf(\'"%" hello\', "a");
                ',
                'error_message' => 'InvalidArgument',
            ],
            'sprintfEmptyFormat' => [
                'code' => '<?php
                    $x = sprintf("", "abc");
                ',
                'error_message' => 'InvalidArgument',
            ],
            'sprintfFormatWithoutPlaceholders' => [
                'code' => '<?php
                    $x = sprintf("hello", "abc");
                ',
                'error_message' => 'InvalidArgument',
            ],
            'sprintfPaddedComplexEmptyStringFormat' => [
                'code' => '<?php
                    $x = sprintf("%1$+0.0s", "abc");
                ',
                'error_message' => 'InvalidArgument',
            ],
        ];
    }
}
