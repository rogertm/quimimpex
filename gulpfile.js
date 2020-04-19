/**
 * Gulp Tasks
 *
 * @since Quimimpex 1.0
 */

/**
 * Load Plugins
 */
const { gulp, dest, src, series, watch, task } = require('gulp');
const gulpCopy 			= require('gulp-copy');
const del				= require('del');

/**
 * Asset
 */
let dist 				= 'assets/dist/';
let nodeSrc				= 'node_modules/';

/**
 * Node Modules
 */
let hover				= nodeSrc +'hover.css/scss/**/*';
let vendorsSrc			= [ hover ];
let vendorsDist			= dist +'vendor/';

/**
 * Copy required dependencies from node_modules/ to assets/dist/vendors/
 *
 * @since Cubalite 1.0
 */
function vendors(){
	return src(vendorsSrc)
		.pipe(gulpCopy(vendorsDist, { prefix: 1 }));
}

/**
 * Delete assets/dist/css/ and assets/dist/js/ directories
 *
 * @since Cubalite 1.0
 */
function clean(){
	return del([vendorsDist]);
}

exports.default = series( clean, vendors );
