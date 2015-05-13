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

    watch: {
      css: {
        files: ['Assets/sass/*.scss'],
        tasks: ['sass']
      }
    }

  });


  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch')
  grunt.loadNpmTasks('grunt-contrib-sass');
  
  // Default task(s).
  grunt.registerTask('default', ['sass'] );
  grunt.registerTask('watch', ['watch'] );
  grunt.registerTask('minify-js', ['concat', 'uglify']);
  grunt.registerTask('minify-css', ['concat'])

};
