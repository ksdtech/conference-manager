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


// After installing SSO plugin.xml in PowerSchool and configuring a Single Sign-on Certificate
// click View PowerSchool IDP Metadata, then copy and paste it into your simplesamlphp's 
// metadata parser, at /admin/metadata-converter.php.  Copy the parsed idp metadata here:
$metadata['https://ksd.powerschool.com:443/default-sp'] = array (
  'entityid' => 'https://ksd.powerschool.com:443/default-sp',
  'description' => 
  array (
    'en' => 'kentfield school district',
  ),
  'OrganizationName' => 
  array (
    'en' => 'kentfield school district',
  ),
  'name' => 
  array (
    'en' => 'kentfield school district',
  ),
  'OrganizationDisplayName' => 
  array (
    'en' => 'kentfield school district',
  ),
  'url' => 
  array (
    'en' => 'https://ksd.powerschool.com:443/default-sp',
  ),
  'OrganizationURL' => 
  array (
    'en' => 'https://ksd.powerschool.com:443/default-sp',
  ),
  'contacts' => 
  array (
  ),
  'metadata-set' => 'saml20-idp-remote',
  'SingleSignOnService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://ksd.powerschool.com:443/powerschool-saml-sso/profile/SAML2/Redirect/SSO',
    ),
  ),
  'SingleLogoutService' => 
  array (
  ),
  'ArtifactResolutionService' => 
  array (
  ),
  'keys' => 
  array (
    0 => 
    array (
      'encryption' => true,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => '
MIID4DCCA0mgAwIBAgIJAKrttG31fzsDMA0GCSqGSIb3DQEBBQUAMIGnMQswCQYD
VQQGEwJVUzETMBEGA1UECBMKQ2FsaWZvcm5pYTESMBAGA1UEBxMJS2VudGZpZWxk
MSIwIAYDVQQKExlLZW50ZmllbGQgU2Nob29sIERpc3RyaWN0MRwwGgYDVQQDExNw
ei4xMjcuMC4wLjEueGlwLmlvMS0wKwYJKoZIhvcNAQkBFh53ZWJtYXN0ZXJAa2Vu
dGZpZWxkc2Nob29scy5vcmcwHhcNMTUwOTEyMDIzNzA1WhcNMjUwOTExMDIzNzA1
WjCBpzELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWExEjAQBgNVBAcT
CUtlbnRmaWVsZDEiMCAGA1UEChMZS2VudGZpZWxkIFNjaG9vbCBEaXN0cmljdDEc
MBoGA1UEAxMTcHouMTI3LjAuMC4xLnhpcC5pbzEtMCsGCSqGSIb3DQEJARYed2Vi
bWFzdGVyQGtlbnRmaWVsZHNjaG9vbHMub3JnMIGfMA0GCSqGSIb3DQEBAQUAA4GN
ADCBiQKBgQDRH5fDJNgrtXcgeQ7IjOr3rC3GpH/uGQh/PSjFF7SCA10J0vV97t9h
t54LvakdRaoWuTLwpmXdQHI/8HITD6RA/As3ECYy9Sznkg0tFK7ZIzeljj2UjDjK
iaiekiagPL8AP3pVEqoJFaBzQARtZGorpngi+2k0zoUQEtA328XX5wIDAQABo4IB
EDCCAQwwHQYDVR0OBBYEFA+UTGRZju8FsyumAuZI7ih1KBV0MIHcBgNVHSMEgdQw
gdGAFA+UTGRZju8FsyumAuZI7ih1KBV0oYGtpIGqMIGnMQswCQYDVQQGEwJVUzET
MBEGA1UECBMKQ2FsaWZvcm5pYTESMBAGA1UEBxMJS2VudGZpZWxkMSIwIAYDVQQK
ExlLZW50ZmllbGQgU2Nob29sIERpc3RyaWN0MRwwGgYDVQQDExNwei4xMjcuMC4w
LjEueGlwLmlvMS0wKwYJKoZIhvcNAQkBFh53ZWJtYXN0ZXJAa2VudGZpZWxkc2No
b29scy5vcmeCCQCq7bRt9X87AzAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUA
A4GBADxAEZohrsI4fO78EdrgnEIId1oIf+H5c/X16fA1P+XRzQAzpH+rTzn09q9r
5tJjN9XerMkOdnDuDni4w8aDjKilMnkw/vca7SFFAxnEN6b4hupeNhjFO9wN6gUq
X0CtK3kYRtxt6fojrYyQe65pqKGqNIWNwWf3cKiN213icBeM',
    ),
  ),
);