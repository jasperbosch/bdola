app.controller('LoginController', function($scope, $rootScope, AuthService) {
	$scope.credentials = {
		username : '',
		password : ''
	};
	$scope.login = function(credentials) {
		AuthService.login(credentials).then(function(user) {
			if (user.username !== undefined) {
//				 console.log("Login OK");
				$scope.setCurrentUser(user);
//				$scope.setLocatie(user.locatie);
//				$scope.setCheckstate(user.checkstatus);
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
})

app.controller('ApplicationController', function($scope, AuthService,
		CheckinService, PrefsService) {
	$scope.currentUser = null;
	$scope.isAuthorized = AuthService.isAuthorized;
	$scope.teams;

	$scope.isLoginPage = false;

	$scope.init = function() {
		CheckinService.getLocaties().then(function(result) {
			if (result.error === undefined) {
				$scope.locaties = result;
			} else {
				$scope.setAlerts(result.error);
			}
		});
		PrefsService.getteams().then(function(result) {
			if (result.data.error === undefined) {
				$scope.teams = result.data;
			} else {
				$scope.setAlerts(result.data.error);
			}
		});
		AuthService.loginstatus().then(function(user) {
			if (user !== undefined) {
				$scope.setCurrentUser(user);
			}
		}, function(msg) {
			$scope.setAlerts(msg);
		});
	}
	$scope.init();

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

	$scope.getLocatie = function() {
		if ($scope.location != null) {
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

	$scope.setLocatie = function(locatie) {
//		$scope.model.location = locatie;
	}

	$scope.setCheckstate = function(checkstate) {
//		$scope.model.checkstate = checkstate;
	}
})

app.controller('CheckinCtrl',function($scope, CheckinService) {
	$scope.check;
	$scope.init = function() {
		$scope.check = {
			location : -1 ,
			checkstate : null
		};
			CheckinService.getLocatie().then(function(result){
				$scope.check.location = result.locatie;
				$scope.check.checkstate= (result != "null");
		});
	}
	$scope.init();
	
	$scope.checkin = function() {
		CheckinService.checkin($scope.check).then(function(res) {
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
	
	$scope.locationDisabled = function(){
		if ($scope.check.checkstate == null || !$scope.check.checkstate){
			return true;
		} else {
			return false;
		}
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
	$scope.init = function() {
		CheckinService.getStatus().then(function(result) {
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
	$scope.prefs;

	$scope.initPrefs = function() {
		$scope.prefs = {
			phone : '',
			team : ''
		};
		PrefsService.getprefs().then(function(result) {
			if (result.data.error === undefined) {
				$scope.prefs.phone = result.data.phone;
				$scope.prefs.team = result.data.team;
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