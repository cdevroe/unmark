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
                    style: 'compressed',
                    banner: '/*! <%= pkg.name %> - <%=pkg.url %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> - <%= pkg.author %> */ \n'
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
                    {expand: false, flatten: true, src: ['manifest.json'], dest: 'release/unmark/manifest.json'},
                    {expand: false, flatten: true, src: ['service-worker.js'], dest: 'release/unmark/service-worker.js'},
                    {expand: false, flatten: true, src: ['.htaccess'], dest: 'release/unmark/.htaccess'},
                    {expand: true, flatten: true, src: ['*', '!Gruntfile.js', '!.DS_Store', '!.gitignore', '!package.json', '!package-lock.json'], dest: 'release/unmark/', filter: 'isFile'}
                ]
            }
        },
        compress: {
            dist: {
                options: {
                    archive: 'release/unmark.zip'
                },
                files: [
                    {src: ['release/unmark/**'] }
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

    grunt.registerTask('default', [ 'sass:prod', 'uglify:prod' ]); // Default Build for OS Project

    grunt.registerTask('release', [ 'sass:prod', 'uglify:prod', 'copy:release' ]); // Build for OS release

    grunt.registerTask('dev', [ 'sass:prod', 'concat:dev', 'concat:custom' ]); // Dev build
    grunt.registerTask('production', [ 'sass:prod', 'uglify:prod', 'uglify:custom' ]); // Default Production Build

};
