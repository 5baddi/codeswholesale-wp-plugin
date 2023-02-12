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

namespace BaddiServices\CodesWholesale\Services\Domains;

use GuzzleHttp\Client;
use GuzzleHttp\RedirectMiddleware;

/**
 * Class WpService.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class WpService
{
    /**
     * @return int|false
     */
    public static function insertImageFromUrlAsAttachment(string $url)
    {
        if (! defined('ABSPATH')) {
            return false;
        }

        if (! class_exists('WP_Http')) {
            require_once(sprintf('%swp-admin/includes/class-http.php', ABSPATH));
        }

        $client = new Client(['allow_redirects' => ['track_redirects' => true]]);
        $response = $client->get($url);
        $headersRedirect = $response->getHeader(RedirectMiddleware::HISTORY_HEADER);

        if (! empty($headersRedirect[0])) {
            $url = $headersRedirect[0];
        }

        require_once(sprintf('%swp-admin/includes/file.php', ABSPATH));

        $tempFile = download_url($url);
        if (is_wp_error($tempFile)) {
            return false;
        }

        $file = [
            'name'     => basename($url),
            'type'     => mime_content_type($tempFile),
            'tmp_name' => $tempFile,
            'size'     => filesize($tempFile),
        ];

        $sideload = wp_handle_sideload($file, ['test_form' => false]);
        if (! empty($sideload['error'])) {
            return false;
        }

        fclose($tempFile);

        $attachmentId = wp_insert_attachment(
            [
                'guid'           => $sideload['url'],
                'post_mime_type' => $sideload['type'],
                'post_title'     => basename($sideload['file']),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ],
            $sideload['file']
        );

        if (is_wp_error($attachmentId) || ! $attachmentId) {
            return false;
        }

        require_once(sprintf('%swp-admin/includes/image.php', ABSPATH));

        wp_update_attachment_metadata(
            $attachmentId,
            wp_generate_attachment_metadata($attachmentId, $sideload['file'])
        );

        return $attachmentId;
    }
}
