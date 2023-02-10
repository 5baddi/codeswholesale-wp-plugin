<?php

/**
 * PHP version 7.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

namespace BaddiServices\CodesWholesale\Traits;

use Twig\Environment;
use Timber\Twig_Filter;

/**
 * Trait TimberTrait.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait TimberTrait
{
    public function addTwigHelpers(Environment $twig): Environment
    {
        // Add functions

        // Add functions as filters
        $twig->addFilter(new Twig_Filter('translate', function ($text) {
            return cws5baddiTranslation($text);
        }));

        return $twig;
    }
}