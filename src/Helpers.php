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

/**
 * File Helpers.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

if (! function_exists('cws5baddiTranslation')) {
    /**
     * Translation helper
     */
    function cws5baddiTranslation(?string $text = null): ?string
    {
        if (empty($text)) {
            return null;
        }

        return __($text, CWS_5BADDI_PLUGIN_TEXT_DOMAIN);
    }
}