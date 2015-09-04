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

    public function oauth2callback() 
    {
        if ( $this->input->get('error') ) {
            // Got an error, probably user denied access
            exit('Got error: ' . $this->input->get('error'));
        } else {
            $code = $this->input->get('code');
            $state = $this->input->get('state');

            $this->load->library('session');
            if ( empty($state) ) {
                exit('Missing state');
            } else {
                $session_state = $this->session->userdata('oauth2state');
                if ( $state !== $session_state ) {

                    // State is invalid, possible CSRF attack in progress
                    $this->session->unset_userdata('oauth2state');
                    exit( 'Invalid state (session had ' . $session_state . ' but auth has ' . $state . ')');
                }
            }

            if ( empty($code) ) {
                exit('Missing code');
            } else {
                // Try to get an access token (using the authorization code grant)
                $provider = $this->getProvider();
                $token = $provider->getAccessToken('authorization_code', array('code' => $code));

                if ( !$token ) {
                    exit('No access token');
                } else {
                    // save access token
                    $encoded_token = base64_encode(serialize($token));
                    $this->session->set_userdata('token', $encoded_token);

                    $session_state = $this->session->userdata('oauth2state');
                    $state_params = unserialize(base64_decode($session_state));
                    $next_url = site_url('welcome');
                    if ( !empty($state_params['redirect']) ) {
                        $next_url = $state_params['redirect'];
                    }
                    $this->session->unset_userdata('oauth2state');

                    $userDetails = $provider->getUserDetails($token);
                    // uid, name, firstName, lastName, email, imageUrl

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
                    if ( !empty( $results['user_id'] ) ) {
                        $auth_data = $this->user->get_auth_data( $user_string );
                        if ( $auth_data ) {
                            $this->authentication->maintain_state_on_oauth_login( $auth_data );
                            redirect( $next_url );
                        }
                    }

                    exit("Google user create or lookup failed");
                }
            }
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
