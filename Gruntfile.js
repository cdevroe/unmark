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

    // Base Config
    var config = {
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            all: {
                options: {
                    style: 'compressed',
                    banner: '/*! <%= pkg.name %> - <%=pkg.url %> - v<%= pkg.version %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> - <%= pkg.author %> */'
                },
                files: {
                    'assets/css/unmark.css': 'assets/css/unmark.scss'
                }
            }
        },
        uglify: {
            all: {
                options : {
                    beautify : {
                        ascii_only : true
                    },
                    banner: '/*! <%= pkg.name %> - <%=pkg.url %> - v<%= pkg.version %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> - <%= pkg.author %> */'
                },
                files: {
                    'assets/js/production/unmark.plugins.js': [
                        'assets/js/plugins/hogan.js',
                        'assets/js/plugins/pjax.js',
                        'assets/js/plugins/Chart.min.js',
                        'assets/js/plugins/unmark-graph.js'
                    ], 
                    'assets/js/production/unmark.loggedin.js': [
                        'assets/js/templates/unmark-templates.js',
                        'assets/js/unmark.js',
                        'assets/js/unmark.actions.js',
                        'assets/js/unmark.marks.js',
                        'assets/js/unmark.client.js',
                        'assets/js/unmark.init.js'
                    ],
                    'assets/js/production/unmark.loggedout.js': [
                        'assets/js/unmark.js',
                        'assets/js/unmark.reset.js',
                        'assets/js/unmark.login.js'
                    ],
                    'assets/js/production/unmark.bookmarklet.js': [
                        'assets/js/unmark.js',
                        'assets/js/unmark.actions.js',
                        'assets/js/unmark.marks.js',
                        'assets/js/unmark.add.js',
                        'assets/js/unmark.init.js'
                    ]
                }
            }
        },
        watch: {
            scripts: {
                files: ['assets/css/*.scss', 'assets/js/*.js'],
                tasks: ['sass', 'uglify']
            },
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
    grunt.registerTask('default', [ 'sass', 'uglify' ]);

};