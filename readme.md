Unmark
============

An open source, to-do application for bookmarks originally created by [Colin Devroe](http://cdevroe.com/) (then called Nilai) and rebuilt from the ground up by [Plain](http://plainmade.com/).

We offer this source code for Unmark completely free. However, if you'd rather us host it for you, as well as a few features not available through this repository, you can sign up for free to [our Unmark hosted service](http://unmark.it). It is free to try with limitations and just a few dollars per year to unlock all features with no limitations.

Here are some useful links:

- [Unmark.it](https://unmark.it) - Sign up for free to the hosted version of Unmark.
- [Unmark Wiki](https://github.com/plainmade/unmark/wiki) - Need more help? Follow along the wiki.
- [Unmark Help](http://help.unmark.it) - Get a little help with Unmark.
- [Donate](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XSYNN4MGM826N) - Help keep Unmark development alive!


Installation
----------------

Please read through the installation requirements carefully. We're keeping them up-to-date to, hopefully, minimize the number of installation issues some have had with earlier versions of Unmark. If you still have issues setting up Unmark after following the instructions, we recommend [looking through the issues on GitHub](https://github.com/plainmade/unmark/issues) to see how others have solved their problems.

### Technical requirements

- PHP 5.4 or greater with gettext extension. Need help? [Try this](http://php-osx.liip.ch).
- mySQL 5.5 or greater

### Installation Instructions

- Download [the latest release](https://github.com/plainmade/unmark/releases) or clone the repo.
- Unzip the archive into ~/Sites or your directory of choice
- Copy `/application/config/database-sample.php` to `/application/config/database.php` (leave `database-sample.php`)
- Create a database for Unmark to use in mySQL
- Fill in proper database credentials in `/application/config/database.php`
- Point your browser to `your-unmark-url/setup` (note that Unmark will error until `/setup` has been successfully completed)
- Follow the onscreen instructions to complete setup

Note: Using Nginx rather than Apache? Follow [these Nginx configuration instructions](https://github.com/plainmade/unmark/wiki/Nginx-Configuration).


### Upgrading to the latest release

- Download [the latest release](https://github.com/plainmade/unmark/releases).
- Replace all files (keeping your local `/application/config/database.php` intact.)
- Navigate to `your-unmark-url/upgrade`
- That's it!

Note: You can also run this from the command line using the following command `php index.php migrations latest`


Building Unmark locally
----------------------------

We use [Grunt](http://gruntjs.com/) to compile our SASS files into CSS and concatinate and compress our JavaScript files for use and a few other small tasks. If you'd like to update your local copy of Unmark do the following...

- Edit SASS in `/assets/css`
- Edit JavaScript in `/assets/js`
- When finished, run "grunt" via command line

Note: For additional information [check out our Grunt guide](https://github.com/plainmade/nilai/wiki/Grunt) on the Unmark wiki.


Staging branch
----------------------------

Generally our **staging** branch will always be kept up to date with the **master** branch unless we are testing a feature or release. We use this branch internally to test on our staging server. You can feel free to use this at any time, just note that Unmark may not always work. So we recommend only those that are helping us test Unmark pull this branch.

Just prior to a release we'll merge staging into master and tag the release using GitHub's Releases feature.


How To Contribute
----------------------------

We are now managing the entire roadmap for Unmark via [GitHub issues](http://github.com/plainmade/unmark/issues). One major way you can contribute to Unmark is to report any issues you find with, or ideas you have for, Unmark and being as verbose as you can be for us to replicate the issue.

### Submitting code

- Fork [the repository on GitHub](https://github.com/plainmade/unmark/).
- Update your code and push those code changes back to your fork.
- [Submit a Pull Request](https://github.com/plainmade/unmark/pulls).

### Donations

We charge a very small yearly fee for the hosted version of Unmark. You can simply [subscribe to Unmark](http://unmark.it) as a way of donating - or, you can [send us any amount you'd like via Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XSYNN4MGM826N). We sincerely appreciate your support.

Please enjoy using Unmark! We use it every day and hope you do too.

Thanks,
Plain