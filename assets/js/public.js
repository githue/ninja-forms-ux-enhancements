(function () {
	// Retrieve our plugin settings from the global window object.
	function is_enabled(option) {
		var settings = window.hasOwnProperty('NF_UXEnhancements') ? NF_UXEnhancements : {};

		return settings.hasOwnProperty(option) ? settings[option] : false;
	}

	var extendFormRender = Backbone.View.extend({
		initialize: function () {
			this.listenTo(Backbone.Radio.channel('form'), 'render:view', this.handleFormRender);
		},

		handleFormRender: function (view) {
			if (is_enabled('browserSaveData')) {
				this.initBrowserSave(view);
			}

			if (is_enabled('cssLayout')) {
				this.initCssLayout(view);
			}
		},

		/**
		 * Allows the web browser to remember users' submitted data.
		 *
		 * Adds a hidden button to every Ninja Form and a 'submit' listener
		 * to each form element. Should work in all modern browsers.
		 *
		 * Here's why this works:
		 * https://groups.google.com/a/chromium.org/d/msg/chromium-discuss/Kt1K1PMrtJU/InNIeoIuBgAJ
		 */
		initBrowserSave: function (view) {
			// Button's default type is always 'submit' according to spec.
			var button = document.createElement('button');

			button.style.display = 'none';
			button.classList.add('nf-ux-enhancements-browser-save');

			var formEl = view.getRegion('formLayout').el.querySelector('form');

			if (!formEl) return;

			formEl.addEventListener('submit', this.handleSubmit);

			formEl.appendChild(button);
		},

		/**
		 * @param {Event} ev Form submit event.
		 */
		handleSubmit: function (ev) {
			// Browser won't save user input without preventDefault()
			ev.preventDefault();
		},

		/**
		 * Gets the user-defined container classes and removes them from the
		 * original container, then applies them to the <nf-field> element.
		 */
		initCssLayout: function (view) {
			function moveClasses(container) {
				var layoutEl = container.parentNode;

				// It's fine to override class attr in this manner because it's the same
				// way in which the template sets it.
				var allClasses = container.getAttribute('class');
				var nfClasses = allClasses.replace(container_classes, '');

				container.setAttribute('class', nfClasses);

				if (layoutEl && layoutEl.tagName === 'NF-FIELD') {
					var classesArray = container_classes.split(' ');

					for (var i = 0; i < classesArray.length; i++) {
						layoutEl.classList.add(classesArray[i]);
					}
				}
			}

			// Loop over all the fields within the current form.
			var fields = view.options.fieldCollection.models;

			for (var i = 0; i < fields.length; i++) {
				var field = fields[i];

				if (!field.hasOwnProperty('attributes')) continue;

				var container_classes = field.attributes.container_class;

				if (!container_classes.length) continue;

				var selector = 'nf-field-' + field.id + '-container';
				var container = document.getElementById(selector);

				if (container) {
					moveClasses(container);
				}
			}
		}
	});

	var extendFormSubmit = Marionette.Object.extend({
		// After form validates, trigger submit event by clicking our
		// custom submit button.
		initialize: function () {
			if (!nfForms.length) return;

			for (var i = 0; i < nfForms.length; i++) {
				var id = nfForms[i].id;
				this.listenTo(nfRadio.channel('form-' + id), 'after:submitValidation', this.submitForm);
			}
		},


		submitForm: function (model) {
			// Trigger native submit event on the <form>, which is handled by
			// our event listener.
			var id = model.id
			var formEl = document.querySelector('#nf-form-' + id + '-cont');

			if (!formEl) return;

			var realSubmit = formEl.querySelector('.nf-ux-enhancements-browser-save');

			if (realSubmit) realSubmit.click();
		}
	});

	jQuery(document).ready(function ($) {
		new extendFormRender();

		if (is_enabled('browserSaveData')) {
			new extendFormSubmit();
		}
	});
})();
