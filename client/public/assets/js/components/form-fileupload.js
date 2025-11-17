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

      var options = { url: url };
      if (previewsContainer) options.previewsContainer = previewsContainer;

      var templateSelector = $el.data('uploadPreviewTemplate');
      if (templateSelector) options.previewTemplate = $(templateSelector).html();

      var maxFiles = $el.data('maxFiles');
      if (maxFiles !== undefined) options.maxFiles = maxFiles;

      var acceptedFiles = $el.data('acceptedFiles');
      if (acceptedFiles) options.acceptedFiles = acceptedFiles;

      options.addRemoveLinks = true;

      $el.dropzone(options);

      var dz = $el[0].dropzone;
      if (requiredInputSelector) {
        var $required = $(requiredInputSelector);
        if ($required.length) {
          dz.on('addedfile', function(){
            $required.val('1');
          });
          dz.on('removedfile', function(){
            if (dz.files.length === 0) {
              $required.val('');
            }
          });
        }
      }

      dz.on('maxfilesexceeded', function(file){
        dz.removeAllFiles(true);
        dz.addFile(file);
      });
    });
  };

  $.FileUpload = new FileUpload();
  $.FileUpload.Constructor = FileUpload;
})(window.jQuery);

(function(){
  "use strict";
  window.jQuery.FileUpload.init();
})();