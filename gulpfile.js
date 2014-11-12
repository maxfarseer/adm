'use strict';

var gulp = require('gulp'),
    rimraf = require('gulp-rimraf'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    sourcemaps = require('gulp-sourcemaps'),
    ngAnnotate = require('gulp-ng-annotate'),
    changed = require('gulp-changed'),
    jade = require('gulp-jade'),
    sass = require('gulp-sass'),
    jshint = require('gulp-jshint'),
    connect = require('gulp-connect'),
    jasmine = require('gulp-jasmine');

var outputDir = 'builds/development',
    env = process.env.NODE_ENV || 'development';

gulp.task('jade', function() {
  return gulp.src('src/templates/**/*.jade')
    .pipe(jade())
    .pipe(gulp.dest(outputDir))
    .pipe(connect.reload());
});

gulp.task('jadeAngularTmpl', function() {
  return gulp.src('src/js/views/**/*.jade')
    .pipe(jade())
    .pipe(gulp.dest(outputDir+'/js/views/'))
    .pipe(connect.reload());
});

gulp.task('sass', function() {
  var config = {};

  if (env === 'production') {
    config.outputStyle = 'compressed';
  }

  if (env === 'development') {
      config.onError = function(e) { console.log(e); };
      config.sourceMap = 'sass';
      //config.sourceComments = 'map';
  }

  return gulp.src('src/sass/main.scss')
    .pipe(sass(config))
    .pipe(gulp.dest(outputDir + '/css'))
    .pipe(connect.reload());
});

gulp.task('js', function() {
  return gulp.src([
      'src/js/**/*.js',
    ])
    .pipe(connect.reload());
});

gulp.task('compress', function() {
  gulp.src(['./src/js/**/*.js'])
    .pipe(changed(outputDir + '/js'))
    .pipe(sourcemaps.init())
    .pipe(concat('scripts.js'))
    .pipe(ngAnnotate())
    .pipe(uglify())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(outputDir + '/js'));
});

gulp.task('compress:libs', function() {
  gulp.src([
    'bower_components/jquery/dist/jquery.js',
    'bower_components/lodash/dist/lodash.js',
    'bower_components/angular/angular.js',
    'bower_components/angular-resource/angular-resource.js',
    'bower_components/angular-animate/angular-animate.js',
    'bower_components/ui-router/release/angular-ui-router.js',
    'bower_components/angular-cookies/angular-cookies.js',
    'bower_components/angular-notify/dist/angular-notify.js',
    ])
    .pipe(concat('libs.js'))
    .pipe(uglify())
    .pipe(gulp.dest(outputDir + '/js'));
});

gulp.task('lint', function() {
    var files = [
      './src/js/**/*.js',
      //'!./src/js/modules/signalr.js' - sample NOT
    ];
    files.push('./gulpfile.js');
    return gulp.src(files)
      .pipe(jshint('.jshintrc'))
      .pipe(jshint.reporter('jshint-stylish'));
});

// Test JS
gulp.task('jasmine', function () {
  return gulp.src('tests/*.js')
      .pipe(jasmine());
});

gulp.task('watch', function() {
  gulp.watch('src/templates/**/*.jade', ['jade']);
  gulp.watch('src/js/**/*.js', ['js','lint', 'compress']);
  gulp.watch('src/js/views/**/*.jade', ['jadeAngularTmpl']);
  gulp.watch('src/sass/**/*.scss', ['sass']);
  //gulp.watch(['src/js/**/*.js','tests/*.js'], ['jasmine']);
});

gulp.task('compressCssToBuild', function() {
  return gulp.src([
    'bower_components/angular-notify/dist/angular-notify.min.css',
    'src/vendor/css/bootstrap.min.css'
    ])
    .pipe(concat('vendor.min.css'))
    .pipe(gulp.dest(outputDir + '/css'));
});

gulp.task('connect', function() {
  connect.server({
    //root: outputDir,
    port: 8001,
    livereload: true
  });
});

gulp.task('deleteOldBuild', function () {
  return gulp.src('./'+outputDir, { read: false }) // much faster
    .pipe(rimraf());
});

gulp.task('default', ['jade', 'jadeAngularTmpl', 'sass', 'watch',  'connect']);
gulp.task('build', ['compress','compress:libs', 'compressCssToBuild', 'jade', 'jadeAngularTmpl', 'sass', 'watch', 'connect']);
