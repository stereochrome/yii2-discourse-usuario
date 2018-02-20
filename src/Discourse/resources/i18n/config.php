<?php

return [
    'sourcePath' => __DIR__ . '/../../../Discourse',
    'messagePath' => __DIR__,
    'languages' => [
        'de',
    ],
    'translator' => 'Yii::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
    ],
    'format' => 'php',
];
