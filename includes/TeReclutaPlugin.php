<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/TeReclutaLoader.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/TeReclutaAdmin.php';

class TeReclutaPlugin {
	protected string $plugin_name;
	protected string $version;

	public function __construct() {
		if ( defined( 'TERECLUTA_PLUGIN_VERSION' ) ) {
			$this->version = TERECLUTA_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'te-recluta';

		$this->loadDependencies();
		$this->defineAdminHooks();
	}

	private function loadDependencies() {
		$this->loader = new TeReclutaLoader();
	}

	private function defineAdminHooks() {
		$TeReclutaAdmin = new TeReclutaAdmin( $this->getPluginName(), $this->getVersion() );
		$this->loader->addAction( 'init', $TeReclutaAdmin, 'loadShortcodes' );
		$this->loader->addAction( 'admin_enqueue_scripts', $TeReclutaAdmin, 'enqueueStyles' );
		$this->loader->addAction( 'admin_enqueue_scripts', $TeReclutaAdmin, 'enqueueScripts' );
	}

	public function getPluginName(): string {
		return $this->plugin_name;
	}

	public function getVersion(): string {
		return $this->version;
	}

	public function run() {
		$this->loader->run();
	}
}