# Tuileries

This is a WordPress theme based on the [Sage](https://github.com/roots/sage/) and [_s](https://github.com/Automattic/_s) starter themes, with a little bit of extra Craftpeak magic thrown in.

The goal for this project is a theme compatible with [Commons In A Box](http://commonsinabox.org/). Find planning documents on [the wiki](https://github.com/mlaa/cbox-mla/wiki/CBOX-MLA-3.0-Planning).

# Installing this on AWS

1. Spin up a new AWS instance on Vagrant, with `vagrant up $hostname --provider=aws`, where `$hostname` is your name for the new instance. 
2. Ssh into the new box with `vagrant ssh $hostname`, and run the box installation script: `/srv/www/commons/bin/install-commons.sh`
3. Make a copy of the `cbox-mla` theme, and call it `tuileries`: `cd /srv/www/commons/current/web/app/themes && cp -r cbox-mla tuileries`
4. Check out the `develop` branch: `cd tuileries && git fetch && git checkout -b develop origin/develop`. 
5. Run the rollout script, after reading it first: `cd scripts && ./rollout.sh`. 
6. Get npm: `sudo apt-get install npm`
7. Upgrade node, since Ubuntu's version is too old for this stack: `sudo apt-get install curl && curl -sL https://deb.nodesource.com/setup_0.12 | sudo bash - && sudo apt-get install nodejs`
8. Symlink ubuntu's `nodejs` binary to `node`, which is what node modules will expect: `sudo ln -s /usr/bin/nodejs /usr/bin/node`. 
9. Install node dependencies: `npm install`. This will take a while. 
10. Install bower: `sudo npm install -g bower`. 
11. Install bower dependencies: `bower install`. 
12. Edit manifest.json, changing the value of `devUrl` to your $hostname: `vim assets/manifest.json`
13. Install gulp globally: `sudo npm install -g gulp`
14. Build the project: `gulp`. 

# Status

Nothing works yet. Test at your own risk!

## Requirements

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Node.js 0.12.x  | `node -v`    | [nodejs.org](http://nodejs.org/) |
| gulp >= 3.8.10  | `gulp -v`    | `npm install -g gulp` |
| Bower >= 1.3.12 | `bower -v`   | `npm install -g bower` |

For more installation notes, refer to the [Install gulp and Bower](#install-gulp-and-bower) section in this document.

## Features

* [gulp](http://gulpjs.com/) build script that compiles both Sass and Less, checks for JavaScript errors, optimizes images, and concatenates and minifies files
* [BrowserSync](http://www.browsersync.io/) for keeping multiple browsers and devices synchronized while testing, along with injecting updated CSS and JS into your browser while you're developing
* [Bower](http://bower.io/) for front-end package management
* [asset-builder](https://github.com/austinpray/asset-builder) for the JSON file based asset pipeline
* [normalize.css](http://necolas.github.io/normalize.css/) for a nice CSS reset
* [Theme wrapper from Sage](https://roots.io/sage/docs/theme-wrapper/)
* ARIA roles and microformats
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat

## Installation

Add the following to your `wp-config.php` on your development installation:

```php
define('WP_ENV', 'development');
```

## Configuration

Edit `lib/config.php` to enable or disable theme features

Edit `lib/init.php` to setup navigation menus, post thumbnail sizes, post formats, and sidebars.

## Theme development

Sage uses [gulp](http://gulpjs.com/) as its build system and [Bower](http://bower.io/) to manage front-end packages.

### Install gulp and Bower

Building the theme requires [node.js](http://nodejs.org/download/). We recommend you update to the latest version of npm: `npm install -g npm@latest`.

From the command line:

1. Install [gulp](http://gulpjs.com) and [Bower](http://bower.io/) globally with `npm install -g gulp bower`
2. Navigate to the theme directory, then run `npm install`
3. Run `bower install`

You now have all the necessary dependencies to run the build process.

### Available gulp commands

* `gulp` — Compile and optimize the files in your assets directory
* `gulp watch` — Compile assets when file changes are made
* `gulp --production` — Compile assets for production (no source maps).

### Using BrowserSync

To use BrowserSync during `gulp watch` you need to update `devUrl` at the bottom of `assets/manifest.json` to reflect your local development hostname.

For example, if your local development URL is `http://project-name.dev` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://project-name.dev"
  }
...
```
If your local development URL looks like `http://localhost:8888/project-name/` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://localhost:8888/project-name/"
  }
...
```

## Documentation

Sage documentation is available at [https://roots.io/sage/docs/](https://roots.io/sage/docs/).

## Coding Standards

This theme follows the [WordPress Coding Standards](https://codex.wordpress.org/WordPress_Coding_Standards) with the exception of the gulpfile, which loosely follows the [node style guide](https://github.com/felixge/node-style-guide). Checking how well we're doing via PHPCS and JSCS with Travis is coming - stay tuned!

## Contribution

Contributing is welcomed and encouraged! Please open an [issue](https://github.com/mlaa/cbox-mla/issues) or a [pull request](https://github.com/mlaa/cbox-mla/pulls) if you have anything to add.
