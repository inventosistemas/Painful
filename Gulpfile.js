var gulp = require('gulp');

//Js
var uglify = require('gulp-uglify');
var filesJs = './javascripts/editable/*.js';
var outputJs = './javascripts';

gulp.task('uglify', function(){
	gulp.src(filesJs).pipe( uglify() ).pipe( gulp.dest(outputJs) );
});

//Sass
var sass = require('gulp-sass');
var filesCss = './sass/**/*.sass';
var outputCss = './stylesheets';

gulp.task('sass', function () {
 return gulp.src(filesCss)
   .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
   .pipe(gulp.dest(outputCss));
});

gulp.task('minify', function() {
  return gulp.src(filesHtml)
    .pipe(htmlmin({collapseWhitespace: true}))
    .pipe(htmlmin({removeComments: true}))
    .pipe(gulp.dest(outputHtml));
});

//Run/Watch
gulp.task('default', function(){
	gulp.run('uglify');
	gulp.watch(filesJs, function(){
		gulp.run('uglify');
	});

	gulp.run('sass');
	gulp.watch(filesCss, function(){
		gulp.run('sass');
	});
});
