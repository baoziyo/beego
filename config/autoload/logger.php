<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

return [
    'default' => [
        'handler' => [
            'class' => \App\Biz\Log\Handler\RotatingFileHandler::class,
            'constructor' => [
                'filename' => BASE_PATH . '/runtime/logs/%filename%.log',
                'maxFiles' => (int)env('LOG_MAX_FILES', 30),
            ],
        ],
        'formatter' => [
            'class' => \App\Biz\Log\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => null,
                'dateFormat' => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
];
