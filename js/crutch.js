(function ($, Drupal, settings) {

  "use strict";

  Drupal.behaviors.Crutch = {
    attach: function (context, settings) {
      function strip_tags(input, allowed) {
        allowed = (((allowed || '') + '')
          .toLowerCase()
          .match(/<[a-z][a-z0-9]*>/g) || [])
          .join('');
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
          commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        return input.replace(commentsAndPhpTags, '')
          .replace(tags, function($0, $1) {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
          });
      }
      $(document).bind('ajaxSuccess.Crutch', function() {
        var value = $(".ui-dialog-title");

        if (value.length && !value.hasClass('do-once')) {
          var text = strip_tags($(value).text());
          $(value).text(text);
          value.addClass('do-once');
        }
        $(this).unbind('ajaxSuccess.Crutch');
      });
    }
  };
})(jQuery, Drupal, drupalSettings);


