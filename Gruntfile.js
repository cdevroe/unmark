module.exports = function(grunt) {

    'use strict';

    function loadConfig(path) {

        var glob = require('glob'),
            object = {},
            key;

        glob.sync('*', {cwd: path}).forEach(function(option) {
            key = option.replace(/\.js$/,'');
            object[key] = require(path + option);
        });

        return object;
    }

    var js_file_config = {
		'assets/js/production/unmark.plugins.js': [
            'assets/js/plugins/hogan.js',
            'assets/js/plugins/selectize.min.js',
			'assets/js/plugins/jquery.pjax.js',
			'assets/js/plugins/fitvids.js'
		],
		'assets/js/production/unmark.loggedin.js': [
			'assets/js/templates/unmark-templates.js',
			'assets/js/unmark.js',
			'assets/js/unmark.actions.js',
			'assets/js/unmark.marks.js',
			'assets/js/unmark.client.js',
			'assets/js/unmark.init.js',
            'assets/js/unmark.touch.js',
            'assets/js/unmark.pwa.js'
		],
		'assets/js/production/unmark.loggedout.js': [
			'assets/js/unmark.js',
			'assets/js/unmark.reset.js',
            'assets/js/unmark.login.js',
            'assets/js/unmark.pwa.js',
			'assets/js/unmark.register.js'
		],
		'assets/js/production/unmark.bookmarklet.js': [
			'assets/js/unmark.js',
			'assets/js/unmark.actions.js',
			'assets/js/unmark.marks.js',
            'assets/js/unmark.add.js',
			'assets/js/unmark.init.js'
		]
	};

    // Base Config
    var config = {
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            prod: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'assets/css/unmark.css': 'assets/css/unmark.scss',
                    'assets/css/unmark_welcome.css': 'assets/css/unmark_welcome.scss'
                }
            }
        },
        uglify: {
            prod: {
                options : {
                    beautify : {
                        ascii_only : true
                    },
                    banner: '/*! <%= pkg.name %> - <%=pkg.url %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> - <%= pkg.author %> */ \n'
                },
                files: js_file_config
            }
        },
        concat: {
            dev: {
                options: {
                    stripBanners: false,
                    banner: '/*! DEVELOPMENT VERSION */ \n'
                },
                files: js_file_config
            }
        },
        copy: {
            custom: {
                files: [
                    {expand: true, flatten: false, cwd: '../unmark-internal/custom/', src: ['**'], dest: '../unmark/custom/'}
                ]
            },
            release: {
                files: [
                    {expand: true, flatten: false, src: ['application/**', '!application/config/database.php'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['assets/css/*.css'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['assets/libraries/jquery/*.js'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['assets/images/**'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['assets/js/plugins/*.js'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['assets/js/production/*.js'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['assets/js/templates/*.js'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['assets/touch_icons/*.png'], dest: 'release/unmark/'},
                    {expand: false, flatten: true, src: ['assets/.htaccess'], dest: 'release/unmark/assets/.htaccess'},
                    {expand: true, flatten: false, src: ['bookmarklets/*.js'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['custom_example/**'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['system/**'], dest: 'release/unmark/'},
                    {expand: true, flatten: false, src: ['docker-configs/*.ini'], dest: 'release/unmark/'},
                    {expand: true, flatten: true, src: ['*', '!Gruntfile.js', '!.DS_Store', '!.gitignore', '!package.json', '!package-lock.json'], dest: 'release/unmark/', filter: 'isFile', dot: true}
                ]
            }
        },
        clean: {
            custom: ['custom/*'],
            releasePrepare: ['release/*'],
            releaseFinal: ['release/*', '!release/unmark.zip']
        },
        compress: {
            dist: {
                options: {
                    archive: 'release/unmark.zip'
                },
                files: [
                    {src: ['release/unmark/**'], dot: true }
                ]
            }
        },
        watch: {
            scripts: {
                files: ['assets/js/*.js'],
                tasks: ['concat:dev', 'concat:custom']
            },
            css: {
                files: ['assets/css/*.scss'],
                tasks: ['sass:prod']
            }
        }
    };


    // Look for any option files inside of `/custom/grunt_tasks` folder.
    // The file name would be `sass.js` or `watch.js` etc
    // If found, extend and overwrite with custom one
    grunt.util._.extend(config, loadConfig('./custom/grunt_tasks/'));

    // Config the Options
    grunt.initConfig(config);

    // Load the Tasks
    require('load-grunt-tasks')(grunt);

    // --------------- TASKS

    // Default task:
    // Compiles CSS, compresses JavaScript.
    grunt.registerTask('default', [ 'sass:prod', 'uglify:prod' ]);

    // Release task:
    // Cleans release directory, compiles CSS and compresses JavaScript. Copies all files to /release/unmark, compresses a zip, deletes /release/unmark
    grunt.registerTask('release', [ 'clean:releasePrepare', 'sass:prod', 'uglify:prod', 'copy:release', 'compress:dist', 'clean:releaseFinal' ]);

    // Dev build task:
    // Does not compress files, easier to debug
    grunt.registerTask('dev', [ 'sass:prod', 'concat:dev', 'concat:custom' ]);
    
    // Production build task:
    // Deletes contents of custom folder, copies new custom files, compresses everything (used primarily for unmark.it)
    grunt.registerTask('production', [ 'makeCustom', 'sass:prod', 'uglify:prod', 'uglify:custom' ]);

    // Utility tasks that deletes/copies /custom/
    grunt.registerTask( 'makeCustom', [ 'clean:custom', 'copy:custom' ] ); // Copies ../unmark-internal/custom to ../unmark/custom

};
