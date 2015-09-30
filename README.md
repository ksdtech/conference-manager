Conference Manager Application
==============================

Installation
------------

This application is built on CodeIgniter 3.0.0.  Here's how to install.

1. Copy or clone the project to a folder that can be served by your PHP web server.

2. Copy application/config/config-sample.php to application/config/config.php and adjust settings,
most notably the 'base_url'.  Set this to whatever host, port and alias your application is running
at, like:

    ```
    $config['base_url'] = 'http://localhost:8080/conference-manager/'
    ```

Note: If you don't use a webserver 'rewrite' feature, all of the URLs in the conference manager 
application will have "index.php" attached to them, like this:

http://localhost:8080/conference-manager/index.php/admin/users

Other host-specific settings you will need to adjust (and make sure these are chmod 777)

    ```
    $config['upload_path'] = '/path/to/uploads'
    $config['sess_save_path'] = '/path/to/session/store'
    ```

3. Add timezone to php.ini: 

    ```
    date.timezone = "America/Los_Angeles"
    ```

4. Copy application/config/database-sample.php to application/config/database.php and adjust your
database connection settings.

5. Create the database, database user and grant the user all permissions, per the settings in you
database.php configuration file.

6. Run (import) the database initialization file, sql/conference-manager.sql.  This file is designed
for MySQL (modify to use with other SQL systems).

7. Install the "community_auth for CodeIgniter 3" library, found at http://community-auth.com.
Download the latest stable package, unzip it, and copy the application/third_party/community_auth
folder and all its subfolders to the the application/third_party folder of the conference manager
project.

8. Install the Oauth2 client library in application/vendor

9. Install simplesamlphp in the project root.

10. Create an admin user in the database using the command line. Change to the project directory,
then enter: 

php index.php admin/users add_cli <first_name> <last_name> <url_encoded_email> <password> 9

The user will be created with the username, password and email address passed to the add_cli
method in the admin/users controller. Note that the email and password must be URL-safe 
(for instance the '@' character in the email address must be encoded as '%40').  The '9'
specifies the admin user level (this script can be used to create regular users also).

11. Check that the user was created by going to the URL:

<base_url>/index.php/admin/users


