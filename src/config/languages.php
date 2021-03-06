<?php

$languages = [
    'en' => ['English', 'en_US'],
    'es' => ['Spanish', 'es_ES'],
];

return array_merge($languages, ['available' => array_keys($languages)]);
