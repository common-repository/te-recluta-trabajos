<?php

/**
 *
 * @package           Te_Recluta
 * @author            Te Recluta
 * @link              https://terecluta.com/
 * @since             1.2.3
 * @copyright         2022 Te Recluta
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Te Recluta
 * Plugin URI:        https://terecluta.com/soluciones/plugin/
 * Description:       Muestra fácilmente los trabajos de Te Recluta en cualquier lugar con shortcodes.
 * Version:           1.2.3
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Te Recluta
 * Author URI:        https://terecluta.com/
 * Text Domain:       te_recluta
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

/**
 * Current plugin version.
 */
const TERECLUTA_PLUGIN_VERSION = '1.2.3';

require plugin_dir_path(__FILE__) . 'includes/TeReclutaPlugin.php';

function runTeReclutaPlugin()
{

  $plugin = new TeReclutaPlugin();
  $plugin->run();
}

runTeReclutaPlugin();

