<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    'tom' => [
        'version' => '0.4.1',
    ],
    'rx' => [
        'version' => '4.1.0',
    ],
    'tom-select/dist/css/tom-select.bootstrap5.css' => [
        'version' => '2.4.1',
        'type' => 'css',
    ],
    'tom-select' => [
        'version' => '2.4.1',
    ],
    '@orchidjs/sifter' => [
        'version' => '1.1.0',
    ],
    '@orchidjs/unicode-variants' => [
        'version' => '1.1.2',
    ],
    'tom-select/dist/css/tom-select.default.min.css' => [
        'version' => '2.4.1',
        'type' => 'css',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.12',
    ],
    'vanilla-cookieconsent' => [
        'version' => '3.0.1',
    ],
    'aos' => [
        'version' => '2.3.4',
    ],
    '@fortawesome/fontawesome-free' => [
        'version' => '6.7.2',
    ],
    '@fortawesome/fontawesome-free/css/fontawesome.min.css' => [
        'version' => '6.7.2',
        'type' => 'css',
    ],
    'glightbox' => [
        'version' => '3.3.1',
    ],
    'swiper' => [
        'version' => '11.2.1',
    ],
    '@fortawesome/fontawesome-svg-core' => [
        'version' => '6.7.2',
    ],
    '@fortawesome/free-brands-svg-icons' => [
        'version' => '6.7.2',
    ],
    '@fortawesome/fontawesome-svg-core/styles.min.css' => [
        'version' => '6.7.2',
        'type' => 'css',
    ],
    'vanilla-cookieconsent/dist/cookieconsent.css' => [
        'version' => '3.0.1',
        'type' => 'css',
    ],
];
