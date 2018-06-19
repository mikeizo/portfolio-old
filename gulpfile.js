// Defining requirements
var gulp        = require('gulp');
var plumber     = require('gulp-plumber');
var sass        = require('gulp-sass');
var concat      = require('gulp-concat');
var uglify      = require('gulp-uglify');
var imagemin    = require('gulp-imagemin');
var sourcemaps  = require('gulp-sourcemaps');
var cleanCSS    = require('gulp-clean-css');
var browserSync = require('browser-sync').create();


// Path to files
var config = {
    sassPath:   './web/assets/sass/*.scss',
    scriptPath: './web/assets/js/app.js',
    imagePath:  './web/assets/img/src/**',
};


// Compiles SCSS files to CSS
gulp.task('sass', function () {
    return gulp.src(config.sassPath)
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sass())
        .pipe(cleanCSS())
        .pipe(gulp.dest('./web/assets/css'))
        .pipe(browserSync.stream());
});


// Compiles development SCSS files to CSS
gulp.task('dev-sass', function () {
    return gulp.src(config.sassPath)
        .pipe(sourcemaps.init())
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sass())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./web/assets/css'))
        .pipe(browserSync.stream());
});


// Compile vendor javascript files
gulp.task('vendor-scripts', function() {
    return gulp.src([
      './node_modules/jquery/dist/jquery.min.js',
      './node_modules/bootstrap/dist/js/bootstrap.min.js',
      './node_modules/waypoints/lib/jquery.waypoints.min.js',
      './node_modules/angular/angular.min.js',
      './node_modules/angular-messages/angular-messages.min.js',
    ])
    .pipe(concat('vendors.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./web/assets/js/'))
    .pipe(browserSync.stream());
});


// Running image optimizing task
gulp.task('imagemin', function(){
    gulp.src(config.imagePath)
    .pipe(imagemin())
    .pipe(gulp.dest('./web/assets/img'))
});


// Starts browser-sync task
gulp.task('sync', ['sass'], function() {
    browserSync.init({
        proxy: 'dev.mtropea/app_dev.php',
        //notify: false,
        //open: false,
    });
    gulp.watch(config.sassPath, ['dev-sass']).on('change', browserSync.reload);
    gulp.watch("./app/Resources/views/**/*.html.twig").on('change', browserSync.reload);
});

// Production task
gulp.task('production', ['sass', 'vendor-scripts', 'imagemin'], function() {});
