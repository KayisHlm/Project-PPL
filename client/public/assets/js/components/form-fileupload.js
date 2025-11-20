;(function($){
  "use strict";

  function FileUpload(){
    this.$body = $("body");
  }

  FileUpload.prototype.init = function(){
    Dropzone.autoDiscover = false;

    $('[data-plugin="dropzone"]').each(function(){
      var $el = $(this);
      var url = $el.attr('action') || $el.data('url');
      var previewsContainer = $el.data('previewsContainer');
      var requiredInputSelector = $el.data('requiredInput');

      var options = { 
        url: url,
        autoProcessQueue: true,
        uploadMultiple: false,
        paramName: 'file',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      };
      if (previewsContainer) options.previewsContainer = previewsContainer;

      var templateSelector = $el.data('uploadPreviewTemplate');
      if (templateSelector) options.previewTemplate = $(templateSelector).html();

      var maxFiles = $el.data('maxFiles');
      if (maxFiles !== undefined) options.maxFiles = maxFiles;

      var acceptedFiles = $el.data('acceptedFiles');
      if (acceptedFiles) options.acceptedFiles = acceptedFiles;

      options.addRemoveLinks = true;

      try {
        $el.dropzone(options);

        var dz = $el[0].dropzone;
        if (requiredInputSelector) {
          var $required = $(requiredInputSelector);
          if ($required.length) {
            // When file upload is successful, store the server response path
            dz.on('success', function(file, response){
              try {
                // Assuming server returns { path: '/uploads/filename.jpg' }
                if (response && response.path) {
                  $required.val(response.path);
                  // Store the path in the file object for later reference
                  file.serverPath = response.path;
                } else if (typeof response === 'string') {
                  // If server returns just a string path
                  $required.val(response);
                  file.serverPath = response;
                }
              } catch (e) {
                console.error('Error handling upload success:', e);
              }
            });
            
            dz.on('removedfile', function(file){
              try {
                if (dz.files.length === 0) {
                  $required.val('');
                } else if (dz.files.length > 0 && dz.files[0].serverPath) {
                  // If there are still files, update with the first file's server path
                  $required.val(dz.files[0].serverPath);
                }
              } catch (e) {
                console.error('Error handling file removal:', e);
              }
            });
            
            // Handle upload error
            dz.on('error', function(file, errorMessage){
              console.error('Upload error:', errorMessage);
              // Don't clear the required field on error to allow retry
            });
          }
        }

        dz.on('maxfilesexceeded', function(file){
          dz.removeAllFiles(true);
          dz.addFile(file);
        });
      } catch (e) {
        console.error('Error initializing Dropzone:', e);
      }
    });
  };

  $.FileUpload = new FileUpload();
  $.FileUpload.Constructor = FileUpload;
})(window.jQuery);

(function(){
  "use strict";
  window.jQuery.FileUpload.init();
})();