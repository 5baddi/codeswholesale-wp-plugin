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
use BaddiServices\CodesWholesale\Traits\InlineScriptsTrait;

/**
 * Class ScriptEnqueuer.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class ScriptEnqueuer
{
    use InlineScriptsTrait;

    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $dependencies = [];

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $loadInFooter = false;

    /**
     * @var bool
     */
    private $isRemoteFile = false;

    public function __construct(string $name, array $dependencies = [], bool $loadInFooter = false)
    {
        $this->id = sprintf('%s-script-%s', CodesWholesaleBy5baddi::NAMESPACE, md5($name));
        $this->dependencies = $dependencies;
        $this->loadInFooter = $loadInFooter;
        $this->path = ! $this->isRemoteFile ? removeDoubleSlashes(Str::replace(CWS_5BADDI_PLUGIN_ASSETS_PATH, CWS_5BADDI_PLUGIN_ASSETS_URL, $name)) : $name;
    }

    /**
     * Load a script to enqueue or register it later
     */
    public static function load(string $name): self
    {
        return new self($name);
    }

    /**
     * Register a new script
     */
    public function register(): self
    {
        if (!$this->isRemoteFile && !file_exists(Str::replace(CWS_5BADDI_PLUGIN_ASSETS_URL, CWS_5BADDI_PLUGIN_ASSETS_PATH, $this->path))) {
            return $this;
        }

        if (wp_script_is($this->id, 'registered')) {
            return $this;
        }

        wp_register_script(
            $this->id,
            $this->path,
            $this->dependencies,
            CWS_5BADDI_PLUGIN_ASSETS_VERSION,
            $this->loadInFooter
        );

        return $this;
    }

    /**
     * Enqueue a script
     */
    public function enqueue(): self
    {
        if (!$this->isRemoteFile && !wp_script_is($this->id, 'registered')) {
            $this->register();
        }

        if (!$this->isRemoteFile  && !wp_script_is($this->id, 'enqueued')) {
            wp_enqueue_script($this->id);
        }

        if ($this->isRemoteFile && !wp_script_is($this->id, 'enqueued')) {
            wp_enqueue_script(
                $this->id,
                $this->path
            );
        }

        return $this;
    }

    /**
     * Enqueue an inline script
     */
    public function enqueueInlineScript(string $script): self
    {
        if (! wp_script_is($this->id, 'enqueued')) {
            return $this;
        }

        wp_add_inline_script(
            $this->id,
            $script
        );

        return $this;
    }

    /**
     * Enqueue global JS object
     */
    public function enqueueGlobalJsObject(): self
    {
        $this->enqueueInlineScript(
            $this->generateGlobalJsObject()
        );

        return $this;
    }

    /**
     * Enqueue appended values to global JS object
     */
    public function enqueueAppendedDataToGlobalJsObject(array $data = []): self
    {
        $this->enqueueInlineScript(
            $this->appendToGlobalJsObject($data)
        );

        return $this;
    }

    /**
     * Set the script to be loaded in footer
     */
    public function loadInFooter(): self
    {
        $this->loadInFooter = true;

        return $this;
    }

    /**
     * Set the script dependencies
     */
    public function hasDependencies(array $dependencies = []): self
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Set the script path
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the script is remote file
     */
    public function setIsRemoteFile(): self
    {
        $this->isRemoteFile = true;

        return $this;
    }
}
