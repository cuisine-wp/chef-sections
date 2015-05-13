module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    
    uglify: {
      build: {
        src: 'Assets/css/admin.css',
        dest: 'Assets/css/admin.min.css'
      }
    },

    /* Plain ol' Sass function, so we can drop compass as a dependency: */
    sass: {                              
      dist: {                            
        options: {                       
          style: 'expanded',
        },
        files: {                         
          'Assets/css/admin.css': 'Assets/sass/main.scss',
        }
      }
    },

    cssmin: {
      combine: {
        files: {
          'Assets/css/admin.min.css': 'Assets/css/admin.css'
        }
      }
    },
    watch: {
      css: {
        files: ['Assets/sass/*.scss', 'Assets/sass/**/*.scss'],
        tasks: ['sass']
      }
    }

  });


  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  
  // Default task(s).
  grunt.registerTask('default', ['watch'] );

  grunt.registerTask('minify-js', ['concat', 'uglify']);
  grunt.registerTask('minify-css', ['concat'])

};
