/**
 * Kigali Drive — unified Summernote initialization (admin + owner forms).
 */
(function ($) {
  'use strict';

  if (typeof $ === 'undefined' || !$.fn.summernote) {
    return;
  }

  window._summernoteToolbar = window._summernoteToolbar || [
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'clear']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['table', ['table']],
    ['insert', ['link', 'picture', 'video']],
    ['view', ['fullscreen', 'codeview', 'help']]
  ];

  var LEGACY_IDS = [
    'Blogs', 'hotelDescription', 'leftBags', 'ticketing', 'roomDescription',
    'propertyDescription', 'propertyListingTerms', 'unitDescription', 'tripDescription',
    'itinerary', 'expectations', 'recommendations', 'inclusions', 'exclusions',
    'storyDescription', 'eventDescription', 'welcomeMessage', 'aboutDescription',
    'whatWeDo', 'whyChooseUs', 'programDescription', 'description', 'postDescription',
    'solution', 'founderDescription', 'approachDescription', 'modelDescription',
    'mission', 'vision', 'progProblem', 'progSolution', 'projProblem', 'projSolution',
    'progTestimony', 'bioDescription', 'terms', 'privacy', 'return', 'support',
    'facilityDescription', 'partnerDescription', 'carDescription', 'roomDescriptionModal',
    'serviceDescription', 'privacyPolicy', 'privacyDetails', 'cookiesPolicy',
    'refundsPolicy', 'bookingCancellation', 'listingCommission', 'paymentMethods',
    'returnPolicy', 'supportPolicy'
  ];

  function isInitialized($ta) {
    return $ta.next('.note-editor').length > 0;
  }

  window.initKdrSummernote = function (root, options) {
    options = options || {};
    root = root ? $(root) : $(document);

    var selectors = ['.summernote', '[data-summernote="true"]'];
    LEGACY_IDS.forEach(function (id) {
      selectors.push('#' + id);
    });

    var seen = {};
    root.find(selectors.join(',')).each(function () {
      var $ta = $(this);
      if (!$ta.is('textarea') || isInitialized($ta)) {
        return;
      }
      var key = $ta.attr('id') || $ta.attr('name') || selectors.join(',') + '-' + seen.length;
      if (seen[key]) {
        return;
      }
      seen[key] = true;

      var height = parseInt($ta.data('height'), 10) || options.height || 200;
      var placeholder = $ta.data('placeholder') || options.placeholder || 'Enter content…';

      $ta.summernote({
        placeholder: placeholder,
        tabsize: 2,
        height: height,
        toolbar: window._summernoteToolbar,
        disableDragAndDrop: true
      });
    });
  };

  window.syncKdrSummernote = function (root) {
    root = root ? $(root) : $(document);
    root.find('textarea').each(function () {
      var $ta = $(this);
      if (isInitialized($ta)) {
        try {
          $ta.val($ta.summernote('code'));
        } catch (e) { /* ignore */ }
      }
    });
  };

  $(function () {
    initKdrSummernote(document);

    $(document).on('submit', 'form', function () {
      syncKdrSummernote(this);
    });

    $(document).on('shown.bs.modal', '.modal', function () {
      initKdrSummernote(this);
    });
  });
})(window.jQuery);
