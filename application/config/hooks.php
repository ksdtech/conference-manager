<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
// community_auth
$hook['pre_system'] = array(
		'function' => 'auth_constants',
		'filename' => 'auth_constants.php',
		'filepath' => 'hooks'
);
