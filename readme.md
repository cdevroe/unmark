Unmark
============

An open source to-do application for bookmarks.

We offer this source code for Unmark completely free. We do so in hopes that Unmark will live on for many years even if we stop maintaining it. To support its further development please consider subscribing to the hosted version at [unmark.it](https://unmark.it) (which is free with an option to pay). Or, you can simply [donate via Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XSYNN4MGM826N). Or you can contribute to the code.

Here are some useful links:

- [Download the latest release](https://github.com/plainmade/unmark/releases) - Grab the latest and greatest version of Unmark.
- [Unmark.it](https://unmark.it) - Sign up for free to the hosted version.
- [Unmark Help](http://help.unmark.it) - Get a little help with Unmark.

Support
----------------

We want you to have a good experience using Unmark so we do offer some support via Slack, and Twitter. Follow [@unmarkit](https://twitter.com/unmarkit) on Twitter and send us a tweet. Or, send us a tweet to get access to our Slack channels.

Installation
----------------

The local-version of Unmark is recommended for at least intermediate users so the instructions are rather light on detail. However, if you need assistance beyond what is provided please ping us on Twitter. Alternatively, if you have issues setting up Unmark after following the instructions, we recommend [looking through the issues on GitHub](https://github.com/plainmade/unmark/issues) to see how others have solved their problems. Or, to create your own issue.

### Technical requirements

- PHP 5.4 or greater with gettext extension. Need help? [Try this](http://php-osx.liip.ch). (we have every intention of removing the need for gettext extension soon)
- mySQL 5.5 or greater

### Instructions

- Download [the latest release](https://github.com/plainmade/unmark/releases) or clone the repo.
- Unpack the archive
- Copy `/application/config/database-sample.php` to `/application/config/database.php` (leave `database-sample.php` where it is if you cloned the repo)
- Create a database for Unmark to use in mySQL (may we recommend "unmark" as a database name?)
- Fill in proper database credentials in `/application/config/database.php`
- Optionally: Update your HOSTS file and create a virtual host for Unmark. We use "unmark.local" most of the time.
- Point your browser to `/setup`
- From there Unmark will set up the proper database tables and then ask you to register your username with the app
- Optional: Change base_url in config.php to your local URL. This will help build proper URLs in some cases.

Note: Using Nginx rather than Apache? Follow [these Nginx configuration instructions](https://github.com/plainmade/unmark/wiki/Nginx-Configuration).

### Upgrading to the latest release

- Download [the latest release](https://github.com/plainmade/unmark/releases).
- Replace all files (keeping your local `/application/config/database.php` intact.)
- Optional: If you changed base_url in config.php to your local URL make this update
- Navigate to `your-unmark-url/upgrade`
- Unmark will then make any needed database updates
- That's it!

How to contribute to Unmark
----------------------------

One major way you can contribute is to report any issues you find with Unmark on Github and being as verbose as you can be for us to replicate the issue. This goes a long way in making Unmark better for everyone on every type of set up.

Another way is to contribute your own code via Pull Requests. Here are some notes on how to do that.

- Fork [the repository on GitHub](https://github.com/plainmade/unmark/) into your own account
- Create your own branch `git checkout -b your-branch-name`
- Update your code and push those code changes back to your fork's branch `git push origin your-branch-name`
- [Submit a Pull Request](https://github.com/plainmade/unmark/pulls) using that branch

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

History
----------------------------
Unmark was originally created by [Colin Devroe](http://cdevroe.com/). It was a side-project called Nilai (the Indonesian word for "mark") and rebuilt from the ground up by [Plain](http://plainmade.com/) which included Jeff Johns, Kyle Ruane, Tim Whitacre, Chris Fehnel, Jakub Jakubiec and Colin Devroe.

Now it is being maintained by Colin and Kyle and the community. Please consider donating, subscribing to the hosted version, or contributing code in order to keep Unmark alive and well.

Contributors
----------------------------

Currently being maintained by: [@cdevroe](https://github.com/cdevroe) and [@kyleruane](https://github.com/kyleruane) and you?

With special thanks to: [@phpfunk](https://github.com/phpfunk) (wrote most of Unmark), [@twhitacre](https://github.com/twhitacre) (wrote most of the front-end), [@kip9](https://github.com/kip9) (wrote the languages and migration back-up bits), [@cfehnel](https://github.com/cfehnel) (support master extraordinaire)

Also contributions by [@thebrandonallen](https://github.com/thebrandonallen), [@simonschaufi](https://github.com/simonschaufi), [@williamknauss](https://github.com/williamknauss), [@hewigovens](https://github.com/hewigovens)
