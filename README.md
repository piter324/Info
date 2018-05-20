# How to put Info on a server
## You need:
* Apache 2 web server
* PHP version >=5
* PHP MySQL connection component (with mysqli functions)
* MySQL database server on the same server as Apache with PHP

## Steps:
1. Put all files from this repo apart from *config_info.ini* and *database.sql* files into a public directory on your webserver.
1. Put *config_info.ini* in a non-public folder on your webserver one step in a folder tree *above* all public files.
1. Enter username and password for MySQL user who will be used to access the database automatically by the app into *config_info.ini* file (this user should have INSERT, DELETE, UPDATE priviliges). Also enter the name of the database (by default: *info*) there.
1. Create a database and a user with names specified in the \*.ini file from above.
1. Enter that database, then load and execute *database.sql* file.

You're good to go. Happy testing!
