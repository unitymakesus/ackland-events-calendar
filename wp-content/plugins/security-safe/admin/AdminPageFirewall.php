<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class AdminPageFirewall
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageFirewall extends AdminPage {


    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */  
    protected function set_page() {

        $this->slug = 'security-safe-firewall';
        $this->title = 'Firewall Page';
        $this->description = 'Firewall page description.';

    } // set_page()


} // AdminPageFirewall()
