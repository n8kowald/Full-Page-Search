'use strict';
module.exports = function(grunt) {

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		// js minification
		uglify: {
			dist: {
				files: {
					// admin scripts
					'lib/js/min/yikes-full-page-search.min.js': [ // all other admin scripts
						'lib/js/yikes-full-page-search.js',
					],
				}
			}
		},

		// Autoprefixer for our CSS files
		postcss: {
			options: {
				map: true,
				processors: [
					require('autoprefixer-core') ({
						browsers: ['last 2 versions']
					})
				]
			},
			dist: {
				src: ['lib/css/*.css']
			}
		},
		auto_install: {
			local: {}
		},

		// css minify all contents of our directory and add .min.css extension
		cssmin: {
			target: {
				files: [
					// admin css files
					{
						'lib/css/min/yikes-full-page-search.min.css':
						[
							'lib/css/yikes-full-page-search.css',
						],
					}
				]
			}
		},

		// Generate a nice banner for our css/js files
		usebanner: {
			taskName: {
				options: {
					position: 'top',
					replace: true,
					banner: '/*\n'+
						' * @Plugin <%= pkg.title %>\n' +
						' * @Author <%= pkg.author %>\n'+
						' * @Site <%= pkg.site %>\n'+
						' * @Version <%= pkg.version %>\n' +
						' * @Build <%= grunt.template.today("mm-dd-yyyy") %>\n'+
						' */',
					linebreak: true
				},
				files: {
					src: [
						'lib/css/min/*.min.css',
						'lib/js/min/*.min.js',
					]
				}
			}
		},

		// watch our project for changes
		watch: {
			admin_css: { // admin css
				files: 'lib/css/*.css',
				tasks: ['cssmin', 'usebanner'],
				options: {
					spawn: false,
					event: ['all']
				},
			},
			admin_js: { // admin js
				files: 'lib/js/*.js',
				tasks: ['uglify', 'usebanner'],
				options: {
					spawn: false,
					event: ['all']
				},
			},
		},

	});

	// load tasks
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-banner');
	grunt.loadNpmTasks('grunt-postcss'); // CSS autoprefixer plugin (cross-browser auto pre-fixes)
	grunt.loadNpmTasks('grunt-auto-install'); // autoload all of ourd ependencies (ideally, you install this one package, and run grunt auto_install to install our dependencies automagically)

	// register task
	grunt.registerTask('default', [
		'uglify',
		'postcss',
		'cssmin',
		'usebanner',
		'watch',
	]);

};
