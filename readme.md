Unmark
=======

An open source, bookmarking service created by [Colin Devroe](http://colin.getbarley.com/) and [ rebuilt by Plain](http://plainmade.com/blog/7825/say-hello-to-unmark).

- [Unmark.it](https://unmark.it) - A hosted version of Unmark. [Sign up for free](https://unmark.it)
- [Unmark Wiki](https://github.com/plainmade/unmark/wiki) - Everything you wanted to know about Unmark.


Installation
==

- Download [the latest release](https://github.com/plainmade/unmark/releases) or clone the repo.
- Unzip the archive.
- Copy `/application/config/database-sample.php` to `/application/config/database.php` (leave `database-sample.php`)
- Create a database
- Fill in proper database details in `/application/config/database.php`
- Navigate to `/setup` (note that index will result in an error until `/setup` has been run)
- Follow onscreen instructions


Upgrading
==

- Download [the latest release](https://github.com/plainmade/unmark/releases).
- Replace all files (keeping your local configuration intact.)
- Navigate to `/upgrade`
- You could also run this from the command line `php index.php migrations latest`


Building Locally
==

- We use [Grunt](http://gruntjs.com/) to compile our SASS files as well as concat/uglify our JS files for production and some other small tasks.
- For more info, please [check out our Grunt guide](https://github.com/plainmade/nilai/wiki/Grunt).


Staging
==

Generally our **staging** branch will always be kept up to date with **master**, unless we are testing a feature. We use this internally to test on our staging server. You can feel free to use this at any time, just note that things might not always work 100%.


How To Contribute
==

To contribute by submitting issues; Please submit all issues on GitHub and be as verbose as possible. This is where we'll track most bugs.

We also have [an open Trello Board](https://trello.com/b/Tdx9o1X6) for new feature ideas, discussions, etc.

To submit code patches:

- Fork the repository on GitHub.
- Make updates to your code.
- Submit a Pull Request.

Enjoy. If you have questions or issues submit them [on GitHub](http://github.com/plainmade/unmark).
