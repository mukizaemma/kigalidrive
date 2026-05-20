window._summernoteToolbar = [
  ['style', ['style']],
  ['font', ['bold', 'underline', 'clear']],
  ['color', ['color']],
  ['para', ['ul', 'ol', 'paragraph']],
  ['table', ['table']],
  ['insert', ['link', 'picture', 'video']],
  ['view', ['fullscreen', 'codeview', 'help']]
];

$(document).ready(function() {
  $('#Blogs').each(function () {
    var $t = $(this);
    if ($t.closest('.modal').length || $t.next('.note-editor').length) return;
    $t.summernote({
      placeholder: 'Description',
      tabsize: 2,
      height: 200,
      toolbar: _summernoteToolbar
    });
  });
});
$(document).ready(function() {
  $('#hotelDescription').each(function () {
    var $t = $(this);
    if ($t.closest('.modal').length || $t.next('.note-editor').length) return;
    $t.summernote({
      placeholder: 'Hotel Description',
      tabsize: 2,
      height: 200,
      toolbar: _summernoteToolbar
    });
  });
});
$(document).ready(function() {
  $('#leftBags').summernote({
      placeholder: 'Left Bags Description',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#ticketing').summernote({
      placeholder: 'Ticketing Description',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#roomDescription').each(function () {
    var $t = $(this);
    if ($t.closest('.modal').length || $t.next('.note-editor').length) return;
    $t.summernote({
      placeholder: 'Room Description',
      tabsize: 2,
      height: 200,
      toolbar: _summernoteToolbar
    });
  });
});
$(document).ready(function() {
  $('#propertyDescription').each(function () {
    var $t = $(this);
    if ($t.closest('.modal').length || $t.next('.note-editor').length) return;
    $t.summernote({
      placeholder: 'Property Description',
      tabsize: 2,
      height: 220,
      toolbar: _summernoteToolbar
    });
  });
});
$(document).ready(function() {
  $('#propertyListingTerms').each(function () {
    var $t = $(this);
    if ($t.closest('.modal').length || $t.next('.note-editor').length) return;
    $t.summernote({
      placeholder: 'Terms and conditions',
      tabsize: 2,
      height: 280,
      toolbar: _summernoteToolbar
    });
  });
});
$(document).ready(function() {
  $('#unitDescription').each(function () {
    var $t = $(this);
    if ($t.closest('.modal').length || $t.next('.note-editor').length) return;
    $t.summernote({
      placeholder: 'Unit Description',
      tabsize: 2,
      height: 220,
      toolbar: _summernoteToolbar
    });
  });
});
$(document).ready(function() {
  $('#tripDescription').summernote({
      placeholder: 'Trip Description',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#itinerary').summernote({
      placeholder: 'Trip Itinerary',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#expectations').summernote({
      placeholder: 'Trip expectations',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#recommendations').summernote({
      placeholder: 'Trip recommendations',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#inclusions').summernote({
      placeholder: 'Trip inclusions',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#exclusions').summernote({
      placeholder: 'Trip exclusions',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#storyDescription').summernote({
      placeholder: 'Terms',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('.summernote').summernote({
      placeholder: 'Enter content...',
      tabsize: 2,
      height: 200,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});
$(document).ready(function() {
  $('#eventDescription').summernote({
      placeholder: 'Description',
      tabsize: 2,
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
  });
});

$(document).ready(function() {
    $('#welcomeMessage').summernote({
        placeholder: 'welcome Message',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });
$(document).ready(function() {
    $('#programDescription').summernote({
        placeholder: 'Program Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#description').each(function () {
      var $t = $(this);
      if ($t.closest('.modal').length || $t.next('.note-editor').length) return;
      $t.summernote({
        placeholder: 'Description',
        tabsize: 2,
        height: 200,
        toolbar: _summernoteToolbar
      });
    });
});

$(document).ready(function() {
    $('#postDescription').summernote({
        placeholder: 'Post Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#solution').summernote({
        placeholder: 'Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#founderDescription').summernote({
        placeholder: 'Founder Biography',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#approachDescription').summernote({
        placeholder: 'Approach Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#modelDescription').summernote({
        placeholder: 'Model Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#storyDescription').summernote({
        placeholder: 'Story Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#mission').summernote({
        placeholder: 'Mission Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#vision').summernote({
        placeholder: 'Vision Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#progProblem').summernote({
        placeholder: 'Problem Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#progSolution').summernote({
        placeholder: 'Solution Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#projProblem').summernote({
        placeholder: 'Problem Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#projSolution').summernote({
        placeholder: 'Solution Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#progTestimony').summernote({
        placeholder: 'Testimony Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#bioDescription').summernote({
        placeholder: 'Bio Description',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#terms').summernote({
        placeholder: 'Terms and conditions ',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#privacy').summernote({
        placeholder: 'Our Privacy Terms',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#return').summernote({
        placeholder: 'Our Return Polocies',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });

$(document).ready(function() {
    $('#support').summernote({
        placeholder: 'Our Support Polocies',
        tabsize: 2,
        height: 150,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    });