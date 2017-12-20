'use strict';

const gulp = require('gulp');
const sass = require('gulp-sass');
const { gulpSassError } = require('gulp-sass-error');
const sourcemaps = require('gulp-sourcemaps');
const rev = require('gulp-rev');
const revdelOriginal = require('gulp-rev-delete-original');
const del = require('del');
const requirejsOptimize = require('gulp-requirejs-optimize');
const autoprefixer = require('gulp-autoprefixer');
const override = require('gulp-rev-css-url');
const gulpif = require('gulp-if');
const runSequence = require('run-sequence');

const staticPathSrc = 'assets';
const staticPathDist = 'web/assets';
const sassMatch = '/sass/**/*.scss';
const jsMatch = '/js/**/*.js';
const imageMatch = '/images/*';

var throwError = true;
var isSandbox = false;

gulp.task('js:clean', function () {
    return del([staticPathDist + '/js']);
});

gulp.task('js', ['js:clean'], function () {

    const modulesToOptimize = [
        staticPathSrc + '/js/**/rv-bootstrap.js',
        staticPathSrc + '/js/**/dsamen-bootstrap.js',
        staticPathSrc + '/js/**/timezone-notification.js',
        'vendor/bbc-rmp/comscore/js-modules/comscorews.js'
    ];

    const config = {
        "baseUrl": "assets/js",
        "paths": {
            "jquery-1.9": "empty:",
            "respimg": "../../node_modules/lazysizes/plugins/respimg/ls.respimg",
            "lazysizes": "../../node_modules/lazysizes/lazysizes-umd",
            "eqjs": "../../node_modules/eq.js/dist/eq.polyfilled.min",
            "comscorews" : "../../vendor/bbc-rmp/comscore/js-modules/comscorews",
            "rmpcomscore/base" : "../../vendor/bbc-rmp/comscore/js-modules/base",
            "orb/cookies": "empty:",
            'istats-1': 'empty:'
        },
        "optimize": 'uglify',
        "map": {
            "*": {
                "jquery": "jquery-1.9"
            }
        }
    };

    return gulp.src(modulesToOptimize)
        .pipe(gulpif(isSandbox, sourcemaps.init()))
        .pipe(requirejsOptimize(config))
        .pipe(gulpif(isSandbox, sourcemaps.write('.')))
        .pipe(gulp.dest(staticPathDist + '/js'));
});

// ------

gulp.task('sass:clean', function() {
    return del([staticPathDist + '/css']);
});

gulp.task('sass', ['sass:clean'], function() {
    return gulp.src(staticPathSrc + sassMatch)
        .pipe(gulpif(isSandbox, sourcemaps.init()))
        .pipe(sass({
            outputStyle: 'compressed',
            precision: 8,
            includePaths: ['src', 'node_modules']
        }).on('error', gulpSassError(throwError)))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'], cascade: false, remove: false
        }))
        .pipe(gulpif(isSandbox, sourcemaps.write('.')))
        .pipe(gulp.dest(staticPathDist + '/css/'));
});

// ------

gulp.task('images:clean', function() {
    return del([staticPathDist + '/images']);
});

gulp.task('images', ['images:clean'], function() {
    return gulp.src(staticPathSrc + '/images/**/*')
        .pipe(gulp.dest(staticPathDist + '/images/'));
});

// ------

gulp.task('rev', ['sass', 'images', 'js'], function() {
    return gulp.src([staticPathDist + '/**/*', '!' + staticPathDist + '/**/rev-manifest.json'])
        .pipe(rev())
        .pipe(override())
        .pipe(gulp.dest(staticPathDist))
        .pipe(revdelOriginal()) // delete no-revised file
        .pipe(rev.manifest('rev-manifest.json'))
        .pipe(gulp.dest(staticPathDist));
});

/*
 * Entry tasks
 */
gulp.task('watch',function() {
    // When watching we don't want to throw an error, because then we have to
    // go and restart the watch task if we ever write invalid sass, which would
    // be really annoying.
    throwError = false;

    gulp.watch(
        [staticPathSrc + sassMatch, 'src/**/*.scss'],
        ['sass']
    );

    gulp.watch([staticPathSrc + imageMatch], ['images']);
    gulp.watch([staticPathSrc + jsMatch], ['js']);
});

gulp.task('default', function(cb){
    isSandbox = true;
    runSequence(['sass', 'images', 'js']);
});
gulp.task('distribution', ['rev']);
