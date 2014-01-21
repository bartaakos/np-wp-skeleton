<?php
/*
This is a sample local-config.php file
In it, you *must* include the four main database defines

You may include other settings here that you only want enabled on your local development checkouts
*/

define( 'DB_NAME', 'database' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' ); // Probably 'localhost'

define( 'WP_LOCAL_DEV', true );
define( 'WP_CONTENT_URL', 'http://example.com/content' );