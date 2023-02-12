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

namespace BaddiServices\CodesWholesale\Core;

use Illuminate\Support\Str;
use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;

/**
 * Class StyleEnqueuer.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class StyleEnqueuer
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $media = 'all';

    /**
     * @var bool
     */
    private $isRemoteFile = false;

    public function __construct(string $name)
    {
        $this->id = sprintf('%s-style-%s', CodesWholesaleBy5baddi::NAMESPACE, md5($name));
        $this->path = ! $this->isRemoteFile ? removeDoubleSlashes(Str::replace(CWS_5BADDI_PLUGIN_ASSETS_PATH, CWS_5BADDI_PLUGIN_ASSETS_URL, $name)) : $name;
    }

    /**
     * Load a style to enqueue or register it later
     */
    public static function load(string $name): self
    {
        return new self($name);
    }

    /**
     * Register a new style
     */
    public function register(): self
    {
        if (!$this->isRemoteFile && !file_exists(Str::replace(CWS_5BADDI_PLUGIN_ASSETS_URL, CWS_5BADDI_PLUGIN_ASSETS_PATH, $this->path))) {
            return $this;
        }

        if (wp_style_is($this->id, 'registered')) {
            return $this;
        }

        wp_register_style(
            $this->id,
            $this->path,
            [],
            CWS_5BADDI_PLUGIN_ASSETS_VERSION,
            $this->media
        );

        return $this;
    }

    /**
     * Enqueue a style
     */
    public function enqueue(): self
    {
        if (!$this->isRemoteFile && !wp_style_is($this->id, 'registered')) {
            $this->register();
        }

        if (!$this->isRemoteFile && !wp_style_is($this->id, 'enqueued')) {
            wp_enqueue_style($this->id);
        }

        if ($this->isRemoteFile && !wp_style_is($this->id, 'enqueued')) {
            wp_enqueue_style(
                $this->id,
                $this->path
            );
        }

        return $this;
    }

    /**
     * Set the style path
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the style media type
     */
    public function setMedia(string $media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Set the style is remote file
     */
    public function setIsRemoteFile(): self
    {
        $this->isRemoteFile = true;

        return $this;
    }
}
