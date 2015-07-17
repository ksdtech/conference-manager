<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Auth Controller - based on Community Auth
 */

class Auth extends MY_Controller
{
    public function __construct() {
        parent::__construct();

        // Force SSL
        //$this->force_ssl();
    }

    /**
     * This login method only serves to redirect a user to a 
     * location once they have successfully logged in. It does
     * not attempt to confirm that the user has permission to 
     * be on the page they are being redirected to.
     */
    public function login()
    {
        // Method should not be directly accessible
        if ( $this->uri->uri_string() == 'auth/login') {
            show_404();
        }

        if ( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ) {
            $this->require_min_level(1);
        }

        $this->setup_login_form();
        $this->load->template('auth/login');
    }

    // --------------------------------------------------------------

    /**
     * Log out
     */
    public function logout()
    {
        $this->authentication->logout();
        redirect( secure_site_url( LOGIN_PAGE . '?logout=1') );
    }
    
    /**
     * User recovery form
     */
    public function recover()
    {
    	// TODO: use a custom form - See Community Auth documentation.
    	echo "Not quite yet.";
    }
}
