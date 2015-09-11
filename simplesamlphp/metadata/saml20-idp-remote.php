<?php
/**
 * SAML 2.0 remote IdP metadata for simpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote 
 */

/*
 * Guest IdP. allows users to sign up and register. Great for testing!
 
$metadata['https://openidp.feide.no'] = array(
	'name' => array(
		'en' => 'Feide OpenIdP - guest users',
		'no' => 'Feide Gjestebrukere',
	),
	'description'          => 'Here you can login with your account on Feide RnD OpenID. If you do not already have an account on this identity provider, you can create a new one by following the create new account link and follow the instructions.',

	'SingleSignOnService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SSOService.php',
	'SingleLogoutService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SingleLogoutService.php',
	'certFingerprint'      => 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb'
);
*/

// HTTP-POST version cribbed from dotnetsso-saml-sp-example in PowerSchool API examples
/*
$metadata['https://ksd.powerschool.com:443/ConfMgrPost'] = array(
  'name' => array(
    'en' => 'PowerSchool Guardian',
  ),
  'description'            => 'PowerSchool Guardian login',
  'SingleSignOnService'    => 'https://ksd.powerschool.com:443/powerschool-saml-sso/profile/SAML2/POST/SSO',
  'SingleSignOnServiceBinding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
  'SignAuthnRequest'       => false,
  'WantSAMLResponseSigned' => false,
  'WantAssertionSigned'    => false
);
*/

// See older description of array of array for metadata endpoints
$metadata['https://ksd.powerschool.com:443/ConfMgr'] = array(
  'name' => array(
    'en' => 'PowerSchool Guardian',
  ),
  'description'            => 'PowerSchool Guardian login',
  'SingleSignOnService'    => array(
    array(
      'Location' => 'https://ksd.powerschool.com:443/powerschool-saml-sso/profile/SAML2/POST/SSO',
      'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST'
    )
  )
  // 'SignAuthnRequest'       => false,
  // 'WantSAMLResponseSigned' => false,
  // 'WantAssertionSigned'    => false
);
