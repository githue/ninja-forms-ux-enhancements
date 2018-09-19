<?php if (!defined('ABSPATH')) exit;

/*
 * Plugin Name: Ninja Forms - UX Enhancements
 * Plugin URI: 
 * Description: 
 * Version: 3.0.0
 * Author: Rowan
 * Author URI: 
 * Text Domain: nf-ux-enhancements
 *
 * Copyright 2018 Rowan.
 */

if (version_compare(get_option('ninja_forms_version', '0.0.0'), '3', '<') || get_option('ninja_forms_load_deprecated', false)) {

    //include 'deprecated/nf-ux-enhancements.php';

} else {

  /**
   * Class NF_UXEnhancements
   */
  final class NF_UXEnhancements
  {
    const VERSION = '0.0.1';
    const SLUG = 'ux-enhancements';
    const NAME = 'UX Enhancements';
    const AUTHOR = 'Rowan';
    const PREFIX = 'NF_UXEnhancements';

    /**
     * @var NF_UXEnhancements
     * @since 3.0
     */
    private static $instance;

    /**
     * Plugin Directory
     *
     * @since 3.0
     * @var string $dir
     */
    public static $dir = '';

    /**
     * Plugin URL
     *
     * @since 3.0
     * @var string $url
     */
    public static $url = '';

    /**
     * Main Plugin Instance
     *
     * Insures that only one instance of a plugin class exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 3.0
     * @static
     * @static var array $instance
     * @return NF_UXEnhancements Highlander Instance
     */
    public static function instance()
    {
      if (!isset(self::$instance) && !(self::$instance instanceof NF_UXEnhancements)) {
        self::$instance = new NF_UXEnhancements();

        self::$dir = plugin_dir_path(__FILE__);

        self::$url = plugin_dir_url(__FILE__);

        /*
         * Register our autoloader
         */
        spl_autoload_register(array(self::$instance, 'autoloader'));
      }

      return self::$instance;
    }

    public function __construct()
    {
      // add_action('admin_init', array($this, 'setup_license'));

      add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

      $this->admin_settings('settings');

      $override_date_format = get_option('nf_ux_enhancements_sub_date_format', '1');

      if ($override_date_format) {
        add_filter('nf_edit_sub_date_submitted', array($this, 'edit_sub_format_submitted_date'), 10);
        add_filter('nf_edit_sub_date_modified', array($this, 'edit_sub_format_modified_date'), 10);
      }

      $submissions_btn = get_option('nf_ux_enhancements_subs_back', '1');

      if ($submissions_btn) {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
      }

      $show_scrollbar = get_option('nf_ux_enhancements_scrollbar', '1');

      if ($show_scrollbar) {
        add_filter('admin_body_class', array($this, 'add_body_class_scroll'));
      }
    }

    function add_body_class_scroll()
    {
      // Surrounding spaces are required.
      return ' nf-ux-enhancements-scroll ';
    }

    public function enqueue_admin_scripts()
    {
      wp_enqueue_style('nf-ux-enhancements-admin', self::$url . 'assets/css/admin.css', array(), self::VERSION, 'all');
    }

    public function edit_sub_format_submitted_date()
    {
      $post = get_post();
      return $post->post_date;
    }

    public function edit_sub_format_modified_date()
    {
      $post = get_post();
      return $post->post_modified;
    }

    public function add_meta_boxes()
    {
      add_meta_box(
        'nf_ux_enhancements_view',
        __('View', 'ninja-forms'),
        array($this, 'view_meta_box'),
        'nf_sub',
        'side',
        'high'
      );
    }

    public function view_meta_box()
    {
      $submission = get_post();
      $id = $submission->ID;
      $form_id = get_post_meta($id, '_form_id', true);
      $submissions_url = admin_url('edit.php?post_status=all&post_type=nf_sub&form_id=' . $form_id);
      $link_label = __("All Submissions", "nf-ux-enhancements");
      ?>

      <p>
        <a href="<?php echo $submissions_url ?>" class="nf-ux-enhancements-subs-btn dashicons-before dashicons-list-view">
          <?php echo $link_label ?>
        </a>
      </p>

      <?php
    }

    public function admin_settings($file_name)
    {
      return include self::$dir . 'includes/Admin/Menus/' . $file_name . '.php';
    }

    /*
     * Methods for convenience.
     */
    public function autoloader($class_name)
    {
      if (class_exists($class_name)) return;

      if (false === strpos($class_name, self::PREFIX)) return;

      $class_name = str_replace(self::PREFIX, '', $class_name);
      $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
      $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

      if (file_exists($classes_dir . $class_file)) {
        require_once $classes_dir . $class_file;
      }
    }

    /**
     * Template
     *
     * @param string $file_name
     * @param array $data
     */
    public static function template($file_name = '', array $data = array())
    {
      if (!$file_name) return;

      extract($data);

      include self::$dir . 'includes/Templates/' . $file_name;
    }

    /**
     * Config
     *
     * @param $file_name
     * @return mixed
     */
    public static function config($file_name)
    {
      return include self::$dir . 'includes/Config/' . $file_name . '.php';
    }

    /*
     * Required methods for all extensions.
     */
    public function setup_license()
    {
      if (!class_exists('NF_Extension_Updater')) return;

      new NF_Extension_Updater(self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG);
    }
  }

  /**
   * The main function responsible for returning The Highlander Plugin
   * Instance to functions everywhere.
   *
   * Use this function like you would a global variable, except without needing
   * to declare the global.
   *
   * @since 3.0
   * @return {class} Highlander Instance
   */
  function NF_UXEnhancements()
  {
    return NF_UXEnhancements::instance();
  }

  NF_UXEnhancements();
}
