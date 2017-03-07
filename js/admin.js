(function ($, window, Drupal, drupalSettings) {

  /*global atmTpl */

  'use strict';

  Drupal.AjaxCommands.prototype.redirectInNewTab = function(ajax, response, status){
    if (status == 'success') {
      open(response.url, '_target');
    }
  };

  $.fn.toggleAttr = function(attr, attr1, attr2) {
    return this.each(function() {
      var self = $(this);
      if (self.attr(attr) === attr1)
        self.attr(attr, attr2);
      else
        self.attr(attr, attr1);
    });
  };

  window.atmCloseModal = function () {
    $('#atm-terms-modal').hide();
  }

  $(function () {

    function toggleTemplates() {
      var id = $(this.$el).parent().attr('id');
      var componentName = $('#' + id).data('view-component');

      if (componentName) {
        $('div[data-view-component="' + componentName + '"]').each(function (i, element) {
          var id = $(element).attr('id');
          var $element = $('#' + id);
          var view = $element.data('view');

          $element.toggleAttr('data-open', 'true', 'false');

          var state = $element.attr('data-open');

          if (view === 'expanded' && state === 'false') {
            atmTemplates[componentName].expanded.small(true);
            atmTemplates[componentName].collapsed.small(false);
          }

          if (view === 'expanded' && state === 'true') {
            atmTemplates[componentName].expanded.small(false);
            atmTemplates[componentName].collapsed.small(true);
          }

          atmTemplates[componentName].collapsed.redraw();
          atmTemplates[componentName].expanded.redraw();

          atmTemplates[componentName].collapsed.watch('showModalBody', toggleTemplates);
          atmTemplates[componentName].expanded.watch('showModalBody', toggleTemplates);
        });
      }
    }

    function updateComponent(componentName) {
      var options = {};
      var styles = {};

      $('.options-component-' + componentName).each(function (index, element) {
        var $element = $(element);
        var optionName = $element.data('option-name');
        options[optionName] = $element.val();
        styles[optionName] = {};
      });

      $('.styles-component-' + componentName).each(function (index, element) {
        var $element = $(element);
        var styleName = $element.data('style-name');
        var styleValue = $element.val();
        var $options = $($element.data('option-name').split(' '));

        $options.each(function (i, optionName) {
          styles[optionName][styleName] = styleValue;
        });
      });

      try {
        atmTemplating.updateTemplate(componentName + 'Component', options, styles);

        for (var comp in atmTemplates) {
          if (typeof atmTemplates[comp].expanded != 'undefined') {
            atmTemplates[comp].expanded.redraw();
            atmTemplates[comp].collapsed.redraw();

            atmTemplates[comp].expanded.watch('showModalBody', toggleTemplates);
            atmTemplates[comp].collapsed.watch('showModalBody', toggleTemplates);
          }
        }

        var output = atmTemplating.templateRendition(componentName + 'Component').render(options, styles);
      } catch (error) {

        noty({
          type: 'error',
          maxVisible: 1,
          timeout: 2000,
          text: error.message
        });

        return;
      }

      var $template = $(".templates-" + componentName);

      $template.val(
        JSON.stringify(output)
      );
      $template.removeAttr("disabled");
    }

    atmTpl.default.config({revenueMethod: 'micropayments'});
    var atmTemplating = atmTpl.default;

    var atmTemplates = {
      "pledge": {
        "expanded": atmTemplating.render('pledge', '#render-pledge-expanded'),
        "collapsed": atmTemplating.render('pledge', '#render-pledge-collapsed')
      },
      "pay": {
        "expanded": atmTemplating.render('pay', '#render-pay-expanded'),
        "collapsed": atmTemplating.render('pay', '#render-pay-collapsed')
      },
      "refund": {
        "expanded": atmTemplating.render('refund', '#render-refund-expanded'),
        "collapsed": atmTemplating.render('refund', '#render-refund-collapsed')
      },
      "auth": {}
    };

    for (var comp in atmTemplates) {
      if (typeof atmTemplates[comp].expanded !== 'undefined') {
        atmTemplates[comp].expanded.small(false);
      }

      updateComponent(comp);
    }

    $('.js-component-options, .js-component-styles, .js-sync-values').on('change keyup', function() {
      var $this = $(this);

      if ($this.hasClass('js-sync-values')) {
        var classSync = $this.data('class-sync');
        $('.' + classSync).not(this).val($this.val());
      }

      var componentName = $(this).data('component-name');
      updateComponent(componentName);
    });

    var $body = $('body');
    var modal =
      '<div id="atm-terms-modal" class="atm-modal">' +
        '<div class="atm-modal-content">' +
          '<span class="atm-close" onclick="atmCloseModal()">×</span>' +
          '<h1 class="atm-modal-header">Terms of Use</h1>' +
          '<div id="atm-terms-modal-content"></div>' +
        '</div>' +
      '</div>'
    ;
    $body.append(modal);

    $('#atm-terms').on('click', function (event) {
      event.preventDefault();

      $.ajax({
        url: '/atm/terms',
        dataType: 'json'
      })
        .done(function (response) {
          if (!response.error) {
            $('#atm-terms-modal-content').html(response.content);
            $('#atm-terms-modal').show();
          }
        })
    });

    $('.accordion-details').find('details').on('click', function (event) {
      if (event.target.nodeName === 'SUMMARY') {
        $(this).siblings().removeAttr('open');
      }
    });

  });
})(jQuery, window, Drupal, drupalSettings);
