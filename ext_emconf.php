<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'PayPal payments within TYPO3',
    'description' => 'Shows the integration of the Paypal payment service.',
    'category' => 'misc',
    'author' => 'Manuel Schnabel',
    'author_email' => 'service@passionweb.de',
    'author_company' => 'PassionWeb Manuel Schnabel',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => ['typo3' => '11.5.0-12.4.99'],
        'conflicts' => [],
        'suggests' => [],
    ],
];
