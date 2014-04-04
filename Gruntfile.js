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

    var asset_version = new Date().getTime();

    var js_file_config = {
                    'assets/js/production/unmark.plugins.js': [
                        'assets/js/plugins/hogan.js',
                        'assets/js/plugins/pjax.js',
                        'assets/js/plugins/fitvids.js'
                    ],
                    'assets/js/production/unmark.loggedin.js': [
                        'assets/js/templates/unmark-templates.js',
                        'assets/js/unmark.js',
                        'assets/js/unmark.actions.js',
                        'assets/js/unmark.marks.js',
                        'assets/js/unmark.client.js',
                        'assets/js/unmark.init.js',
                        'assets/js/unmark.touch.js'
                    ],
                    'assets/js/production/unmark.loggedout.js': [
                        'assets/js/unmark.js',
                        'assets/js/unmark.reset.js',
                        'assets/js/unmark.login.js',
                        'assets/js/unmark.register.js'
                    ],
                    'assets/js/production/unmark.bookmarklet.js': [
                        'assets/js/unmark.js',
                        'assets/js/unmark.actions.js',
                        'assets/js/unmark.marks.js',
                        'assets/js/unmark.add.js',
                        'assets/js/unmark.init.js'
                    ]
                }

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
        "string-replace": {
            src: {
               files: {
                   "application/helpers/view_helper.php" : "application/helpers/view_helper.php"
               },
               options: {
                   replacements: [{
                        pattern: /define\("ASSET_VERSION", ('(?:''|[^'])*'|[^',]+)\);/,
                        replacement: 'define("ASSET_VERSION", "'+asset_version+'");'
                   }]
               }
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

    // Register Tasks
    grunt.registerTask('default', [ 'sass:prod', 'uglify:prod', 'uglify:custom', 'string-replace' ]); // Default Production Build

    grunt.registerTask('dev', [ 'sass:prod', 'concat:dev', 'concat:custom', 'string-replace' ]);

};
