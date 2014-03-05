/*!
	Mobile & Touch Scripts for Unmark.it
	Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

	$(document).ready(function () {

		// Toggle the Mobile Navigation
		unmark.mobile_nav = function (hide) {
			if (hide) {
				$('.main-wrapper').animate({left: 0}, 400);
				$('.navigation-content').animate({left: '-64'}, 400);
				$('.navigation-content .menu-activator').animate({left: 62}, 400);
			} else {
				$('.main-wrapper').animate({left: 65}, 400);
				$('.navigation-content').animate({left: 0}, 400);
				$('.navigation-content .menu-activator').animate({left: 0}, 400);
				unmark.mobile_sidebar(true); // Hide Mobile Sidebar
			}
		};

		// Toggle the Mobile Sidebar
		unmark.mobile_sidebar = function (hide) {
			if (hide) {
				$('.sidebar-content').show().animate({right: '-85%'}, 600, function () {
					$(this).hide();
				});
			} else {
				$('.sidebar-content').show().css('width', '85%').animate({right: 0 }, 600);
				unmark.mobile_nav(true); // Hide Mobile Nav
			}
		}

		// For Small Phone Size Devices
		if (Modernizr.mq('only screen and (max-width: 480px)')) {

			// Unbind/Bind the Hamburger to show correct sidebar menu
			$('.menu-activator a').off().on('click', function (e) {
				e.preventDefault();
				var open = $('.main-wrapper').css('left');
				if (open === '65px') { unmark.mobile_nav(true); } else { unmark.mobile_nav(); }
			});

			// Mobile Show Sidebar
			$('#mobile-sidebar-show').on('click', function (e) {
				e.preventDefault();
				var open = $('.sidebar-content').css('right');
				if (open === '0px') { unmark.mobile_sidebar(true); } else { unmark.mobile_sidebar(); }
			});

			// Set Max width for view of sidebar expand.
			// Since they are parsed on the fly, we need to update the DOM
			$('.menu-upgrade a, .menu-settings a, .menu-search a').attr('rel', '250');

		}

		// Tablets & Netbooks...
		if (Modernizr.mq('only screen and (max-width: 1024px)')) {
			// Unbind the Hover State for Marks in List as well as click
			$(document).off('mouseenter mouseleave click', '.mark');

			$(document).hammer().on('tap', '.mark', function(e) {
				e.gesture.preventDefault();
				$('.mark-actions').hide();
				$(this).find('.mark-actions').show();
    		});

		}

		// Tablet Portrait & Below
		if (Modernizr.mq('only screen and (max-width: 767px)')) {
			$('.nav-panel a').on('click', function (e) {
				unmark.hideNavigation();
			});
		}


	});

}(window.jQuery));
