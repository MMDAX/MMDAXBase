<?php

namespace MMDAXBase;

return array(
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
            array(
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language/forms',
                'pattern' => '%s.php',
                'text_domain' => 'FormTranslate',
            ),
        ),
    ),
);
