<?php

/**
 * @Class OptiMonkAdmin
 */
class OptiMonkAdmin
{
    /**
     * @var string
     */
    protected static $pluginLink = 'themes.php?page=optimonk';
    /**
     * @var
     */
    protected static $basePath;

    /**
     * @param $pluginBasePath
     */
    public function __construct($pluginBasePath)
    {
        self::$basePath = $pluginBasePath;
        add_filter('plugin_action_links_' . plugin_basename(self::$basePath), array($this, 'addSettingsPageLink'));
        add_action('admin_init', array($this, 'initSettings'));
        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_post_optimonk_settings', array($this, 'postHandler'));
    }

    public static function activate()
    {
        add_option('optiMonkDoActivationRedirect', true);
    }

    /**
     * @param $links
     *
     * @return mixed
     */
    public function addSettingsPageLink($links)
    {
        $settings_link = '<a href="' . self::$pluginLink . '">' . __('Settings', 'optimonk') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function redirectToSettingPage()
    {
        if (get_option('optiMonkDoActivationRedirect', false)) {
            delete_option('optiMonkDoActivationRedirect');
            wp_redirect(self::$pluginLink);
        }
    }

    public function initSettings()
    {
        register_setting('optiMonk', 'accountId', 'intval');
    }

    public function menu()
    {
        add_submenu_page(
            'themes.php',
            __('OptiMonk', 'optimonk'),
            __('OptiMonk', 'optimonk'),
            'edit_theme_options',
            'optimonk',
            array($this, 'settings')
        );
    }

    public function settings()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        wp_enqueue_style('optimonk-style', plugin_dir_url(__FILE__) . 'css/optimonk-style.css');
        $error = $this->getError();
        $pluginDir = plugin_dir_url(self::$basePath);

        include(sprintf("%s/template/settings.php", dirname(__FILE__)));
    }

    public function postHandler()
    {
        if (!($accountId = (int) $_POST['optiMonk_accountId'])) {
            $_SESSION['optiMonk']['error'] = __('Wrong account id!', 'optimonk');
            wp_redirect(self::$pluginLink);
        }
        update_option('optiMonk_accountId', $accountId);
        wp_redirect(self::$pluginLink);
    }

    /**
     * @return string
     */
    protected function getError()
    {
        $error = array();
        if (isset($_SESSION['optiMonk']['error'])) {
            $error[] = $_SESSION['optiMonk']['error'];
        }

        unset($_SESSION['optiMonk']);

        return $error;
    }
}
