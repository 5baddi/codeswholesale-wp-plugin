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

use Illuminate\Support\Str;

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
    public static function insertImageFromUrlAsAttachment(string $url, ?string $name = null)
    {
        if (! defined('ABSPATH')) {
            return false;
        }

        require_once(sprintf('%swp-admin/includes/file.php', ABSPATH));

        $imageContent = @file_get_contents($url);
        if (empty($imageContent)) {
            var_dump($imageContent);die();
            return false;
        }

        $tempFile = tmpfile();
        file_put_contents($tempFile, $imageContent);

        $file = [
            'name'     => ! empty($name) ? Str::slug($name, '_') : basename($url),
            'type'     => mime_content_type($tempFile),
            'tmp_name' => $tempFile,
            'size'     => filesize($tempFile),
        ];
var_dump($file, $tempFile, $imageContent);die();
        $sideload = wp_handle_sideload($file, ['test_form' => false]);
        if (! empty($sideload['error'])) {
            var_dump($sideload);die();
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
            var_dump($attachmentId);die();
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
