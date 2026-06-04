/** Admin entry — logic lives in assets/js/kdr-summernote-init.js */
(function () {
  if (typeof window.initKdrSummernote === 'function') {
    return;
  }
  console.warn('kdr-summernote-init.js must load before summernote.js');
})();
