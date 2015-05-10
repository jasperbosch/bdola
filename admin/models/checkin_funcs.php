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
	$row = array();
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
	$row = array();
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

/**
 * Sprint
 */
// Retrieve information for all prints
function fetchAllSprints() {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		id,
		naam,
		datum
		FROM ch_sprints
		ORDER BY datum" );
	$stmt->execute ();
	$stmt->bind_result ( $id, $naam, $datum );
	$row = array();
	while ( $stmt->fetch () ) {
		$row [] = array (
				'id' => $id,
				'naam' => $naam,
				'datum' => $datum
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level name exists in the DB
function sprintNameExists($sprint) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT id
		FROM ch_sprints
		WHERE
		naam = ?
		LIMIT 1" );
	$stmt->bind_param ( "s", $sprint );
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

// Create a sprint in DB
function createSprint($naam) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "INSERT INTO ch_sprints (
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

// Delete a sprint from the DB
function deleteSprints($sprint) {
	global $mysqli, $errors;
	$i = 0;
	$stmt = $mysqli->prepare ( "DELETE FROM ch_sprints
		WHERE id = ?" );
	foreach ( $sprint as $id ) {
		$stmt->bind_param ( "i", $id );
		$stmt->execute ();
		$i ++;
	}
	$stmt->close ();
	return $i;
}

// Change a sprint's name
function updateSprint($id, $naam, $datum) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "UPDATE ch_sprints
		SET naam = ?,
			datum = ?
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "ssi", $naam, $datum, $id );
	$result = $stmt->execute ();
	$stmt->close ();
	return $result;
}

// Retrieve information for a single permission level
function fetchSprintDetails($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		id,
		naam,
		datum
		FROM ch_sprints
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "i", $id );
	$stmt->execute ();
	$stmt->bind_result ( $id, $naam, $datum );
	$row = array ();
	while ( $stmt->fetch () ) {
		$row = array (
				'id' => $id,
				'naam' => $naam,
				'datum' => $datum
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level ID exists in the DB
function sprintIdExists($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT id
		FROM ch_sprints
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
 * Vrijedagen
 */
// Retrieve information for all prints
function fetchAllVrijedagens() {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		datum
		FROM ch_vrijedagen" );
	$stmt->execute ();
	$stmt->bind_result (  $datum );
	$row = array();
	while ( $stmt->fetch () ) {
		$row [] = array (
				'datum' => $datum
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level name exists in the DB
function vrijedagenNameExists($vrijedagen) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT datum
		FROM ch_vrijedagen
		WHERE
		datum = ?
		LIMIT 1" );
	$stmt->bind_param ( "s", $vrijedagen );
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

// Create a vrijedagen in DB
function createVrijedagen($datum) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "INSERT INTO ch_vrijedagen (
		datum
		)
		VALUES (
		?
		)" );
	$stmt->bind_param ( "s", $datum );
	$result = $stmt->execute ();
	$stmt->close ();
	return $result;
}

// Delete a vrijedagen from the DB
function deleteVrijedagens($id) {
	global $mysqli, $errors;
	$i = 0;
	$stmt = $mysqli->prepare ( "DELETE FROM ch_vrijedagen
		WHERE datum = ?" );
	foreach ( $id as $vrijedagen ) {
		$stmt->bind_param ( "s", $vrijedagen );
		$stmt->execute ();
		$i ++;
	}
	$stmt->close ();
	return $i;
}

// Change a vrijedagen's name
function updateVrijedagen($id, $naam, $datum) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "UPDATE ch_vrijedagen
		SET naam = ?,
			datum = ?
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "ssi", $naam, $datum, $id );
	$result = $stmt->execute ();
	$stmt->close ();
	return $result;
}

// Retrieve information for a single permission level
function fetchVrijedagenDetails($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT
		id,
		naam,
		datum
		FROM ch_vrijedagen
		WHERE
		id = ?
		LIMIT 1" );
	$stmt->bind_param ( "i", $id );
	$stmt->execute ();
	$stmt->bind_result ( $id, $naam, $datum );
	$row = array ();
	while ( $stmt->fetch () ) {
		$row = array (
				'id' => $id,
				'naam' => $naam,
				'datum' => $datum
		);
	}
	$stmt->close ();
	return ($row);
}

// Check if a permission level ID exists in the DB
function vrijedagenIdExists($id) {
	global $mysqli;
	$stmt = $mysqli->prepare ( "SELECT id
		FROM ch_vrijedagen
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
