// Gulp.js configuration
'use strict';

// Gulp and plugins
import gulp from 'gulp';
import newer from 'gulp-newer';
import imagemin from 'gulp-imagemin';
import minify from 'gulp-minify-css';
import merge from 'merge-stream';
import cleanCss from 'gulp-clean-css';
import terser from 'gulp-terser';
import uglify from 'gulp-uglify';
import svgmin from 'gulp-svgmin';
import { deleteAsync } from 'del';
import browserSync from 'browser-sync';
import gulpSass from 'gulp-sass';
import dartSass from 'sass';

const browserSyncEnabled = process.env.NODE_ENV !== 'production';

const paths = {
    build: './assets',
    css: {
        src: './resources/css/**/*.css',
        dest: './assets/css/',
    },
    sass: {
        src: './resources/scss/**/*.scss',
        dest: './assets/css/',
    },
    fonts: {
        src: './resources/fonts/*.{ttf,woff,woff2,eof}',
        dest: './assets/fonts/',
    },
    images: {
        src: './resources/img/**/*.{jpg,jpeg,gif,png,svg}',
        dest: './assets/img',
        icons: {
            src: './resources/icons/*.svg',
            dest: './assets/icons/',
        }
    },
    js: {
        src: './resources/js/**/*.js',
        dest: './assets/js',
    }
};

// Css minify
gulp.task('css', () => {
    return gulp.src(paths.css.src)
        .pipe(minify())
        .pipe(cleanCss())
        .pipe(gulp.dest(paths.css.dest));
});

// Sass processing
gulp.task('sass', () => {
    let sass = gulpSass(dartSass);

    return gulp.src(paths.sass.src)
        .pipe(sass.sync())
        .pipe(minify())
        .pipe(cleanCss())
        .pipe(gulp.dest(paths.sass.dest));
});

// Copy fonts
gulp.task('fonts', () => {
    return gulp.src(`${paths.fonts.src}`)
        .pipe(gulp.dest(paths.fonts.dest));
});

// Images processing
gulp.task('images', () => {
    let assetsImages = gulp.src(paths.images.src)
        .pipe(newer(paths.images.dest))
        .pipe(imagemin())
        .pipe(gulp.dest(paths.images.dest));

    let icons = gulp.src(paths.images.icons.src)
        .pipe(svgmin({
            multipass: true,
            plugins: [
                {
                    name: 'cleanupIDs',
                    params: {
                        minify: true
                    }
                }
            ]
        }))
        .pipe(gulp.dest(paths.images.icons.dest));

    return merge(assetsImages, icons);
});

// JS minify
gulp.task('js', () => {
    return gulp.src(paths.js.src)
        .pipe(terser())
        .pipe(uglify())
        .pipe(gulp.dest(paths.js.dest));
});

// Watch tasks
gulp.task('watch', () => {
    const browserSyncServer = browserSync.create();

    if (browserSyncEnabled) {
        browserSyncServer.init({
            proxy: 'localhost:8080',
            port: 8080
        });
    }

    gulp.watch([paths.css.src], gulp.series('css')).on('change', browserSyncServer.reload);
    gulp.watch([paths.sass.src], gulp.series('sass')).on('change', browserSyncServer.reload);
    gulp.watch([paths.fonts.src], gulp.series('fonts')).on('change', browserSyncServer.reload);
    gulp.watch([paths.images.src, paths.images.icons.src], gulp.series('images')).on('change', browserSyncServer.reload);
    gulp.watch([paths.js.src], gulp.series('js')).on('change', browserSyncServer.reload);
});

// Clean build folder
gulp.task('clean', async () => {
    return deleteAsync(paths.build, { force: true });
});

gulp.task('build', gulp.series('clean', 'css', 'sass', 'fonts', 'images', 'js'));
gulp.task('default', gulp.series('build'));
