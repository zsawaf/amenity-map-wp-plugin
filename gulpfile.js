/**
 *
 * Gulpfile setup
 *
 * @since 1.0.0
 * @authors @zsawaf
 * @package Amenity Map
 */

var gulp = require('gulp');
var scss = require('gulp-scss');
var autoprefixer = require('gulp-autoprefixer');

gulp.task("styles", function () {
    gulp.src(
        "assets/css/styles.scss"
    ).pipe(scss(
    )).pipe(autoprefixer({
        browsers: ['> 1%'],
        cascade: false
    }))
    .pipe(gulp.dest("assets/css"));
});

gulp.task("admin_styles", function () {
    gulp.src(
        "assets/css/admin.scss"
    ).pipe(scss(
    )).pipe(autoprefixer({
        browsers: ['> 1%'],
        cascade: false
    }))
    .pipe(gulp.dest("assets/css"));
});

 // Watch Task
 gulp.task('default', ['styles', 'admin_styles'], function () {
    gulp.watch('./assets/css/**/*.scss', ['styles', 'admin_styles']);
 });
