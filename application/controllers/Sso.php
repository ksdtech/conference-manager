<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(dirname(BASEPATH).'/simplesamlphp/lib/_autoload.php');

class SSO extends MY_Controller {
    public function __construct()
    {
        parent::__construct();

        // Force SSL
        //$this->force_ssl();
    }

    // -----------------------------------------------------------------------

    /**
     * Demonstrate being redirected to login.
     * If you are logged in and request this method,
     * you'll see the message, otherwise you will be
     * shown the login form. Once login is achieved,
     * you will be redirected back to this method.
     */
    public function index()
    {

      /* Get a reference to our authentication source. */
      $as = new SimpleSAML_Auth_Simple('confmgr');

      /* Require the user to be authentcated. */
      /* When that function returns, we have an authenticated user. */
      $as->requireAuth();

      $logged_in = $as->isAuthenticated();

      /* Log the user out. */
      /* $as->logout(); */

      /*
       * Retrieve attributes of the user.
       *
       * Note: If the user isn't authenticated when getAttributes() is
       * called, an empty array will be returned.
       */
      $attributes = $as->getAttributes();

      // Do something with SAML library here
      $post_data = $this->input->post();
      $get_data  = $this->input->get();
      $data = array('loggged_in' => $logged_in, 
        'attributes' => $attributes, 
        'post' => $post_data, 
        'get' => $get_data);
      $this->load->template('sso_index', $data);
    }
}
