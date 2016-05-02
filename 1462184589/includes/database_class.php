<?php

class Database {

	// Function to the database and tables and fill them with the default data
	function create_database($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['dbusername'],$data['dbpassword'],'');
       
		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Create the prepared statement
		$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['database']);

		// Close the connection
		$mysqli->close();

		return true;
	}

	// Function to create the tables and fill them with the default data
	function create_tables($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['dbusername'],$data['dbpassword'],$data['database']);

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Open the default SQL file
		$query = file_get_contents('assets/install.sql');


        
        //replacing database information
        $new=str_replace('%DBPREFIX%', $data['dbprefix'], $query);
        foreach ($data as $key => $value) {
        	# code...
        	$search="%".strtoupper($key)."%";

            $new=str_replace("$search", "$value", $new);
        }

		// Execute a multi query
		$mysqli->multi_query($new);

		// Close the connection
		$mysqli->close();

		return true;
	}
}