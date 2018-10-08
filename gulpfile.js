// Requis
var gulp = require('gulp');

// Include plugins
var plugins = require('gulp-load-plugins')(); // tous les plugins de package.json

var source = './assets';
var destination = './public/build';

/*
gulp.task('js', function(){
    return gulp.src(source + '/js/*.js')
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.concat('app.min.js'))
        .pipe(plugins.sourcemaps.write())
        .pipe(gulp.dest(destination + '/js/'))
});
*/

gulp.task('css', function () {
    return gulp.src(source + '/less/*.less')
        .pipe(plugins.less())
        .pipe(plugins.csscomb())
        .pipe(plugins.cssbeautify({indent: '  '}))
        .pipe(plugins.autoprefixer())
        .pipe(gulp.dest(destination + '/css/'));
});

gulp.task('minify', function () {
    return gulp.src(destination + '/css/*.css')
        .pipe(plugins.csso())
        .pipe(plugins.rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(destination + '/css/'));
});

gulp.task('build', ['css']);

gulp.task('prod', ['build',  'minify']);

gulp.task('watch', function () {
    gulp.watch('assets/less/*.less', ['build']);
});

gulp.task('default', ['build']);
