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
	 * Navigation sidebar toggler
	 */
	$('.navbar-toggler').on('click', function() {
		$('body').toggleClass('noscroll');
		$('.navbar-collapse').toggleClass('show');
		$('.navbar-toggler').toggleClass('animate');
	});
	$('.navbar-custom .nav-link').on('click', function() {
		$('body').removeClass('noscroll');
		$('.navbar-collapse').removeClass('show');
		$('.navbar-toggler').removeClass('animate');
	});


	/**
	* Waypoints - Fixed navigation
	*/
	$('#work').waypoint(function(direction) {
		$('header').toggleClass('unfixed-top');
	}, {
		offset: '90%'
	});
	$('#work').waypoint(function(direction) {
		$('header').toggleClass('fixed-top');
		$('header').toggleClass('unfixed-top');
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

		// Ajax request
		$http({
			method  : 'POST',
			url     : location.href + '/contact',
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
