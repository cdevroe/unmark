Unmark
============

An open source to-do application for bookmarks.

We offer this source code for Unmark completely free. We do so in hopes that Unmark will live on for many years even if we stop maintaining it.

To support its further development please consider [donating via Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XSYNN4MGM826N). Or, you can contribute to the code.

- [Download the latest release](https://github.com/plainmade/unmark/releases) - Grab the latest and greatest version of Unmark.


## Installation

The local-version of Unmark is recommended for at least intermediate users so the instructions are rather light on detail. However, if you need assistance beyond what is provided please ping us on Twitter. Alternatively, if you have issues setting up Unmark after following the instructions, we recommend [looking through the issues on GitHub](https://github.com/cdevroe/unmark/issues) to see how others have solved their problems. Or, to create your own issue.

### Technical requirements

- PHP 5.4 or greater
- mySQL 5.5 or greater

### Installation Instructions

#### From Zip (binary)
- Download [the latest release](https://github.com/cdevroe/unmark/releases)
- Unpack the archive into your desired location
- Rename `/application/config/database-sample.php` to `/application/config/database.php`
- Create a database for Unmark to use in mySQL (may we recommend "unmark" as a database name?)
- Fill in proper database credentials in `/application/config/database.php`
- Optionally: Update your HOSTS file and create a virtual host for Unmark. We use "unmark.local" as an example.
- Point your browser to `/setup` on your local Unmark domain E.g. unmark.local/setup
- From there Unmark will set up the proper database tables and then ask you to register your username with the app

#### From git repository
- Run `git clone https://github.com/cdevroe/unmark.git` (Or, if you've forked the repo, use your URL)
- Copy `/application/config/database-sample.php` to `/application/config/database.php` (leave `database-sample.php` where it is if you cloned the repo)
- Create a database for Unmark to use in mySQL (may we recommend "unmark" as a database name?)
- Fill in proper database credentials in `/application/config/database.php`
- Optionally: Update your HOSTS file and create a virtual host for Unmark. We use "unmark.local" as an example.
- Run `npm install` from the application's root directory
- Run `grunt` from the app's root directory [more info on Grunt](http://gruntjs.com/)
- Point your browser to `/setup` on your local Unmark domain E.g. unmark.local/setup
- From there Unmark will set up the proper database tables and then ask you to register your username with the app

### Upgrading to the latest release

#### From Zip (binary)
- Download [the latest release](https://github.com/plainmade/unmark/releases)
- Replace all Unmark files (keeping your local `/application/config/database.php` intact.)
- Navigate to `/upgrade`
- Unmark will then make any needed database updates
- That's it!

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> master
#### From git repository
- Run `git pull origin master`
- Run `npm update` in the app's root directory
- Run `grunt` in the app's root directory
- Navigate to `/upgrade`
- Unmark will then make any needed database updates
- That's it!

<<<<<<< HEAD
=======
### Importing bookmarks

Unmark currently supports importing from Unmark's hosted version, any self-hosted version of Unmark, Readability, Pinboard, Delicious, and many other services. However, to ensure this works properly be sure that your PHP.ini file's "max_upload_size" setting is larger than the file you're trying to import.
>>>>>>> master
=======
### Importing bookmarks

Unmark currently supports importing from Unmark export file, Readability, Pinboard, Delicious, and many other services. However, to ensure this works properly be sure that your PHP.ini file's "max_upload_size" setting is larger than the file you're trying to import.
>>>>>>> master

## How to contribute to Unmark

One major way you can contribute is to report any issues you find with Unmark on Github and being as verbose as you can be for us to replicate the issue. This goes a long way in making Unmark better for everyone on every type of set up.

Another way is to contribute your own code via Pull Requests. Here are some notes on how to do that.

- Fork [the repository on GitHub](https://github.com/plainmade/unmark/) into your own account
- Create your own branch `git checkout -b your-branch-name`
- Update your code and push those code changes back to your fork's branch `git push origin your-branch-name`
- [Submit a Pull Request](https://github.com/plainmade/unmark/pulls) using that branch
- And please accept our _thanks_!

This makes it easy for us to test your code locally and also allows the community to have a discussion around it.

Just a note: We use [Grunt](http://gruntjs.com/) to compile our SASS files into CSS and concatenate and compress our JavaScript files for use and a few other small tasks. For any updates to JavaScript or styles you will need to use Grunt too. See the Grunt web site or ping us on Slack for help.

#### Creating a release

- Be sure that index.php's default environment variable is "production"
- Be sure version variable in config.php is accurate
- Compile, check-in, and commit compiled assets by running Grunt
- Merge into master
- Test
- Push master to Github
- Tag release via Github

## Support via Twitter and Email

We want you to have a good experience using Unmark so we do offer some support via Twitter. Follow [@unmarkit](https://twitter.com/unmarkit) on Twitter and send us a tweet.

You can find an email to send a note from within the app by choosing Settings > Contact Support.

## History

Unmark was originally created by [Colin Devroe](http://cdevroe.com/). It was a side-project called Nilai (the Indonesian word for "mark") and rebuilt from the ground up by [Plain](http://plainmade.com/) which included Jeff Johns, Kyle Ruane, Tim Whitacre, Chris Fehnel, Jakub Jakubiec and Colin Devroe.

Now it is being maintained by Colin and Kyle and the community. Please consider donating or contributing code in order to keep Unmark alive and well.

## Contributors

Currently being maintained by: [@cdevroe](https://github.com/cdevroe) and [@kyleruane](https://github.com/kyleruane).

Also contributions by [@thebrandonallen](https://github.com/thebrandonallen), [@simonschaufi](https://github.com/simonschaufi), [@williamknauss](https://github.com/williamknauss), [@hewigovens](https://github.com/hewigovens)

Extra special thanks to: [@phpfunk](https://github.com/phpfunk) (wrote most of Unmark), [@twhitacre](https://github.com/twhitacre) (wrote most of the front-end), [@kip9](https://github.com/kip9) (wrote the languages and migration back-up bits), [@cfehnel](https://github.com/cfehnel) (support master extraordinaire)
