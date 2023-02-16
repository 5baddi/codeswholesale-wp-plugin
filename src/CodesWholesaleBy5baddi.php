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
use Illuminate\Support\Str;
use BaddiServices\CodesWholesale\Traits\AdminTrait;
use BaddiServices\CodesWholesale\Core\StyleEnqueuer;
use BaddiServices\CodesWholesale\Traits\TimberTrait;
use BaddiServices\CodesWholesale\Core\ScriptEnqueuer;
use BaddiServices\CodesWholesale\Traits\ProductTrait;
use BaddiServices\CodesWholesale\Traits\WooCommerceTrait;
use BaddiServices\CodesWholesale\Traits\ApplyFiltersTrait;
use BaddiServices\CodesWholesale\Traits\CodesWholesaleTrait;

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
    use AdminTrait, TimberTrait, CodesWholesaleTrait, ProductTrait, ApplyFiltersTrait;
    use WooCommerceTrait;

    public const SLUG = 'codeswholesale-by-5baddi';
    public const NAMESPACE = 'cws5baddi';

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
        add_action('rest_api_init', [$this, 'initRestApiRoutes']);
        add_action('plugins_loaded', [$this, 'pluginsLoaded']);
    }

    public function checkWooCommerceIsInstalled(): void
    {
        if (! is_plugin_active('woocommerce/woocommerce.php')) {
            wp_die(cws5baddiTranslation('Make sure <a href="https://woocommerce.com/">WooCommerce plugin</a> is installed and activated!'));
        }
    }

    public function init(): void
    {
        // Actions
        // Register custom settings page
        if (is_admin()) {
            add_action('admin_menu', [$this, 'registerSettingsPage']);
            add_action('admin_init', [$this, 'registerSettingsPageOptions']);
            add_action('admin_enqueue_scripts', [$this, 'registerAdminStylesAndScripts']);
        }

        // Before render the post
        add_action('the_post', [$this, 'doubleCheckProductPrice'], 1);

        // Before wc checkout process
        add_action('woocommerce_before_checkout_process', [$this, 'beforeCheckoutProcess']);

        // Filters
        // Timber twig filter
        add_filter('timber/twig', [$this, 'addTwigHelpers']);

        // Only allowed origins
        add_filter('rest_authentication_errors', [$this, 'restAuthenticationErrors']);
    }

    public function pluginsLoaded(): void
    {
        $this->authenticate();
        $this->fetchSupportedProductDescriptionLanguages();
        $this->fetchSupportedRegions();
        $this->fetchSupportedTerritories();
        $this->fetchSupportedPlatforms();
    }

    public function initRestApiRoutes(): void
    {
        $controllersPaths = array_merge(
            glob(sprintf('%ssrc/Controllers/*.php', CWS_5BADDI_PLUGIN_BASEPATH)),
            glob(sprintf('%ssrc/Controllers/**/*.php', CWS_5BADDI_PLUGIN_BASEPATH)),
            glob(sprintf('%ssrc/Controllers/**/**/*.php', CWS_5BADDI_PLUGIN_BASEPATH))
        );

        foreach ($controllersPaths as $controllerPath) {
            $namespace = Str::replace([sprintf('%ssrc/', CWS_5BADDI_PLUGIN_BASEPATH), '.php'], '', $controllerPath);
            $namespace = Str::replace('/', '\\', $namespace);
            $namespace = sprintf('%s\\%s', __NAMESPACE__, $namespace);

            if (! class_exists($namespace) || ! method_exists($namespace, 'register_routes')) {
                continue;
            }

            $controllerInstance = new $namespace;
            $controllerInstance->{'register_routes'}();
        }
    }

    public function registerAdminStylesAndScripts(): void
    {
        $adminStyles = glob(sprintf('%sassets/css/admin/*.css', CWS_5BADDI_PLUGIN_BASEPATH));
        $adminScripts = glob(sprintf('%sassets/js/admin/*.js', CWS_5BADDI_PLUGIN_BASEPATH));

        foreach ($adminStyles as $adminStyle) {
            StyleEnqueuer::load($adminStyle)
                ->enqueue();
        }

        foreach ($adminScripts as $adminScript) {
            $scriptEnqueuer = ScriptEnqueuer::load($adminScript)
                ->enqueue();

            if (basename($adminScript) === 'main.js') {
                $scriptEnqueuer->enqueueGlobalJsObject();
            }
        }
    }
}