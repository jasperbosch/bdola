app.controller('LoginController', function($scope, $rootScope, AuthService) {
	$scope.credentials = {
		username : '',
		password : ''
	};
	$scope.login = function(credentials) {
		AuthService.login(credentials).then(function(user) {
			if (user.username !== undefined) {
				// console.log("Login OK");
				$scope.setCurrentUser(user);
				$scope.setLocatie(user.locatie);
				$scope.setCheckstate(user.checkstatus);
				$scope.setAlerts([ {
					type : 'success',
					msg : "User logged in"
				} ]);
			} else {
				// console.log("Login NOK");
				$scope.setAlerts(user);
			}
		}, function(msg) {
			// console.log("Login NOK");
			$scope.setAlerts(msg);
		});
	};
	$scope.logout = function() {
		AuthService.logout().then(function() {
			// console.log("Logout OK");
			$scope.visible = true;
			$scope.setCurrentUser(null);
			$scope.setAlerts([ {
				type : 'success',
				msg : "User logged out"
			} ]);
		}, function() {
			// console.log("Logout NOK");
		});
	}
	$scope.loginstatus = function(credentials) {
		AuthService.loginstatus().then(function(user) {
			if (user !== undefined) {
				$scope.setCurrentUser(user);
				$scope.setLocatie(user.locatie);
				$scope.setCheckstate(user.checkstatus);
			}
		}, function(msg) {
			$scope.setAlerts(msg);
		});
	};
	$scope.loginstatus();
})

app.controller('LocatieCtrl', function($scope, $timeout, Session) {
	$scope.model = $scope.locatie;
	$scope.locaties = [ 'Thuis', 'Walterbos F3.43', 'Walterbos F3.49' ];

	$scope.$watch(function(scope) {
		return scope.model
	}, function(newValue) {
		Session.setLocatie(newValue);
		$scope.setLocatie(newValue);
	});

	$scope.selectWithOptionsIsVisible = true;
})

app.controller('ApplicationController', function($scope, AuthService,
		CheckinService) {
	$scope.currentUser = null;
	$scope.isAuthorized = AuthService.isAuthorized;
	$scope.checkstate = false;
	$scope.locatie = null;

	$scope.isLoginPage = false;

	// (scope.getCurrentUser()==null);

	$scope.setCurrentUser = function(user) {
		$scope.currentUser = user;
	};

	$scope.getCurrentUser = function() {
		return $scope.currentUser;
	};

	$scope.getCheckstate = function() {
		if ($scope.checkstate) {
			return "disabled";
		} else {
			return "";
		}
	}

	$scope.getLocation = function() {
		if ($scope.locatie != null) {
			return "disabled";
		} else {
			return "";
		}
	}

	$scope.alerts = [];

	$scope.setAlerts = function(alerts) {
		$scope.alerts = alerts;
	}

	$scope.addAlert = function(alert) {
		$scope.alerts.push(alert);
	};

	$scope.closeAlert = function(index) {
		$scope.alerts.splice(index, 1);
	};

	$scope.checkin = function() {
		CheckinService.checkin().then(function(res) {
			if (res !== undefined) {
				$scope.setAlerts(res);
			} else {
				// $scope.setAlerts([]);
			}
		});
	}

	$scope.checkout = function() {
		CheckinService.checkout().then(function(res) {
			if (res !== undefined) {
				$scope.setAlerts(res);
			} else {
				// $scope.setAlerts([]);
			}
		});
	}

	$scope.setLocatie = function(locatie) {
		$scope.locatie = locatie;
	}

	$scope.setCheckstate = function(checkstate) {
		$scope.checkstate = checkstate;
	}
})

app.controller('NavCtrl', [ '$scope', '$location', function($scope, $location) {
	$scope.navClass = function(page) {
		var currentRoute = $location.path().substring(1) || 'stsovz';
		return page === currentRoute ? 'active' : '';
	};
} ]);

app.controller('StsOvzCtrl', function($scope, $compile, CheckinService) {
	$scope.data;
	$scope.init = function(){
		CheckinService.getStatus().then(function(result){
			if (result.error === undefined) {
				$scope.data = result;
			} else {
				$scope.setAlerts(result.error);
			}
		});
	};
	$scope.init();

});

app.controller('AfwOvzCtrl', function($scope, $compile) {
	console.log('inside home controller');

});

app.controller('AfwMutCtrl', function($scope, $compile) {
	console.log('inside contact controller');

});

app.controller('PrefsCtrl', function($scope, $compile, PrefsService) {
	$scope.teams;
	$scope.prefs;

	$scope.initPrefs = function() {
		$scope.prefs = {
				phone : '',
				team : ''
			};
		PrefsService.getteams().then(function(result) {
			if (result.data.error === undefined) {
				$scope.teams = result.data;
				PrefsService.getprefs().then(function(result) {
					if (result.data.error === undefined) {
						$scope.prefs.phone = result.data[0].phone;
						$scope.prefs.team = result.data[0].team;
					} else {
						$scope.setAlerts(result.data.error);
					}
				});
			} else {
				$scope.setAlerts(result.data.error);
			}
		});
	}
	$scope.initPrefs();

	$scope.save = function(prefs) {
		PrefsService.save(prefs).then(function(result) {
			if (result.data.data !== undefined) {
				$scope.setAlerts([ {
					type : 'success',
					msg : "Preferences succesvol opgeslagen."
				} ]);
			} else {
				$scope.setAlerts(result.data.error);
			}
		}, function(msg) {
			$scope.setAlerts(msg);
		});
	};

});