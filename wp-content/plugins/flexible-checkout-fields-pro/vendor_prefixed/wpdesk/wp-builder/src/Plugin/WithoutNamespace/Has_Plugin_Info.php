<?php

namespace FCFProVendor;

if (!\interface_exists('FCFProVendor\\WPDesk_Translatable')) {
    require_once __DIR__ . '/Translatable.php';
}
interface WPDesk_Has_Plugin_Info extends \FCFProVendor\WPDesk_Translatable
{
    /**
     * @return string
     */
    public function get_plugin_file_name();
    /**
     * @return string
     */
    public function get_plugin_dir();
    /**
     * @return string
     */
    public function get_version();
}
