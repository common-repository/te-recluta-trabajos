<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/TeReclutaShortcodes.php';

class TeReclutaAdmin {
	private string $plugin_name;
	private string $version;
	private array $te_recluta_options;
	protected TeReclutaShortcodes $shortcodes;

	public function __construct( string $plugin_name, string $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'admin_menu', array( $this, 'te_recluta_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'te_recluta_page_init' ) );
	}

	public function loadShortcodes() {
		$this->shortcodes = new TeReclutaShortcodes($this->plugin_name, $this->version);
	}

	public function enqueueStyles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/te-recluta-admin.css', array(), $this->version, 'all' );
	}

	public function enqueueScripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/te-recluta-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function te_recluta_add_plugin_page() {
		add_menu_page(
			'Te Recluta', // page_title
			'Te Recluta', // menu_title
			'manage_options', // capability
			$this->plugin_name, // menu_slug
			array( $this, 'te_recluta_create_admin_page' ), // function
			plugins_url('images/icon.svg', __FILE__ ), // icon_url
			75 // position
		);
	}

	public function get_options() {
		return get_option( 'te_recluta_option_name' );
	}

	public function te_recluta_create_admin_page() {
		$this->te_recluta_options = get_option( 'te_recluta_option_name' ); ?>

		<div class="wrap">
			<h2>Te Recluta</h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'te_recluta_option_group' );
					do_settings_sections( 'te-recluta-admin' );
					submit_button();
				?>
			</form>

			<div id="section-description" class="section" style="display: block;">				
				<p><strong>Uso del Shortcode:</strong></p>

				<pre><code>[<?php echo $this->plugin_name; ?>]</code></pre>

				<h3>Características</h3>

				<ul>
					<li>
						<p><strong>Paginación:</strong> Determina si se muestra la paginación de puestos de trabajos. <em>Predeterminado: "0"</em> (deshabilitado)</p>
						<pre><code>pagination="1"</code></pre>
					</li>
					<li>
						<p><strong>Tiempo:</strong> Muestra el tiempo de la publicación. <em>Predeterminado: "1"</em> (habilitado)</p>
						<pre><code>time="0"</code></pre>
					</li>
					<li>
						<p><strong>Descripción:</strong> Muestra la descripción de la publicación. <em>Predeterminado: "1"</em> (habilitado)</p>
						<pre><code>description="0"</code></pre>
					</li>
					<li>
						<p><strong>Locación:</strong> Muestra la locación (país y provincia) de la publicación. <em>Predeterminado: "1"</em> (habilitado)</p>
						<pre><code>location="0"</code></pre>
					</li>
				</ul>
			</div>

			
		</div>
	<?php }

	public function te_recluta_page_init() {
		register_setting(
			'te_recluta_option_group', // option_group
			'te_recluta_option_name', // option_name
			array( $this, 'te_recluta_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'te_recluta_setting_section', // id
			'Settings', // title
			array( $this, 'te_recluta_section_info' ), // callback
			'te-recluta-admin' // page
		);

		add_settings_field(
			'te_recluta_company_code', // id
			'company_code', // title
			array( $this, 'te_recluta_company_code_callback' ), // callback
			'te-recluta-admin', // page
			'te_recluta_setting_section' // section
		);
	}

	public function te_recluta_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['te_recluta_company_code'] ) ) {
			$sanitary_values['te_recluta_company_code'] = sanitize_text_field( $input['te_recluta_company_code'] );
		}

		return $sanitary_values;
	}

	public function te_recluta_section_info() {
		
	}

	public function te_recluta_company_code_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="Ej: terecluta" name="te_recluta_option_name[te_recluta_company_code]" id="te_recluta_company_code" value="%s">',
			isset( $this->te_recluta_options['te_recluta_company_code'] ) ? esc_attr( $this->te_recluta_options['te_recluta_company_code']) : ''
		);
	}

}