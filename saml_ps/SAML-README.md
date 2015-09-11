
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
