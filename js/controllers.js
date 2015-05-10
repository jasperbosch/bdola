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
				$scope.setStuffAfterLogin();
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
	$scope.check;

	$scope.isLoginPage = false;

	$scope.init = function() {
		$scope.check = {
			location : -1,
			checkstate : null,
			locationname : ''
		};
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
		$scope.prefs = {
			team : '',
			functie : '',
			mo : 8,
			tu : 8,
			we : 8,
			th : 8,
			vr : 8,
			sa : 0,
			su : 0
		};
		$scope.setStuffAfterLogin();
	}

	$scope.setStuffAfterLogin = function() {
		AuthService.loginstatus().then(function(user) {
			if (user !== undefined) {
				$scope.setCurrentUser(user);
			}
		}, function(msg) {
			$scope.setAlerts(msg);
		});
		CheckinService.getLocatie().then(function(result) {
			$scope.check.location = result.locatie;
			$scope.check.checkstate = (result != "null");
			$scope.check.locationname = result.locatienaam;
		});
		PrefsService.getprefs().then(function(result) {
			if (result.data.error === undefined) {
				$scope.prefs.phone = result.data.phone;
				$scope.prefs.team = result.data.team;
				$scope.prefs.mo = result.data.mo;
				$scope.prefs.tu = result.data.tu;
				$scope.prefs.we = result.data.we;
				$scope.prefs.th = result.data.th;
				$scope.prefs.vr = result.data.vr;
				$scope.prefs.sa = result.data.sa;
				$scope.prefs.su = result.data.su;
			} else {
				$scope.setAlerts(result.data.error);
			}
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
		// $scope.model.location = locatie;
	}

	$scope.setCheckstate = function(checkstate) {
		// $scope.model.checkstate = checkstate;
	}
})

app.controller('CheckinCtrl', function($scope, CheckinService) {
	$scope.init = function() {
		// $("#menubutton").click();
	}
	$scope.init();

	$scope.checkin = function() {
		CheckinService.checkin($scope.check).then(function(res) {
			if (res.error !== undefined) {
				$scope.setAlerts(res.error);
			} else {
				$scope.check.locationname = res.locatie;
				$scope.check.checkstate = true;
			}
		});
	}

	$scope.checkout = function() {
		CheckinService.checkout().then(function(res) {
			if (res !== undefined) {
				$scope.setAlerts(res.error);
			} else {
				$scope.check.locationname = '';
				$scope.check.checkstate = false;
			}
		});
	}

	$scope.locationDisabled = function() {
		if ($scope.check.checkstate == null || !$scope.check.checkstate) {
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

app.controller('AfwOvzCtrl', function($scope, $compile, SprintService) {
	$scope.data;
	$scope.init = function() {
		SprintService.getOverzicht().then(function(result){
			if (result.error == undefined){
				$scope.data = result.data;
			} else {
				$scope.setAlerts(result.error);
			}
			
		});
	}
	$scope.init();

});

app.controller('AfwMutCtrl', function($scope, $compile, CalenderService) {
	$scope.calender;

	$scope.init = function() {
		// $("#menubutton").click();
		CalenderService.getMutCal(0).then(function(result) {
			$scope.calender = result.data;
		});
	}
	$scope.init();

	$scope.prev = function() {
		CalenderService.getMutCal(-1).then(function(result) {
			$scope.calender = result.data;
		});
	}

	$scope.next = function() {
		CalenderService.getMutCal(1).then(function(result) {
			$scope.calender = result.data;
		});
	}

});

app.controller('PrefsCtrl', function($scope, $compile, PrefsService) {
	// $scope.prefs;

	$scope.initPrefs = function() {
		// $("#menubutton").click();
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

app.controller('dagCtrl', function($scope, CalenderService) {

	$scope.dag;
	
	$scope.click = function() {
		switch ($scope.dag.soort) {
		case 'K':
			$scope.dag.soort = 'T';
			break;
		case 'S':
		case 'T':
			$scope.dag.soort = 'C';
			$scope.dag.uren = 0.0;
			break;
		case 'C':
			if ($scope.currentUser.displayname=='Jasper'){
				// Speciaal(alleen) voor Jasper(mij) een DHN status. 
				$scope.dag.soort = 'D';
			} else {
				$scope.dag.soort = 'V';
			}
			$scope.dag.uren = 0.0;
			break;
		case 'D':
			$scope.dag.soort = 'V';
			$scope.dag.uren = 0.0;
			break;
		case 'V':
			if ($scope.dag.isVerplichtVrij){
				// niets doen
			} else if ($scope.dag.isSprintstart) {
				$scope.dag.soort = 'S';
			} else {
				$scope.dag.soort = 'M';
				switch ($scope.dag.dow) {
				case 1:
					$scope.dag.uren = $scope.prefs.mo;
					break;
				case 2:
					$scope.dag.uren = $scope.prefs.tu;
					break;
				case 3:
					$scope.dag.uren = $scope.prefs.we;
					break;
				case 4:
					$scope.dag.uren = $scope.prefs.th;
					break;
				case 5:
					$scope.dag.uren = $scope.prefs.vr;
					break;
				case 6:
					$scope.dag.uren = $scope.prefs.sa;
					break;
				case 7:
					$scope.dag.uren = $scope.prefs.su;
					break;
				default:
					break;
				}
			}
			break;
		case 'M':
			$scope.dag.soort = 'M';
			break;
		default:
			$scope.dag.soort = 'K';
			break;
		}
		CalenderService.savedag($scope.dag);
	}

	$scope.savedag = function() {
		$scope.dag.soort = 'K'
		CalenderService.savedag($scope.dag);
	}

});