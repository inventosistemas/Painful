(function ($) {
	$(function () {

		$('#footer div, #footer ul').contents().filter(function () {
			return this.nodeType == 3;
		}).remove();

		/*--------------------------------------------------------
			DROPDOWN
		--------------------------------------------------------*/
		$('.drop-toggle').on('click', function () {
			var element = $(this).siblings(".drop-content");
			if ($(this).hasClass('active')) {
				$(this).removeClass('active').siblings(element).removeClass('active');
			} else {
				$(this).addClass('active').siblings(element).addClass('active');
			}
		});

		/*--------------------------------------------------------
			FIXED HEADER
		--------------------------------------------------------*/
		var wWin = $(window).width();
		var hHeader = $('#header').height();

		$(window).scroll(function () {
			hHeader = $('#header').height();
		});

		var nav = $('#fixed-header');
		$(window).scroll(function () {
			if ($(this).scrollTop() > hHeader) {
				nav.addClass("active");
			} else {
				nav.removeClass("active");
			}
		});

		/*--------------------------------------------------------
			MENU MOBILE
		--------------------------------------------------------*/
		$('.menu-mb').on('click', function () {
			var element = $(this).siblings(".menu-mb-content");
			if ($(this).hasClass('active')) {
				$('body').removeClass('menu-open');
				$(this).removeClass('active').siblings(element).removeClass('active');
			} else {
				$('body').addClass('menu-open');
				$(this).addClass('active').siblings(element).addClass('active');
			}
		});
		$('.close-menu').on('click', function () {
			$('body').removeClass('menu-open');
			$('.menu-mb').removeClass('active');
			$('.menu-mb-content').removeClass('active');
		});

		/*--------------------------------------------------------
			SLICK
		--------------------------------------------------------*/
		if ($('.main-banner').length) {
			$('.main-banner').addClass('active');
			$('.main-banner').slick({
				dots: true,
				speed: 400,
				autoplay: true,
				autoplaySpeed: 5000,
			});
		}

		$('.product-slider').slick({
			dots: false,
			infinite: true,
			speed: 500,
			slidesToShow: 4,
			slidesToScroll: 4,
			responsive: [
				{
					breakpoint: 1200,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						infinite: true,
						dots: true
					}
			}, {
					breakpoint: 768,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2
					}
			}, {
					breakpoint: 521,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
			}
		]
		});

		/*--------------------------------------------------------
			INSTAFEED
		--------------------------------------------------------*/
		var feed = new Instafeed({
			get: 'user',
			limit: '12',
			sortBy: 'most-recent',
			tagName: 'instagram_list',
			userId: 2182797290,
			accessToken: '2182797290.39f32e2.3560d64a75544e9cbe5d5727fd9938a5',
			resolution: 'low_resolution',
			template: '<a href="{{link}}" target="_blank" title="{{caption}}" class="photo-i"><div class="inner" style="background-image:url({{image}});"><img src="{{image}}" alt="{{caption}}" /><div class="box-like"><div class="center"><span class="likes">{{likes}}</span><span class="comments">{{comments}}</span></div></div></div></a>',
			after: setSlick,
		});

		function setSlick() {
			$('#instafeed').slick({
				dots: false,
				infinite: true,
				speed: 500,
				slidesToShow: 6,
				slidesToScroll: 6,
				responsive: [
					{
						breakpoint: 1025,
						settings: {
							slidesToShow: 5,
							slidesToScroll: 5
						}
					}, {
						breakpoint: 768,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 4
						}
					}, {
						breakpoint: 601,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3
						}
					}, {
						breakpoint: 521,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2
						}
					}
				]
			});
		}

		feed.run();

		/*--------------------------------------------------------
			UI
		--------------------------------------------------------*/
		$('.select').selectmenu();
		$('#orderSearch').selectmenu({
			change: function (event, data) {
				filtrarBusca(data.item.value);
			}
		});

		/*--------------------------------------------------------
			PRODUCT GALLERY
		--------------------------------------------------------*/
		if ($('#large-img').length) {
			$('#box-thumb').slick({
				vertical: true,
				infinite: false,
				speed: 500,
				slidesToShow: 5,
				slidesToScroll: 5,
				responsive: [
					{
						breakpoint: 1024,
						settings: {
							vertical: false,
							slidesToShow: 5,
							slidesToScroll: 5
						}
				},
					{
						breakpoint: 521,
						settings: {
							vertical: false,
							slidesToShow: 4,
							slidesToScroll: 4
						}
				}
			]
			});

			function iniZoom() {
				$('.box-zoom img').elevateZoom({
					gallery: 'box-thumb',
					cursor: 'pointer',
					galleryActiveClass: 'active',
					imageCrossfade: true,
					zoomType: 'inner',
					responsive: true,
					zoomWindowFadeIn: 500,
					zoomWindowFadeOut: 750,
				});
			}
			iniZoom();

			$(window).resize(function () {
				$('#box-thumb a.active').trigger('click');
			});

			$('#box-thumb a:not(.thumb-video)').on('click', function (e) {
				$('.box-video').fadeOut();
				$('.box-zoom').fadeIn();
				$('.zoomContainer').css('display', 'block');
			});

			$('.thumb-video').on('click', function (e) {
				e.preventDefault();
				$('.box-zoom').fadeOut();
				$('.box-video').fadeIn();
				$('.zoomContainer').css('display', 'none');
			});

		}

		/*-------------------------------------------
			QUANTITY BUTTON
		-------------------------------------------*/
		$('.qty').keyup(function () {
			var valor = $(this).val().replace(/[^0-9]+/g, '');
			if (valor == 0) {
				$(this).val(1);
			} else {
				$(this).val(valor);
			}
		});

		$('.i-increase').click(function (e) {
			e.preventDefault();
			fieldName = $(this).attr('field');
			var currentVal = parseInt($('input[name=' + fieldName + ']').val());
			if (!isNaN(currentVal)) {
				$('input[name=' + fieldName + ']').val(currentVal + 1);
			} else {
				$('input[name=' + fieldName + ']').val(1);
			}
		});

		$('.i-disincrease').click(function (e) {
			e.preventDefault();
			fieldName = $(this).attr('field');
			var currentVal = parseInt($('input[name=' + fieldName + ']').val());
			if (!isNaN(currentVal) && currentVal > 1) {
				$('input[name=' + fieldName + ']').val(currentVal - 1);
			} else {
				$('input[name=' + fieldName + ']').val(1);
			}
		});

		/*--------------------------------------------------------
			DEFAULT INVENTO
		--------------------------------------------------------*/

	});
})(jQuery);