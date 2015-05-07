<?php
/**
 * Locations
 */
// Retrieve information for all locaties
function fetchAllLocaties() {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		id,
		locatie
		FROM ch_locaties" );
	$stmt->execute ();
	$stmt->bind_result ( $id, $locatie );
	
	while ( $stmt->fetch () ) {
		$row [] = array (
				'id' => $id,
				'locatie' => $locatie 
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level name exists in the DB
function locationNameExists($location) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT id
		FROM ch_locaties
		WHERE
		locatie = ?
		LIMIT 1" );
	$stmt->bind_param ( "s", $location );
	$stmt->execute ();
	$stmt->store_result ();
	$num_returns = $stmt->num_rows;
	$stmt->close ();
	
	if ($num_returns > 0) {
		return true;
	} else {
		return false;
	}
}

// Create a location level in DB
function createLocation($location) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "INSERT INTO ch_locaties (
		locatie
		)
		VALUES (
		?
		)" );
	$stmt->bind_param ( "s", $location );
	$result = $stmt->execute ();
	$stmt->close ();
	return $result;
}

// Delete a location level from the DB
function deleteLocations($permission) {
	global $mysqli, $errors;
	$i = 0;
	$stmt = $mysqli->prepare ( "DELETE FROM ch_locaties 
		WHERE id = ?" );
	foreach ( $permission as $id ) {
		$stmt->bind_param ( "i", $id );
		$stmt->execute ();
		$i ++;
	}
	$stmt->close ();
	return $i;
}

// Change a location's name
function updateLocationName($id, $location) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "UPDATE ch_locaties
		SET locatie = ?
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "si", $location, $id );
	$result = $stmt->execute ();
	$stmt->close ();
	return $result;
}

// Retrieve information for a single permission level
function fetchLocationDetails($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		id,
		locatie
		FROM ch_locaties
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "i", $id );
	$stmt->execute ();
	$stmt->bind_result ( $id, $location );
	$row = array ();
	while ( $stmt->fetch () ) {
		$row = array (
				'id' => $id,
				'location' => $location 
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level ID exists in the DB
function locationIdExists($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT id
		FROM ch_locaties
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "i", $id );
	$stmt->execute ();
	$stmt->store_result ();
	$num_returns = $stmt->num_rows;
	$stmt->close ();
	
	if ($num_returns > 0) {
		return true;
	} else {
		return false;
	}
}

/**
 * Teams
 */
// Retrieve information for all teams
function fetchAllTeams() {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		id,
		naam,
		rgb
		FROM ch_teams" );
	$stmt->execute ();
	$stmt->bind_result ( $id, $naam, $rgb );
	
	while ( $stmt->fetch () ) {
		$row [] = array (
				'id' => $id,
				'naam' => $naam,
				'rgb' => $rgb 
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level name exists in the DB
function teamNameExists($team) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT id
		FROM ch_teams
		WHERE
		naam = ? 
		LIMIT 1" );
	$stmt->bind_param ( "s", $team );
	$stmt->execute ();
	$stmt->store_result ();
	$num_returns = $stmt->num_rows;
	$stmt->close ();
	
	if ($num_returns > 0) {
		return true;
	} else {
		return false;
	}
}

// Create a team in DB
function createTeam($naam) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "INSERT INTO ch_teams (
		naam
		)
		VALUES (
		?
		)" );
	$stmt->bind_param ( "s", $naam );
	$result = $stmt->execute ();
	$stmt->close ();
	return $result;
}

// Delete a team from the DB
function deleteTeams($team) {
	global $mysqli, $errors;
	$i = 0;
	$stmt = $mysqli->prepare ( "DELETE FROM ch_teams
		WHERE id = ?" );
	foreach ( $team as $id ) {
		$stmt->bind_param ( "i", $id );
		$stmt->execute ();
		$i ++;
	}
	$stmt->close ();
	return $i;
}

// Change a team's name
function updateTeam($id, $naam, $rgb) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "UPDATE ch_teams
		SET naam = ?,
			rgb = ?
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "ssi", $naam, $rgb, $id );
	$result = $stmt->execute ();
	$stmt->close ();
	return $result;
}

// Retrieve information for a single permission level
function fetchTeamDetails($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		id,
		naam,
		rgb
		FROM ch_teams
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "i", $id );
	$stmt->execute ();
	$stmt->bind_result ( $id, $naam, $rgb );
	$row = array ();
	while ( $stmt->fetch () ) {
		$row = array (
				'id' => $id,
				'naam' => $naam,
				'rgb' => $rgb 
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level ID exists in the DB
function teamIdExists($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT id
		FROM ch_teams
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "i", $id );
	$stmt->execute ();
	$stmt->store_result ();
	$num_returns = $stmt->num_rows;
	$stmt->close ();
	
	if ($num_returns > 0) {
		return true;
	} else {
		return false;
	}
}
