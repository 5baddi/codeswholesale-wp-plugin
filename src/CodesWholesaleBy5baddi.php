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

namespace BaddiServices\CodesWholesale;

use Timber\Timber;
use BaddiServices\CodesWholesale\Traits\AdminMenu;
use BaddiServices\CodesWholesale\Traits\TimberTrait;

/**
 * Class CodesWholesaleBy5baddi.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class CodesWholesaleBy5baddi
{
    use AdminMenu, TimberTrait;

    public const SLUG = 'codeswholesale-by-5baddi';

    /**
     * @var CodesWholesaleBy5baddi
     */
    private static $instance;

    public static function getInstance(): CodesWholesaleBy5baddi
    {
        if (! self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->configure();
    }

    private function configure(): void
    {
        // Prevent plugin activation if Oxygen builder not activated or not installed
        register_activation_hook(CWS_5BADDI_PLUGIN_BASENAME, [$this, 'checkWooCommerceIsInstalled']);

        // Set twig view path
        Timber::$locations = CWS_5BADDI_PLUGIN_BASEPATH . 'src/Views/';

        // Actions
        // Register hooks
        add_action('init', [$this, 'init'], 1);

        // Register custom settings page
        if (is_admin()) {
            add_action('admin_menu', [$this, 'registerSettingsPage']);
            add_action('admin_init', [$this, 'registerSettingsPageOptions']);
        }

        // Filters
        // Timber twig filter
        add_filter('timber/twig', [$this, 'addTwigHelpers']);
    }

    public function checkWooCommerceIsInstalled(): void
    {
        if (! is_plugin_active('woocommerce/woocommerce.php')) {
            wp_die(cws5baddiTranslation('Make sure <a href="https://woocommerce.com/">WooCommerce plugin</a> is installed and activated!'));
        }
    }

    public function init(): void
    {
        
    }

    public function pluginsLoaded(): void
    {

    }
}