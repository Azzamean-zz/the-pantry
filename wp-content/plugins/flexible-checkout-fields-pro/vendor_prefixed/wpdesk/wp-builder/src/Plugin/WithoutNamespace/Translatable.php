<?php

namespace FCFProVendor;

if (!\interface_exists('FCFProVendor\\WPDesk_Translable')) {
    require_once 'Translable.php';
}
interface WPDesk_Translatable extends \FCFProVendor\WPDesk_Translable
{
    /** @return string */
    public function get_text_domain();
}
