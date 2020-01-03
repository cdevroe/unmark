/*!
	Mobile & Touch Scripts
*/

(function ($) {

	$(document).ready(function () {

		// Toggle the Mobile Navigation
		unmark.mobile_nav = function (hide) {
			if (hide) {
				unmark.mainpanels.removeClass('nav-active');
				unmark.hamburger.removeClass('active');
				unmark.mobile_header.removeClass('shift-right');
			} else {
				$('.main-wrapper').animate({left: 65}, 400);
				$('.navigation-content').animate({left: 0}, 400);
				$('.navigation-content .menu-activator').animate({left: 0}, 400);
				unmark.mobile_sidebar(true); // Hide Mobile Sidebar
			}
			return false;
		};

		// Toggle the Mobile Sidebar
		unmark.mobile_sidebar = function (hide) {
			if (hide) {
				$('.sidebar-content').show().animate({right: '-85%'}, 600, function () {
					$(this).hide();
					$('a#mobile-sidebar-show i').removeClass('icon-heading_close').addClass('icon-ellipsis');
				});
			} else {
				$('a#mobile-sidebar-show i').removeClass('.icon-ellipsis').addClass('icon-heading_close');
				unmark.mobile_nav(true); // Hide Mobile Nav
			}
			return false;
		}

		// For Small Phone Size Devices
		if (Modernizr.mq('only screen and (max-width: 767px)')) {

			// Unbind/Bind the Hamburger to show correct sidebar menu
			$('.menu-activator a').off().on('click', function (e) {
				e.preventDefault();
				var open = $('.main-wrapper').css('left');
				if (open === '65px') { unmark.mobile_nav(true); } else { unmark.mobile_nav(); }
				return false;
			});

			// Mobile Show Sidebar
			$('#mobile-sidebar-show').on('click', function (e) {
				e.preventDefault();
				var open = $('#unmark-wrapper');
				if ( open.hasClass('sidebar-active') ) {
					unmark.mobile_sidebar(true); } else { unmark.mobile_sidebar();
				}
				return false;
			});

			// Set Max width for view of sidebar expand.
			// Since they are parsed on the fly, we need to update the DOM
			$('.menu-upgrade a, .menu-settings a, .menu-search a').attr('rel', '250');

		}

	});

}(window.jQuery));
