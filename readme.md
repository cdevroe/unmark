Unmark
============

The open source to-do application for bookmarks.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](./LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/cdevroe/unmark)](../../issues)
[![GitHub pull requests](https://img.shields.io/github/issues-pr/cdevroe/unmark)](../../pulls)
[![Donate](https://img.shields.io/badge/Donate-388307)](https://cdevroe.com/donate)

## Installation

The easiest way to use Unmark is by signing up to [Unmark.it](https://unmark.it/) where we host the application for you. However, you can download and install Unmark and run it locally completely free.

[Download the latest release](https://github.com/cdevroe/unmark/releases) - Grab the latest and greatest version of Unmark.

Running Unmark is recommended for intermediate users. This doesn't mean if you're a beginner we don't want you to try. Hack away! Just that you should expect some speedbumps (though, we're eliminating them all the time). If you need assistance beyond what is provided please [create an issue on Github](https://github.com/cdevroe/unmark/issues). Before creating a new issue we recommend [search through the issues on GitHub](https://github.com/cdevroe/unmark/issues) to see how others have solved their problems.

### Technical requirements

It is now recommended to use Docker / Docker Compose to install and run Unmark locally both for personal use and for development. Please see the installation instructions section below.

However, if you're going to run your own server:

- Apache 2.x
- PHP 7.x or greater
- mySQL 5.7 or greater

### Installation Instructions

#### With Docker / Docker Compose

We've included Docker Compose, Dockerfile, and PHP.ini files to run Unmark locally on Windows or Mac.

### How to start Unmark via Docker for personal use
1. Download and install [Docker](https://docs.docker.com/get-docker/)
1. Download and install [Docker Compose](https://docs.docker.com/compose/install/)
1. Download [the latest release](https://github.com/cdevroe/unmark/releases)
1. Unpack the archive into your desired location
1. Rename the file `/application/config/database-sample.php` to `/application/config/database.php`
1. In Terminal or Powershell - Run `docker-compose up -d` (to shut Unmark down run `docker-compose down`)
1. Navigate to [http://localhost](http://localhost) and click "Install"
1. If successful, you'll be asked to create an account

#### How to start Unmark via Docker for development
1. Download and install [Docker](https://docs.docker.com/get-docker/)
1. Download and install [Docker Compose](https://docs.docker.com/compose/install/)
1. Run `git clone https://github.com/cdevroe/unmark.git` (Or, if you've forked the repo, use your URL)
1. **Copy the file** `/application/config/database-sample.php` to `/application/config/database.php` (leave `database-sample.php` in place)
1. Rename the file `/application/config/database-sample.php` to `/application/config/database.php`
1. Run `docker-compose up -d` (to shut Unmark down run `docker-compose down`)
1. Run `npm install`
1. Run `grunt` [more info on Grunt](http://gruntjs.com/)
    - To run Grunt you'll need to also install Ruby and the [SASS gem](https://sass-lang.com/ruby-sass)
1. Navigate to [http://localhost](http://localhost) and click "Install"
1. If successful, you'll be asked to create an account

### Upgrading to the latest release

#### From Release
1. Download [the latest release](https://github.com/cdevroe/unmark/releases)
1. Shut down Unmark `docker-compose down`
1. Replace all Unmark files (keeping your local `/application/config/database.php` intact.)
1. Navigate to [http://localhost/upgrade](http://localhost/upgrade)
1. Unmark will then make any database updates if needed
1. That's it!

#### From git repository
1. Run `git pull origin main`
1. Run `npm update` in the app's root directory
1. Run `grunt` in the app's root directory
1. Navigate to [http://localhost/upgrade](http://localhost/upgrade)
1. Unmark will then make any database updates if needed
1. That's it!

### Importing bookmarks

Unmark currently supports importing from Unmark's hosted version, any self-hosted version of Unmark, Readability, Pinboard, Delicious, Pocket and many other services via HTML.

## History

Unmark was originally created by [Colin Devroe](http://cdevroe.com/). It was a side-project called Nilai (the Indonesian word for "mark") and rebuilt from the ground up by Plain, a small software company, which included Jeff Johns, Kyle Ruane, Tim Whitacre, Chris Fehnel, Jakub Jakubiec and Colin Devroe.

Now it is being maintained by Colin, Kyle and the community in their spare time.

## Contributors

Currently being maintained by: [@cdevroe](https://github.com/cdevroe) and [@kyleruane](https://github.com/kyleruane).

Extra special thanks to: 

- [@phpfunk](https://github.com/phpfunk) - who wrote most of Unmark's original codebase
- [@twhitacre](https://github.com/twhitacre) - who wrangled the original JS
- [@kip9](https://github.com/kip9) - wrote the languages and migration back-up bits
- [@cfehnel](https://github.com/cfehnel) - who handled support for the app

Also contributions by [@thebrandonallen](https://github.com/thebrandonallen), [@simonschaufi](https://github.com/simonschaufi), [@williamknauss](https://github.com/williamknauss), [@hewigovens](https://github.com/hewigovens)