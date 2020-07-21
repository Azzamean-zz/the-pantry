module.exports = function(grunt) {

    // 1. All configuration goes here
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            dist: {
                options: {
                    compass: true,
                    bundleExec: true,
                    require: [
                        'susy',
                        'sass-css-importer',
                        'sass-globbing',
                        'breakpoint'
                    ]
                },
                files: {
                    'assets/css/site.css': 'assets/css/src/site.scss'
                }
            }
        },

        autoprefixer: {
            dist: {
                files: {
                    'assets/css/site.css': 'assets/css/site.css'
                }
            }
        },

        csso: {
            dist: {
                files: {
                    'assets/css/site.min.css': ['assets/css/site.css']
                }
            }
        },

        concat: {
            main: {
                src: [
                    'assets/components/jquery-cookie/jquery.cookie.js',
                    'assets/components/jquery-timeago/jquery.timeago.js',
                    'assets/components/jquery.payment/lib/jquery.payment.js',
                    'assets/components/lightbox_me/jquery.lightbox_me.js',
                    'assets/components/parsleyjs/dist/parsley.js',
                    'assets/js/src/**/*.js'
                ],
                dest: 'assets/js/site.js',
            }
        },

        uglify: {
            dist: {
                files: {
                    'assets/js/site.min.js': ['assets/js/site.js']
                }
            }
        },

        imagemin: {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: 'assets/images/',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'assets/images/'
                }]
            }
        },

        watch: {
            css: {
                files: ['assets/css/src/**/*.scss'],
                tasks: ['sass', 'autoprefixer', 'csso'],
                options: {
                    spawn: false,
                }
            },
            scripts: {
                files: ['assets/js/src/**/*.js'],
                tasks: ['concat', 'uglify'],
                options: {
                    spawn: false,
                },
            },
            livereload: {
                // Here we watch the files the sass task will compile to
                options: { livereload: true },
                files: ['assets/css/*.css', 'js/*.js'],
            },
        }
    });

    // Load tasks
    require('load-grunt-tasks')(grunt);

    // Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['sass', 'autoprefixer', 'csso', 'concat', 'uglify', 'imagemin']);

};