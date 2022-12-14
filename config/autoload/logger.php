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
            'class' => \App\Core\Log\Handler\RotatingFileHandler::class,
            'constructor' => [
                'filename' => BASE_PATH . '/runtime/logs/%filename%.log',
                'maxFiles' => (int)env('LOG_SAVE_MAX_DAY', 30),
            ],
        ],
        'formatter' => [
            'class' => \App\Core\Log\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => null,
                'dateFormat' => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
];
