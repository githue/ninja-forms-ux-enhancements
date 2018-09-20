/**
 * Allows the web browser to remember users' submitted data.
 * 
 * Adds a hidden button to every Ninja Form and a 'submit' listener
 * to each form element. Should work in all modern browsers.
 * 
 * Here's why this works:
 * https://groups.google.com/a/chromium.org/d/msg/chromium-discuss/Kt1K1PMrtJU/InNIeoIuBgAJ
 */
(function () {
  var browserSaveData = Backbone.View.extend({
    // Begin by adding a hidden button inside each form element.
    initialize: function () {
      this.listenTo(Backbone.Radio.channel('form'), 'render:view', this.handleFormRender);
    },

    handleFormRender: function (view) {
      var button = document.createElement('button');

      button.style.display = 'none';
      button.classList.add('nf-ux-enhancements-browser-save');

      var formEl = view.getRegion('formLayout').el.querySelector('form');

      if (!formEl) return;

      formEl.addEventListener('submit', this.handleSubmit);

      formEl.appendChild(button);
    },

    handleSubmit: function (ev) {
      // It won't work without preventDefault()
      ev.preventDefault();
    }
  });

  var stopStartSubmission = Marionette.Object.extend({
    // Reply to the maybe:submit channel, so we can do something before the form disappears.
    initialize: function () {
      if (!nfForms.length) return;

      for (var i = 0; i < nfForms.length; i++) {
        var id = nfForms[i].id;
        Backbone.Radio.channel('form-' + id).reply('maybe:submit', this.beforeSubmit, this, id);
      }
    },


    beforeSubmit: function (model) {
      // Automatically and silently click our button to trigger the <form> submit event.
      var id = model.id
      var formEl = document.querySelector('#nf-form-' + id + '-cont');

      if (!formEl) return;

      var realSubmit = formEl.querySelector('.nf-ux-enhancements-browser-save');

      if (realSubmit) realSubmit.click();
    }
  });

  jQuery(document).ready(function ($) {
    new browserSaveData();
    new stopStartSubmission();
  });
})();
