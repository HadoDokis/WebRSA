/*global module, require*/
module.exports = function( grunt ) {
	'use strict';

	var pkg = grunt.file.readJSON( 'package.json' ),
		initConfig = { pkg: pkg },
		webrsa_app = '/home/atma/workspace/webrsa/app/',
		src = {
			validate: webrsa_app + 'webroot/js/webrsa.validate*.js',
			specs: webrsa_app + 'Vendor/javascript/Test/*.js',
			all: [ webrsa_app + 'webroot/js/webrsa.validate*.js', webrsa_app + 'Vendor/javascript/Test/*.js' ],
			nospec: [ webrsa_app + 'webroot/js/webrsa.validate*.js' ]
		};

	// -------------------------------------------------------------------------

	initConfig.clean = {
		quality: [
			'out/complexity',
			'out/coverage',
			'out/jscpd',
			'out/jsdoc',
			'out/jshint',
			'out/jslint',
			'out/junit',
			'out/plato',
			'out/tmp'
		]
	};

	initConfig.mkdir = {
		quality: {
			options: {
				create: [ 'out/tmp' ]
			}
		}
	};

	initConfig.jsvalidate = {
		options: {
			verbose: true
		},
		all: {
			files: {
				src: src.all
			}
		}
	};

	initConfig.jasmine = {
		all: {
			src: src.all,
			options: {
				specs: src.specs
			}
		},
		quality: {
			src: src.all,
			options: {
				specs: src.specs,
				junit: {
					path: 'out/junit/'
				}
			}
		}
	};

	initConfig.jscpd = {
		all: {
			path: src.validate
		},
		quality: {
			path: src.validate,
			output: 'out/jscpd/jscpd.xml'
		}
	};

	initConfig.jshint = {
		all: {
			src: src.all
		},
		quality: {
			src: src.all,
			options: {
				force: true,
				reporter: 'checkstyle',
				reporterOutput: 'out/jshint/jshint-checkstyle.xml'
			}
		}
	};

	initConfig.jslint = {
		all: {
			src: src.all,
			directives: {
				white: true,
				browser: true,
				plusplus: true,
				nomen: true,
				regexp: true,
				continue: true,
				sloppy: false
			},
			options: {
				edition: 'latest',
				errorsOnly: true,
				failOnError: true
			}
		},
		quality: {
			src: src.all,
			directives: {
				white: true,
				browser: true,
				plusplus: true,
				nomen: true,
				regexp: true,
				continue: true,
				sloppy: false
			},
			options: {
				edition: 'latest',
				errorsOnly: true,
				failOnError: false,
				jslintXml: 'out/jslint/jslint.xml',
				checkstyle: 'out/jslint/jslint-checkstyle.xml'
			}
		}
	};

	initConfig.complexity = {
		options: {
			breakOnErrors: false,
			errorsOnly: false,
			hideComplexFunctions: false,
			broadcast: false,
			cyclomatic: 4,
			halstead: 20,
			maintainability: 100
		},
		all: {
			src: [ src.all ]
		},
		quality: {
			src: [ src.all ],
			options: {
				jsLintXML: 'out/complexity/jslint.xml',
				checkstyleXML: 'out/complexity/checkstyle.xml'
			}
		}
	};

	initConfig.jsdoc = {
		quality: {
			src: src.nospec,
			options: {
				destination: 'out/jsdoc/'
			}
		}
	};

	initConfig.plato = {
		quality: {
			files: {
				'out/plato/': src.nospec
			}
		}
	};

	// -------------------------------------------------------------------------

	initConfig.watch = {
		dev: {
			files: src.all,
			tasks: [ 'jasmine:all' ]
		}
	};

	// -------------------------------------------------------------------------

	grunt.initConfig( initConfig );
	grunt.loadNpmTasks( 'grunt-complexity' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-jasmine' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-jscpd' );
	grunt.loadNpmTasks( 'grunt-jsdoc' );
	grunt.loadNpmTasks( 'grunt-jslint' );
	grunt.loadNpmTasks( 'grunt-jsvalidate' );
	grunt.loadNpmTasks( 'grunt-mkdir' );
	grunt.loadNpmTasks( 'grunt-plato' );

	// -------------------------------------------------------------------------

	grunt.registerTask( 'default', [ 'jsvalidate:all', 'jscpd:all', 'jshint:all', 'jslint:all', 'complexity:all' ] );
	grunt.registerTask( 'quality', [ 'jsvalidate:all', 'clean:quality', 'mkdir:quality', /*'jasmine:quality',*/ 'jscpd:quality', 'jshint:quality', 'jslint:quality', 'complexity:quality', 'jsdoc:quality', 'plato:quality' ] );
};
