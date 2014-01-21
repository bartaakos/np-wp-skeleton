# np-wp-skeleton

WordPress skeleton based on nDeploy and wp-cli

With this skeleton now you can implement a well-maintained WordPress site.

## Sources

* [Mark Jaquith's Wordpress Skeleton](https://github.com/markjaquith/WordPress-Skeleton/)
* [wp-cli](https://github.com/wp-cli/wp-cli)
* [Netpositive's nDeploy](https://github.com/Netpositive/ndeploy)
 
This skeleton itself is very similar to the one Mark Jaquith created but now it is enhanced with a powerful deployment method.  

## Basic Information

### Files and Folders

<pre>
/content/ -> the version controlled folder for wp-content with all the templates and plugins you use
/protected/ -> general folder for some important files (e.g. common database dump)
/wp-config.php -> almost the same as Mark Jaquith's
/local-config-sample.php -> a sample file for local configuration
/ndeploy.xml -> an extension for the deploy script which creates a symlink to the config file inside the shared wp folder
</pre>

### Shared files

Shared files are kept out of the repository and we (or at least our deployscript) create symlinks for them to the prod directory.

* wp: a clean WordPress
* content/uploads: folder for uploaded files (needs to be writeable)
* local-config.php: contains environmental parameters

## Implementation

Let's assume that we have a user called **example** on our server (*/home/example*) and the target website's domain is gonna be *example.com* and we named our repository *example* as well.

### wp-cli

The first step is to install wp-cli, which will help us keep the framework and the plugins up-to-date.

For detailed description please visit http://wp-cli.org/#install

<code>curl -L https://download-url-here > wp-cli.phar</code>

Let's give it executable permissions.

<code>chmod +x wp-cli.phar</code>

In order to user just <code>wp</code>:

<code>mv wp-cli.phar /usr/bin/wp</code>

### nDeploy

nDeploy is a phing based deploy script for php ecosystem, for detailed setup information please visit https://github.com/Netpositive/ndeploy 

After cloning ndeploy go to your home and run: <code>phing -f /path/where/you/installed/ndeploy/build.xml -q</code> and choose the options you want.

Note that the **shared files** are the following: wp,content/uploads,local-config.php

<pre>
example@server:~$ phing -f /home/ndeploy/current/build.xml -q
     [echo] Welcome to ndeploy build.properties skeleton generator!
Application name? example
Application basedir [/home/example]?
Application framework (yii,symfony2,symfony,) []?
Releases kept [100]? 20
SCM type (git,svn) [git]?
SCM repository? ssh://example@git.example.org/example.git
Shared files? wp,content/uploads,local-config.php
Vendor type(composer,sf2vendors,custom,none) [composer]? none
ndeploy lib [/home/ndeploy/current]?
     [echo] Edit /home/example/build.properties

BUILD FINISHED
</pre>

You might want to run a <code>phing</code> in order to clone the repository and create the (empty) shared files.

The next step is to update the shared files:

<code>example@server:~/shared$ cp /home/example/current/local-config-sample.php local-config.php</code>

<code>example@server:~/shared$ chmod 0777 content/uploads -R</code>

Downloading WordPress with wp-cli using some attributes:

<code>example@server:~/shared$ wp core download --path=wp --version=3.8 --locale=hu_HU</code>

Now you can run <code>phing</code> again and it should work.

### THE MAGIC

The purpose of this project is that there is a very important option that **MUST** be set for this environment:

<pre>
mysql> select * from wp_options where option_name IN ('siteurl', 'home');
+-----------+-------------+-----------------------+----------+
| option_id | option_name | option_value          | autoload |
+-----------+-------------+-----------------------+----------+
|        36 | home        | http://example.com    | yes      |
|         1 | siteurl     | http://example.com/wp | yes      |
+-----------+-------------+-----------------------+----------+
2 rows in set (0.00 sec)
</pre>

You must append <code>/wp</code> after siteurl in order to make the site work well.
