(function ($) {

	$.fn.ueMenu = function (jsSetting) {
		this.each(function () {

			/////////////
			// Element //
			/////////////

			var _ = $(this);

			//////////////
			// Settings //
			//////////////

			var defaultSetting = {
				type: 'linked', // linked, unlinked
				optionsList: '.ue-options-menu',
				floatedMenu: '.ue-floated-menu',
				optionsListTarget: '.ue-list-option',
				floatedMenuTarget: '.ue-floated-option'
			};

			var htmlSetting = _.data();
			var setting = $.extend(defaultSetting, jsSetting, htmlSetting);

			///////////////
			// Variables //
			///////////////
			var menuContainerWidth = Math.floor(_.width()),
				floatedMenuWidth = Math.ceil(_.find(setting.floatedMenu).width()),
				optionsLimit = 0,
				menuSizes = [];

			///////////////
			// Functions //
			///////////////
			function update() {
				//Update Variables
				menuContainerWidth = Math.floor(_.width());
				floatedMenuWidth = Math.ceil(_.find(setting.floatedMenu).width());
				menuSizes = [];

				//Build menu array
				var aux = 0;
				_.find(setting.optionsList).find(setting.optionsListTarget).each(function (index) {
					var child = {};
					aux += Math.ceil($(this).width());
					child.optionWidth = aux;
					child.optionsLimit = index + 1;
					menuSizes.push(child);
				});

				//Find options limit
				aux = 0;
				var o;
				for (o = 0; o < _.find(setting.optionsList).find(setting.optionsListTarget).length; o += 1) {
					if (menuSizes[o].optionWidth + floatedMenuWidth < menuContainerWidth) {
						aux += menuSizes[o].optionWidth;
						optionsLimit = menuSizes[o].optionsLimit;
					} else {
						break;
					}
				}
				type();
			};

			function type() {
				_.find(setting.optionsList).find(setting.optionsListTarget).removeClass('ue-menu-visible');
				var o;
				for (o = 0; o < optionsLimit; o += 1) {
					_.find(setting.optionsList).find(setting.optionsListTarget).eq(o).addClass('ue-menu-visible');
				}
				if (setting.type == 'linked') {
					_.find(setting.floatedMenu).find(setting.floatedMenuTarget).removeClass('ue-menu-visible');
					var o;
					for (o = optionsLimit; o < _.find(setting.floatedMenu).find(setting.floatedMenuTarget).length; o += 1) {
						_.find(setting.floatedMenu).find(setting.floatedMenuTarget).eq(o).addClass('ue-menu-visible');
					}
				}
			}

			function init() {
				update();
			}
			init();

			////////////
			// Events //
			////////////

			$(window).on('resize', function () {
				update();
			});

			return this;
		});
	};
}(jQuery));

$(document).ready(function () {
	$('.ue-menu').ueMenu();
});