function notif(poto,nama,ket){$.notify({	icon: 'poto/'+poto+'.jpg',	title: nama,	message: ket},{	type: 'minimalist',	z_index: 10015,	delay: 2500,	icon_type: 'image',	placement: {		from: "bottom",		align: "right"				},	template: '<div data-notify="container" class="6u 12u(xsmall) alert alert-{0}" role="alert">' +		'<img data-notify="icon" class="image avatar pull-left">' +		'<span data-notify="title">{1}</span>' +		'<span data-notify="message">{2}</span>' +	'</div>'});};var jml = 0;function exes(arr_dt) {			var id = arr_dt.map(a => a.id);		var nm = arr_dt.map(a => a.nm);		var kt = arr_dt.map(a => a.kt);  var min = 4,    max = 12;  var rand = Math.floor(Math.random() * (max - min + 1) + min);   notif(id[jml],nm[jml],kt[jml]);  jml = jml +1;  timer = setTimeout('exes(arr_dt)', rand * 1000);  if(jml >= 29){	  clearTimeout(timer);      timer = 0;  };};
(function($) {

	skel.breakpoints({
		xlarge: '(max-width: 1680px)',
		large: '(max-width: 1280px)',
		medium: '(max-width: 1024px)',
		small: '(max-width: 736px)',
		xsmall: '(max-width: 480px)'
	});

	$(function() {

		var $body = $('body'),
			$header = $('#header'),
			$nav = $('#nav'), $nav_a = $nav.find('a'),
			$wrapper = $('#wrapper');

		// Fix: Placeholder polyfill.
			$('form').placeholder();

		// Prioritize "important" elements on medium.
			skel.on('+medium -medium', function() {
				$.prioritize(
					'.important\\28 medium\\29',
					skel.breakpoint('medium').active
				);
			});

		// Header.
			var ids = [];

			// Set up nav items.
				$nav_a
					.scrolly({ offset: 44 })
					.on('click', function(event) {

						var $this = $(this),
							href = $this.attr('href');

						// Not an internal link? Bail.
							if (href.charAt(0) != '#')
								return;

						// Prevent default behavior.
							event.preventDefault();

						// Remove active class from all links and mark them as locked (so scrollzer leaves them alone).
							$nav_a
								.removeClass('active')
								.addClass('scrollzer-locked');

						// Set active class on this link.
							$this.addClass('active');

					})
					.each(function() {

						var $this = $(this),
							href = $this.attr('href'),
							id;

						// Not an internal link? Bail.
							if (href.charAt(0) != '#')
								return;

						// Add to scrollzer ID list.
							id = href.substring(1);
							$this.attr('id', id + '-link');
							ids.push(id);

					});

			// Initialize scrollzer.
				$.scrollzer(ids, { pad: 300, lastHack: true });

		// Off-Canvas Navigation.

			// Title Bar.
				$(
					'<div id="titleBar">' +
						'<a href="#header" class="toggle"></a>' +
						'<span class="title">' + $('#logo').html() + '</span>' +
					'</div>'
				)
					.appendTo($body);

			// Header.
				$('#header')
					.panel({
						delay: 500,
						hideOnClick: true,
						hideOnSwipe: true,
						resetScroll: true,
						resetForms: true,
						side: 'right',
						target: $body,
						visibleClass: 'header-visible'
					});

			// Fix: Remove navPanel transitions on WP<10 (poor/buggy performance).
				if (skel.vars.os == 'wp' && skel.vars.osVersion < 10)
					$('#titleBar, #header, #wrapper')
						.css('transition', 'none');

	});

})(jQuery);