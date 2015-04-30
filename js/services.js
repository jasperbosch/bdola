app.service('Session', function() {
	this.create = function(sessionId, userId, userRole, username,checkstatus, locatie) {
		this.id = sessionId;
		this.userId = userId;
		this.userRole = userRole;
		this.username = username;
		this.locatie = locatie;
		this.checkstatus = checkstatus;
	};
	this.destroy = function() {
		this.id = null;
		this.userId = null;
		this.userRole = null;
		this.username = null;
		this.locatie = null;
		this.checkstatus = null;
	};
	this.getUserId = function() {
		return this.userId;
	}
	this.setLocatie = function(locatie){
		this.locatie = locatie;
	}
	this.getLocatie = function(){
		return this.locatie;
	}
})