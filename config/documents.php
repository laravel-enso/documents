<?php

return [
    'deletableTimeLimit' => 60 * 60,
    'imageWidth' => 2048,
    'imageHeight' => 2048,
    'onDelete' => 'restrict',
    'loggableMorph' => [
        'documentable' => [],
    ],
    'queues' => [
        'ocr' => 'heavy',
    ],
];
