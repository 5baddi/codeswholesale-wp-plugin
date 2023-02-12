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

use WP_Post;
use Illuminate\Support\Str;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;

/**
 * Trait InlineScriptsTrait.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait InlineScriptsTrait
{
    /**
     * Generate cws5Baddi JS object
     */
    private function generateGlobalJsObject(): string
    {
        $currentPost = get_post();
        $currentPostType = null;
        $currentPostId = intval(get_the_ID());

        if ($currentPost instanceof WP_Post) {
            // Set current post type
            $currentPostType = $currentPost->post_type;
        }

        return sprintf(
            "
                const cws5Baddi = {
                    name: '%s',
                    textDomain: '%s',
                    isDebugMode: %s,
                    currentPostType: '%s',
                    wpUser: {
                        isConnected: %s,
                    },
                    currentPostId: %d,
                    homeUrl: '%s',
                    ajaxUrl: '%s',
                    apiNonce: '%s',
                    isAdminPage: %s,
                    translations: %s,
                    %s
                };%s
            ",
            get_bloginfo('name'),
            CodesWholesaleBy5baddi::NAMESPACE,
            (defined('WP_DEBUG') && WP_DEBUG) ? 'true' : 'false',
            $currentPostType,
            is_user_logged_in() ? 'true' : 'false',
            $currentPostId,
            strtok(home_url(), '?'),
            strtok(admin_url('admin-ajax.php'), '?'),
            wp_create_nonce('wp_rest'),
            is_admin() ? 'true' : 'false',
            json_encode(Constants::translations()),
            $this->prepareSharedData(),
            PHP_EOL
        );
    }

    /**
     * append to cws5Baddi JS object
     */
    private function appendToGlobalJsObject(array $data = []): string
    {
        $values = '';

        foreach ($data as $key => $item) {
            $key = $this->parseKey($key);
            $parsedValue = $this->parseValue($item);

            $values = sprintf(
                "
                    %s

                    if (typeof cws5Baddi === 'object') {
                        cws5Baddi.%s = %s;
                    }

                    %s
                ",
                $values,
                $key,
                $parsedValue,
                PHP_EOL
            );
        }

        return $values;
    }

    private function prepareSharedData(): string
    {
        $sharedData = Constants::sharedData();
        $values = '';

        foreach ($sharedData as $key => $item) {
            $key = $this->parseKey($key);
            $parsedValue = $this->parseValue($item);

            if (Str::startsWith($key, '.')) {
                $key = substr($key, 1, -1);
            }

            $values = sprintf(
                '%s%s%s%s',
                $values,
                ! empty($values) ? ',' : '',
                PHP_EOL,
                sprintf('%s: %s', $key, $parsedValue)
            );
        }

        return $values;
    }

    private function parseValue($value)
    {
        $parsedValue = '';

        if (is_object($value) || is_array($value)) {
            $parsedValue = json_encode($value);
        }

        if (is_numeric($value)) {
            $parsedValue = $value;
        }

        if (is_bool($value)) {
            $parsedValue = $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            $parsedValue = sprintf("'%s'", addcslashes($value, "'"));
        }

        return $parsedValue;
    }

    private function parseKey(string $key): string
    {
        $key = preg_replace('/[^a-zA-Z0-9\.\_]+/', '', $key);

        if (is_int(substr($key, 0, 1))) {
            $key = substr($key, 1, -1);
        }

        return $key;
    }
}
