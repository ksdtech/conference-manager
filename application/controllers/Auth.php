<?php
defined('BASEPATH') or exit('No direct script access allowed');


// Needed? or put in loader class?
// Composer stuff
// require __DIR__ . '/../vendor/autoload.php';

// League oauth2 Google client
use League\OAuth2\Client\Provider\Google;

/**
 * Auth Controller - based on Community Auth
 */

class Auth extends MY_Controller
{

    public function __construct() {
        parent::__construct();

        // Force SSL
        //$this->force_ssl();

        // League ouath2 Google client
        // Replace these with your token settings
        // Create a project at https://console.developers.google.com/
        $this->clientId     = config_item('google_client_id');
        $this->clientSecret = config_item('google_client_secret');

        // Change this if you are not using the built-in PHP server
        $this->redirectUri  = secure_site_url(config_item('google_redirect_uri'));
        $this->scopes       = config_item('google_scopes');
        $this->domain       = config_item('google_domain');
        $this->provider     = null;
    }

    public function getProvider() {
        // Initialize the provider
        if ( !$this->provider ) {

            $provider_params = array(
                'clientId'     => $this->clientId,
                'clientSecret' => $this->clientSecret,
                'redirectUri'  => $this->redirectUri,
                'scope'        => $this->scopes,
            );
            $this->provider = new Google($provider_params);
        }
        return $this->provider;
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
            // Check for Google auth
            $login_data =  $this->input->post();
            if ( $login_data['submit'] == 'Google' ) {

                // Query string probably has something like ?redirect=admin%2Fusers
                // Encode these params in oauth2 'state' 
                $redirect = $this->input->get('redirect');
                $encoded_state = base64_encode(serialize(array('redirect' => $redirect))); 

                // CSRF check - save state in session
                $this->load->library('session');
                $this->session->set_userdata('oauth2state', $encoded_state);

                // And put state in auth url per Google spec
                $provider = $this->getProvider();
                $authUrl  = $provider->getAuthorizationUrl(array('state' => $encoded_state));

                redirect( $authUrl );
                return;
            }
            $this->require_min_level(1);
        }

        $this->setup_login_form();
        $this->load->template('auth/login');
    }

    private function get_oauth_next_url() {
        $next_url = FALSE;
        $session_state = $this->session->userdata('oauth2state');
        if ($session_state) {
            $state_params = unserialize(base64_decode($session_state));
            if ( !empty($state_params['redirect']) ) {
                $next_url = $state_params['redirect'];
            }
        }
        return $next_url;
    }

    public function oauth2callback() 
    {
        $next_url = $this->get_oauth_next_url();
        $success = false;

        if ( $this->input->get('error') ) {
            // Got an error, probably user denied access
            log_message('error', 'Google oauth error: ' . $this->input->get('error'));
        } else {
            $code = $this->input->get('code');
            $state = $this->input->get('state');

            $this->load->library('session');
            if ( empty($state) ) {
                log_message('error', 'Google oauth missing state');
            } else {
                $session_state = $this->session->userdata('oauth2state');
                if ( $state !== $session_state ) {

                    // State is invalid, possible CSRF attack in progress
                    $this->session->unset_userdata('oauth2state');
                    log_message( 'error', 
                        'Google oauth invalid state (session had ' . $session_state . ' but auth has ' . $state . ')');
                }
            }

            if ( empty($code) ) {
                log_message('error', 'Google oauth missing code');
            } else {
                // Try to get an access token (using the authorization code grant)
                $provider = $this->getProvider();
                $token = $provider->getAccessToken('authorization_code', array('code' => $code));

                if ( !$token ) {
                    log_message('error', 'Google oauth missing access token');
                } else {
                    // save access token
                    $encoded_token = base64_encode(serialize($token));
                    $this->session->set_userdata('token', $encoded_token);

                    // discard oauth state
                    $this->session->unset_userdata('oauth2state');

                    // Set up community auth $auth_data from the aouth user toaken
                    $userDetails = $provider->getUserDetails($token);
                    $user_string = $userDetails->email;
                    $oauth_uid   = strval($userDetails->uid);
                    $user_data   = array(
                        'user_name'  => NULL,
                        'user_level' => 6,
                        'user_email' => $user_string,
                        'first_name' => $userDetails->firstName,
                        'last_name'  => $userDetails->lastName,
                        'oauth_uid'  => $oauth_uid);

                    $this->load->model('User', 'user');
                    $results = $this->user->find_or_create_by_email( $user_data );
                    if ( empty( $results['user_id'] ) ) {
                        log_message('error', 'Google oauth user find or create failed for ' . $user_string);
                    } else {
                        $auth_data = $this->user->get_auth_data( $user_string );
                        if ( !$auth_data ) {
                            log_message('error', 'Google oauth user get_auth_data failed for ' . $user_string);
                        } else {
                            $this->authentication->maintain_state_on_oauth_login( $auth_data );
                            if (!$next_url) {
                                $next_url = site_url( 'welcome' );
                            }
                            log_message('debug', 'Google oauth succeeded, redirecting to ' . $next_url);
                            $success = true;
                            redirect( $next_url );
                        }
                    }
                }
            }
        }
        if (!$success) {
            log_message('debug', 'oauth2callback failed, going back to login page');
            $login_url = secure_site_url('login');
            if ($next_url) {
                $login_url .= '?redirect='.$next_url;
            }
            redirect( $login_url );
        }
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
