<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'admin/shortcodes/TeReclutaJobs.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'helpers/helpers.php';


class TeReclutaShortcodes
{

  private string $plugin_name;
  private string $version;

  public function __construct(string $plugin_name, string $version)
  {
    $this->plugin_name = $plugin_name;
    $this->version     = $version;

    $this->TeReclutaJobs();
  }

  public function TeReclutaJobs()
  {

    add_action('wp_enqueue_scripts', function () {
      wp_enqueue_style($this->plugin_name . "-shortcode", plugin_dir_url(__FILE__) . 'shortcodes/css/te-recluta.css', [], $this->version, 'all');
    });

    add_shortcode($this->plugin_name, function ($shortcode_attributes) {
      $te_recluta = new TeReclutaJobs($this->plugin_name, $this->version, $shortcode_attributes);

      ob_start();

      $te_recluta->renderTeReclutaJobs();

      return ob_get_clean();
    });
  }
}
