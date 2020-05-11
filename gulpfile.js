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
var ngAnnotate  = require('gulp-ng-annotate');
var purify      = require('gulp-purifycss');
var purgecss    = require('gulp-purgecss');


// Path to files
var config = {
    sassPath:   './app/Resources/sass/*.scss',
    scriptPath: './app/Resources/js/app.js',
    imagePath:  './app/Resources/img/**',
};

var adminConfig = {
    sassPath:   './app/Resources/sass/admin/*.scss',
    scriptPath: './app/Resources/js/admin/app.js',
    imagePath:  './app/Resources/img/admin/src/**',
};


/**
 * Production
 */

// Compiles SCSS files to CSS
gulp.task('sass', function () {
    return gulp.src(config.sassPath)
        .pipe(sass())
        .pipe(gulp.dest('./web/assets/css'))
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


// Remove unused css
gulp.task('purgecss', () => {
    return gulp.src('./web/assets/css/style.css')
        .pipe(purgecss({
            content: ['./web/assets/js/app.js', './app/Resources/views/**/*.html.twig']
        }))
        .pipe(cleanCSS())
        .pipe(gulp.dest('./web/assets/css'))
})


// Compile javascript files
gulp.task('scripts', function() {
    return gulp.src([
      './node_modules/jquery/dist/jquery.min.js',
      './node_modules/bootstrap/dist/js/bootstrap.min.js',
      './node_modules/waypoints/lib/jquery.waypoints.min.js',
      './node_modules/angular/angular.min.js',
      './node_modules/angular-messages/angular-messages.min.js',
      config.scriptPath,
    ])
    .pipe(concat('app.js'))
    .pipe(ngAnnotate())
    .pipe(uglify())
    .pipe(gulp.dest('./web/assets/js/'))
});


// Running image optimizing task
gulp.task('imagemin', function(){
    gulp.src(config.imagePath)
    .pipe(imagemin())
    .pipe(gulp.dest('./web/assets/img'))
});


// Import Fonts
gulp.task('fonts', function() {
    return gulp.src([
            './node_modules/@fortawesome/fontawesome-free/webfonts/*',
            './node_modules/summernote/dist/font/*'
        ])
        .pipe(gulp.dest('./web/assets/fonts/'));
});


// Starts browser-sync task
gulp.task('sync', ['sass'], function() {
    browserSync.init({
        proxy: 'dev.mtropea/app_dev.php',
        //notify: false,
        //open: false,
    });
    gulp.watch(config.sassPath, ['dev-sass']).on('change', browserSync.reload);
    gulp.watch(adminConfig.sassPath, ['admin-sass']).on('change', browserSync.reload);
    gulp.watch("./app/Resources/views/**/*.html.twig").on('change', browserSync.reload);
});


/**
 * Admin
 */

// Compiles admin SCSS files to CSS
gulp.task('admin-sass', function () {
    return gulp.src(adminConfig.sassPath)
        //.pipe(sourcemaps.init())
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sass())
        //.pipe(sourcemaps.write())
        .pipe(cleanCSS())
        .pipe(gulp.dest('./web/assets/admin/css/'))
        .pipe(browserSync.stream());
});


// Compiles admin SCSS files to CSS
gulp.task('admin-scss-vendors', function () {
    return gulp.src([
            './node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css',
            './node_modules/jquery-minicolors/jquery.minicolors.css',
            './node_modules/summernote/dist/summernote.css'
        ])
        .pipe(cleanCSS())
        .pipe(gulp.dest('./web/assets/admin/css/vendors'))
        .pipe(browserSync.stream());
});


// Compile javascript files
gulp.task('admin-scripts', function() {
    return gulp.src([
      './node_modules/jquery/dist/jquery.min.js',
      './node_modules/popper.js/dist/umd/popper.js',
      './node_modules/bootstrap/dist/js/bootstrap.min.js',
      './node_modules/metismenu/dist/metisMenu.min.js',
      './node_modules/jquery-slimscroll/jquery.slimscroll.min.js',
      './node_modules/popper.js/dist/umd/popper.min.js',
      adminConfig.scriptPath
    ])
    .pipe(concat('app.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./web/assets/admin/js/'))
});


// Compile vendor javascript files
gulp.task('admin-vendor-scripts', function() {
    return gulp.src([
      './node_modules/jquery-minicolors/jquery.minicolors.min.js',
      './node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
      './node_modules/summernote/dist/summernote.js',
      './node_modules/jquery-knob/dist/jquery.knob.min.js',
      './node_modules/sortablejs/Sortable.min.js',
    ])
    .pipe(gulp.dest('./web/assets/admin/js/vendors'))
});


// Production task
gulp.task('production', [
            'sass',
            'purgecss',
            'scripts',
            'imagemin', 
            'fonts',
            'admin-sass',
            'admin-scss-vendors',
            'admin-scripts',
            'admin-vendor-scripts',
        ], function() {});

