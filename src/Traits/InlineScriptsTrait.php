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
     * Generate employer branding JS object
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
            $this->prepareSharedData(),
            PHP_EOL
        );
    }

    private function prepareSharedData(): string
    {
        $sharedData = Constants::sharedData();
        $values = '';

        foreach ($sharedData as $key => $item) {
            $preparedValue = '';

            if (is_object($item) || is_array($item)) {
                $preparedValue = json_encode($item);
            }

            if (is_numeric($item)) {
                $preparedValue = $item;
            }

            if (is_bool($item)) {
                $preparedValue = $item ? 'true' : 'false';
            }

            if (is_string($item)) {
                $preparedValue = sprintf("'%s'", addcslashes($item, "'"));
            }

            $values = sprintf(
                '%s%s%s%s',
                $values,
                ! empty($values) ? ',' : '',
                PHP_EOL,
                sprintf('%s: %s', $key, $preparedValue)
            );
        }

        return $values;
    }
}
