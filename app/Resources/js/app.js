$(document).ready(function() {
	'use strict';

	/**
	 * Loader
	 */
	setTimeout(function() {
		$('#loader').addClass('loaded');
		$('body').removeClass('noscroll');
	}, 6000);


	/**
	 * Smooth Scroll
	 */
	$('a.nav-link').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top - 50
				}, 800);
				return false;
			}
		}
	});


	/**
	 * Navigation sidebar btn
	 */
	$('.navbar-btn').on('click', function() {
		$('body').toggleClass('noscroll');
		$('.navbar-collapse').toggleClass('show');
		$('.navbar-btn').toggleClass('animate');
	});
	$('.navbar-custom .nav-link').on('click', function() {
		$('body').removeClass('noscroll');
		$('.navbar-collapse').removeClass('show');
		$('.navbar-btn').removeClass('animate');
	});


	/**
	* Waypoints - Fixed navigation
	*/
	$('#work').waypoint(function(direction) {
		$('header').toggleClass('unfixed-nav');
	}, {
		offset: '90%'
	});
	$('#work').waypoint(function(direction) {
		$('header').toggleClass('fixed-nav');
		$('header').toggleClass('unfixed-nav');
	}, {
		offset: '50%'
	});


	/**
	* Waypoints - Scroll to animation
	*/
	$('.animated').waypoint(function(direction) {
		var animation = $(this.element).attr('data-animation');
		$(this.element).addClass(animation);
		$(this.element).addClass("show");
	}, {
		offset: '75%'
	});
	// Add class for purgecss
	$('.fade-in-left');
	$('.fade-in-right');


	/**
	 * Bootstrap - Scroll Spy enable
	 */
	$('body').scrollspy({
		target: '.navbar-container',
		offset: 60
	});

});


/**
 * AngularJS
 */
var contactApp = angular.module('myApp', ['ngMessages']);

// Change wrapper brackets to avoid conflict with twig
contactApp.config(function($interpolateProvider){
	$interpolateProvider.startSymbol('{[{');
	$interpolateProvider.endSymbol('}]}');
});

contactApp.controller('ContactForm', ['$scope','$http', function($scope, $http) {
	
	$scope.submit = function() {
		// Add loading spinner
		$('.fas-submit').addClass('fa-sync fa-spin').removeClass('fa-paper-plane');

		var url = location.href
		if(url.slice(-1) != '/') {
			url = url + '/';
		}

		// Ajax request
		$http({
			method  : 'POST',
			url     : url + 'contact',
			data    : $.param({ contact_form: $scope.contactData }), 
			headers : {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
		})
		.then(function successCallback(response) {	
			// Clear contact form
			$scope.contactData = {};
			$scope.contact_form.$setPristine();
			// Remove loading spinner
			$('.fas-submit').addClass('fa-paper-plane').removeClass('fa-sync fa-spin');
			$scope.formSuccess = true;
		}, function errorCallback(response) {
			$('.fas-submit').addClass('fa-paper-plane').removeClass('fa-sync fa-spin');
			$scope.formSuccess = false;
			$scope.errorResponse = response.data.errors[0];
		})
	}

}]);
