Conference Manager Application
==============================

Installation
------------

This application is built on CodeIgniter 3.0.0.  Here's how to install.

1. Copy or clone the project to a folder that can be served by your PHP web server.

2. Copy application/config/config-sample.php to application/config/config.php and adjust settings,
most notably the 'base_url'.  Set this to whatever host, port and alias your application is running
at, like:

http://localhost:8080/conference-manager/

Note: If you don't use a webserver 'rewrite' feature, all of the URLs in the conference manager 
application will have "index.php" attached to them, like this:

http://localhost:8080/conference-manager/index.php/admin/users

3. Copy application/config/database-sample.php to application/config/database.php and adjust your
database connection settings.

4. Create the database, database user and grant the user all permissions, per the settings in you
database.php configuration file.

5. Run (import) the database initialization file, sql/conference-manager.sql.  This file is designed
for MySQL (modify to use with other SQL systems).

6. Install the "community_auth for CodeIgniter 3" library, found at http://community-auth.com.
Download the latest stable package, unzip it, and copy the application/third_party/community_auth
folder and all its subfolders to the the application/third_party folder of the conference manager
project.

7. Create an admin user in the database by going to the special URL 

<base_url>/index.php/examples/create_user

The user will be created with the username, password and email address hard-coded in the file
application/controllers/Examples.php.  After creating this user, you can edit the username,
password and email address by going to 

<base_url>/index.php/admin/users
