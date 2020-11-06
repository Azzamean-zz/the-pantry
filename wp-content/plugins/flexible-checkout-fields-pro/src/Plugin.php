<?php
/**
 * .
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro;

use FCFProVendor\WPDesk_Plugin_Info;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use WPDesk\FCF\Pro\Admin;
use WPDesk\FCF\Pro\Pricing;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
class Plugin extends AbstractPlugin implements HookableCollection {

	use HookableParent;

	/**
	 * Scripts version.
	 *
	 * @var string
	 */
	private $script_version = '1';

	/**
	 * Plugin constructor.
	 *
	 * @param WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );

		$this->plugin_url       = $this->plugin_info->get_plugin_url();
		$this->plugin_namespace = $this->plugin_info->get_text_domain();
		$this->script_version   = $plugin_info->get_version();
	}

	/**
	 * Initializes plugin external state.
	 *
	 * The plugin internal state is initialized in the constructor and the plugin should be internally consistent after creation.
	 * The external state includes hooks execution, communication with other plugins, integration with WC etc.
	 *
	 * @return void
	 */
	public function init() {
		$this->add_hookable( new Admin\Assets() );
		$this->add_hookable( new Pricing\Fields() );
		$this->add_hookable( new Pricing\Session() );
		$this->add_hookable( new Pricing\Settings() );
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		$this->hooks_on_hookable_objects();
	}

	/**
	 * Get script version.
	 *
	 * @return string;
	 */
	public function get_script_version() {
		return $this->script_version;
	}
}
