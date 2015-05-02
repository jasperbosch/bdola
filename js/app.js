var app = angular.module(
		'checkinApp',
		[ 'ui.router', 'ngCookies', 'ui.bootstrap', 'angular-bootstrap-select',
				'angular-bootstrap-select.extra', 'ngRoute', 'angular.filter' ])
	.config(
		function($routeProvider, $locationProvider, $httpProvider) {

			$routeProvider.when('/check', {
				templateUrl : 'snippets/checkin.html',
				controller : 'CheckinCtrl'
			});
			$routeProvider.when('/stsovz', {
				templateUrl : 'snippets/stsovz.html',
				controller : 'StsOvzCtrl'
			});
			$routeProvider.when('/afwovz', {
				templateUrl : 'snippets/afwovz.html',
				controller : 'AfwOvzCtrl'
			});
			$routeProvider.when('/afwmut', {
				templateUrl : 'snippets/afwmut.html',
				controller : 'AfwMutCtrl'
			});
			$routeProvider.when('/prefs', {
				templateUrl : 'snippets/prefs.html',
				controller : 'PrefsCtrl'
			});
			$routeProvider.otherwise({
				redirectTo : 'check',
				controller : 'CheckinCtrl',
			});
		});
