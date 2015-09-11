
Installing the SAML IdP Plugin in PowerSchool
---------------------------------------------

1. Adjust the parameters in the plugin.xml file in this folder according to your instance's Apache configuration.

2. Then install (upload) in PowerSchool Admin > System Settings > Plugin Managmenent Configuration.

3. After installing, click the plugin's name and then the "Single Sign-On Service" function link.

4. Copy the PowerSchool SAML IdP Entity ID to simplesamlphp's authsources.php configuration file.

After installation, the PowerSchool plugin places a link in the Parent Portal (Guardian) header. Click the
up-right arrow ("Applications") icon in the header bar. That will pop up a dialog containing a link 
with the plugin's name.  The URL for the link will look like this:

    ```
    http://pz.127.0.0.1.xip.io:8080/confmgr/index.php/psguardians/index?idpmetadata=https%3A%2F%2Fksd.powerschool.com%3A443%2Fpowerschool-saml-sso%2Fmetadata%2FConfMgrMetadata.action&studentContext=true
    ```

The URL path is concatenated from the plugin.saml.base-url and the plugin.saml.links.link.path values.

The URL query string shows the Shibboleth metadata link (requires cookies) at: 

    ```
    https://ksd.powerschool.com:443/powerschool-saml-sso/metadata/ConfMgrMetadata.action
    ```

SimpleSAMLphp Configuration
---------------------------

1. Bindings

PowerSchool says that their IdP will support these bindings as of version 9.0:

    ```
    <ns4:SingleSignOnService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="https://127.0.0.1:443/powerschool-saml-sso/profile/SAML2/Redirect/SSO" />
    <ns4:SingleSignOnService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="https://127.0.0.1:443/powerschool-saml-sso/profile/SAML2/POST/SSO" />
    ```

The metadata file that is returned by simpleSAMLphp looks like the default-sp.xml file in this directory.

2. config/authsources.php


3. metadata/saml20-idp-remote.php


Logging in from PowerSchool
---------------------------

From simplesamlphp-sp-migration.txt:

#### Overview

This is a quick overview of the API:

    /* Get a reference to our authentication source. */
    $as = new SimpleSAML_Auth_Simple('default-sp');

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

Error Message: Error decoding authentication request message

https://ksd.powerschool.com/powerschool-saml-sso/profile/SAML2/POST/SSO

  ?SAMLRequest=hVJRb9sgGPwrFu82htpJjJJIWaNpkbo1qtM%2B7GWigBs0DIwPr91%2BfbG9SdlLJh6QjrvvvjuxBt4bz3ZDPNsH9WNQELO33lhg08MGDcEyx0EDs7xXwKJg7e7zHaNFyXxw0Qln0IXkuoIDqBC1syg77Dfo25JK2dX1iqqGLwgnlMhGUN5JWS0ryZuGLsiifF7UFcqeVICk3KA0KMkBBnWwELmNCSpJnZdNTsiprBi5YXX5FWX7lEZbHifVOUYPDOPvIAvvXtMwcXbOFML1rKpu8AWWj0FyAIdTwk4bhccAFB%2Fv2xNu23uU7f7muHUWhl6FVoWfWqjHh7vZKRn53wWhy6JMhxRv2hfasVW5KrFwtutfQg4e904ORhX%2B7PHoiWG%2Bac4FTKhUHR9MTFyUHf%2FU%2FUFbqe3L9aafZxKwT6fTMR83R9v1OJtNzYXtf7Zc40vyev4mX5LNYX90Rotf2UcXeh6vbzEiWubdRGUxcAta2ZgKNMa93gbFo9qgGAaF8Ha2%2FPczbt8B

  &RelayState=http%3A%2F%2Fpz.127.0.0.1.xip.io%3A8080%2Fconfmgr-sp%2Fmodule.php%2Fcore%2Fauthenticate.php%3Fas%3Ddefault-sp
