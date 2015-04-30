app.factory('AuthService', function($http, Session) {
	var authService = {};

	authService.login = function(credentials) {
		return $http.post('server/login.php', credentials).then(
				function(res) {
					if (res.data.user != null) {
						Session.create(res.data.id, res.data.user.username,
								res.data.user.role, res.data.user.displayname,
								res.data.user.checkstatus,
								res.data.user.locatie);
						return res.data.user;
					} else {
						return res.data.error;
					}
				});
	};

	authService.logout = function() {
		// console.log("logout");
		return $http.post('server/logout.php', Session.getUserId()).then(
				function() {
					Session.destroy();
					return;
				});
	}

	authService.loginstatus = function() {
		return $http.get('server/loginstatus.php').then(
				function(res) {
					if (res.data.user != null) {
						Session.create(res.data.id, res.data.user.username,
								res.data.user.role, res.data.user.displayname,
								res.data.user.checkstatus,
								res.data.user.locatie);
						return res.data.user;
					} else {
						Session.destroy();
					}
				});
	}

	authService.isAuthenticated = function() {
		return !!Session.userId;
	};

	authService.isAuthorized = function(authorizedRoles) {
		if (!angular.isArray(authorizedRoles)) {
			authorizedRoles = [ authorizedRoles ];
		}
		return (authService.isAuthenticated() && authorizedRoles
				.indexOf(Session.userRole) !== -1);
	};

	return authService;
})

app.factory('CheckinService', function($http, Session) {
	var checkinService = {};

	checkinService.checkin = function() {
		checkin = {
			'userid' : Session.getUserId(),
			'locatie' : Session.getLocatie()
		};
		return $http.post('server/checkin.php', checkin).then(function(res) {
			return res.data.error;
		});
	};
	checkinService.checkout = function() {
		checkout = {
			'userid' : Session.getUserId()
		};
		return $http.post('server/checkout.php', checkout).then(function(res) {
			return res.data.error;
		});
	};
	checkinService.getStatus = function() {
		return $http.get('server/getcheckstatus.php').then(function(res){
			return res.data;
		});
	}
	return checkinService;
})

app.factory('PrefsService', function($http, Session) {
	var prefsService = {};

	prefsService.save = function(prefs) {
		return $http.post('server/saveprefs.php', prefs).then(
				function(res) {
					return res;
				});
	};
	
	prefsService.getprefs = function(){
		return $http.get('server/getprefs.php').then(
				function(result){
					return result;
				}
			);
	}
	
	prefsService.getteams = function(){
		return $http.get('server/getteams.php').then(
				function(result){
					return result;
				}
			);
	}
	return prefsService;
})