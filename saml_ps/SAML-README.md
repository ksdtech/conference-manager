
Installing the SAML IdP Plugin in PowerSchool
---------------------------------------------

1. Install a Digital Certificate, or "create and import" one at System Settings > Digital Certificate Management.

2. Export the certificate for later use to a file named "ps-certificate.pem".

3. Adjust the parameters in the plugin.xml file in this folder according to your instance's Apache configuration.

4. Then install (upload) in PowerSchool Admin > System Settings > Plugin Managmenent Configuration.

5. After installing, click the plugin's name and then the "Single Sign-On Service" function link.

6. Attach the certificate you imported or created in step 1.

7. After enabling the certificate, click "View PowerSchool IDP Metadata". Copy and paste this into a file
named "psmetadata.xml".

8. Copy the plugin's Entity ID to simplesamlphp's authsources.php configuration file for the 'idp' array
entry (see below).

After installation, the PowerSchool plugin places a link in the Parent Portal (Guardian) header. Click the
up-right arrow ("Applications") icon in the header bar. That will pop up a dialog containing a link 
with the plugin's name.  The URL for the link will look like this:

    ```
    http://52.24.3.102/confmgr/index.php/psguardians/index?idpmetadata=https%3A%2F%2Fksd.powerschool.com%3A443%2Fpowerschool-saml-sso%2Fmetadata%2FconfmgrMetadata.action&studentContext=true
    ```

The URL path is concatenated from the plugin.saml.base-url and the plugin.saml.links.link.path values.

The URL query string shows the Shibboleth metadata link (requires cookies) at: 

    ```
    https://ksd.powerschool.com:443/powerschool-saml-sso/metadata/confmgrMetadata.action
    ```

SimpleSAMLphp Configuration
---------------------------

1. config/config.php


2. cert/ps-privatekey.pem

Split the "ps-certificate.pem" file you downloaded from the PowerSchool server.  Put the part that begins with
"-----BEGIN RSA PRIVATE KEY-----" into the file cert/ps-privatekey.pem.

2. config/authsources.php

    ```
    'entityID'    => [value of "entity-id" attribute from the plugin.xml file],
    'idp'         => [value of the Entity ID you obtained from ,
    'privatekey'  => 'saml.pem',
    'certificate' => 'saml.crt',


3. metadata/saml20-idp-remote.php

Take the psmetadata.xml file you copied from the PowerSchool server.  Run it through simplesamlphp's
Metdata Converter, and place the result in the metadata/saml20-idp-remote.php. You'll have to remove
a few things to avoid errors:

    ```
    validUntil="2021-01-31T23:59:00.000-05:00"
    <ns4:AttributeService/>
    
    <ns4:AttributeAuthorityDescriptor protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">

    <ns4:KeyDescriptor>...</ns4:AttributeAuthorityDescriptor>

    ```

It should look something like this:

    ```
    $metadata['https://ksd.powerschool.com:443/default-sp'] = array (
      'entityid' => 'https://ksd.powerschool.com:443/default-sp',
      ...
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
              'X509Certificate' => 
      ...
    );
    ```

4. Bindings

PowerSchool says that their IdP will support these bindings as of version 9.0:

    ```
    <ns4:SingleSignOnService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="https://ksd.powerschool.com:443/powerschool-saml-sso/profile/SAML2/Redirect/SSO" />
    <ns4:SingleSignOnService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="https://ksd.powerschool.com:443/powerschool-saml-sso/profile/SAML2/POST/SSO" />
    ```

The metadata file that is returned by simpleSAMLphp looks like the confmgr.xml file in this directory.

Bugs
----

First error message was: "SAML 2 SSO profile is not configured for relying party "

In the Shibboleth java-shib-ipd2 source code (SSOProfileHander.java), this is a ProfileException
thrown in the SSOProfileHandler.performAuthentication method.  Apparently, 
the decoder was not able to determine the entity id of the sender.

This apparently meant that PowerSchool IdP thinks we're requesting authentication anonymously.  This can
result from PowerSchool not being able to fetch our SP's metadata and therefore not knowing who we 
are.  

So I imported the certificate/key pair to PowerSchool and added these to authsources.php:

    ```
    'privatekey'  => 'confmgr.pem',
    'certificate' => 'confmgr.crt',
    'signature.algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
    'sign.authnrequest'   => TRUE,
    'redirect.sign'       => TRUE,
    'redirect.validate'   => TRUE,
    ```

Then the error became: "Message did not meet security requirements"

In the Shibboleth java-shib-idp2 source code, this is when a SecurityException is thrown by 
SSOProfileHandler.decodeRequest method, called by SSOProfileHandler.performAuthentication. 
There are two possiblities here:

in BaseSAMLMessageDecoder.java, when comparing the endpoint in the message to the endpoint specified by
the IdP's (?) metdata:
    SecurityException("SAML message intended destination (required by binding) was not present");
    SecurityException("SAML message intended destination endpoint did not match recipient endpoint");

in MetadataCredentialResolver.java, when the SP's metadata.getRole() is called:
    SecurityException("Unable to read metadata provider", e);

I removed the signing and certficate settings in authsources.php, then I got "SAML 2 SSO profile is not configured for relying party 'http://52.24.3.102'".


http://52.24.3.102/default-sp/module.php/saml/sp/metadata.php/confmgr




Here's the redirect:

https://ksd.powerschool.com:443/powerschool-saml-sso/profile/SAML2/Redirect/SSO

?SAMLRequest=hVLBjtMwFPyVyPfEjpPSrdVWKlshKi1s1RQOXJDrvGwtHNv4OezC16%2BTgiiXIh8sjWfevBl5ibI3XmyGeLYH%2BD4AxuylNxbF9LAiQ7DCSdQorOwBRVSi2Xx4ELxgwgcXnXKGXEluKyQihKidJdluuyJfQba85vM3J3Za8E6ValbCQrIZcFadZlzOZcerumLyjmSfIWBSrkgalOSIA%2BwsRmljglg5y9kiL%2FmRVaKsBWNfSLZNabSVcVKdY%2FQoKP2GbeHdcxqmzs6ZQrle1HVFr7B8DJIjOpoSdtoAHQNweoBWB1CRNs0jyTZ%2Fstw7i0MPoYHwQyv4dHj46%2BZ%2FFSWfFyydsnjRvtBO3I12ytmufwo5etq7djBQ%2BLOnozHFy81zqXBCW%2BjkYGLikmz%2Fu%2FO32rbaPt2u%2B3QhoXh%2FPO7z%2FWNzJOvlOFtM9YX1%2F9Zc0mv28vJZPiaf3XbvjFY%2Fs3cu9DLeXmNEdJt3E1XEIC1qsDFVaIx7vg8gI6xIDAMQur5Y%2Fvsl168%3D

&RelayState=https%3A%2F%2Fpz.127.0.0.1.xip.io%3A8443%2Fconfmgr%2Findex.php%2Fpsguardians%2Findex%3Fidpmetadata%3Dhttps%253A%252F%252Fksd.powerschool.com%253A443%252Fpowerschool-saml-sso%252Fmetadata%252FconfmgrMetadata.action%26studentContext%3Dtrue

&SigAlg=http%3A%2F%2Fwww.w3.org%2F2001%2F04%2Fxmldsig-more%23rsa-sha256

&Signature=yxGuKX2f2JqTvdqEcV4KfjvtIevEa8ZX5DKD5m3ryjYiwfhUFgNhr3IEHVz9oxzLipwreupA8aUQ%2Fvl918x9EvZXqfeYiRyJyQz28cnqjSIwALKYSY3emHf4qvZ0Gq1s7GMAdufeg0bbok3xhrR9rKxe45j4C5YrGwKp5W48xm8%3D


Logging in from PowerSchool
---------------------------

From simplesamlphp-sp-migration.txt:

#### Overview

This is a quick overview of the API:

    /* Get a reference to our authentication source. */
    $as = new SimpleSAML_Auth_Simple('confmgr');

    /* Require the user to be authentcated. */
    $as->requireAuth();
    /* When that function returns, we have an authenticated user. */

    /*
     * Retrieve attributes of the user.
     *
     * Note: If the user isn't authenticated when getAttributes() is
     * called, an empty array will be returned.
     */
    $attributes = $as->getAttributes();

    /* Log the user out. */
    $as->logout();




Debugging
---------

The Java Spring Security SAML2 plugin supplied by PowerSchool works.

Analyzing the difference between the AuthNRequest messages posted by the Spring example and
by simplesamlphp, these are the two attributes that are missing in simplesamlphp:

    <saml2p:AuthnRequest
      ForceAuthn="false" 
      IsPassive="false" ...>

And simplesamlphp also adds this element:

    <samlp:NameIDPolicy 
      Format="urn:oasis:names:tc:SAML:2.0:nameid-format:transient" 
      AllowCreate="true"/>

So trying with a few more metadata directives.

Flow for the Spring example:

137.164.121.242 - - [14/Sep/2015:23:58:22 +0000] "GET /spring-security-saml2-sp/saml/login/alias/defaultAlias?disco=true&idpmetadata=https%3A%2F%2Fksd.powerschool.com%3A443%2Fpowerschool-saml-sso%2Fmetadata%2FspringsecuritysamlMetadata.action&studentContext=true HTTP/1.1" 302 -
137.164.121.242 - - [14/Sep/2015:23:58:23 +0000] "POST /spring-security-saml2-sp/saml/SSO/alias/defaultAlias HTTP/1.1" 302 -
137.164.121.242 - - [14/Sep/2015:23:58:23 +0000] "GET /spring-security-saml2-sp/index.jsp HTTP/1.1" 200 9034

Will try without SSL?



