<?php
//============================================================================
// Name        : keyLib.php
// Author      : Patrick Reipschläger
// Version     : 1.0
// Date        : 08-2013
// Description : Provides several functions for creating and handling keys
//               for the ESE evaluation for students and tutors.
//============================================================================

	// Constant for the file which contains the keys, should be used by all other scripts so it can easily be changed
	define ("KEYFILE", "keys/Keys.csv");
	// Constants for the different key states that are possible, should always be used when altering or checking the state of a key
	define ("KEYSTATE_NONEXISTENT", "nonexistent");
	define ("KEYSTATE_UNISSUED", "unissued");
	define ("KEYSTATE_ISSUED", "issued");
	define ("KEYSTATE_ACTIVATED", "activated");
	define ("KEYSTATE_USED", "used");
	
	/**
	 * Generates a key from the specified hash value.
	 *
	 * @return string
	 */
	function GenerateKey()
	{
		// create a hash using a unique id based on the servers system time and the sha1 hashing algorithm
		$hash = strtoupper(hash("sha1", uniqid()));
		// extract the desired number of digits out of the hash to generate the key
		return substr($hash, 0, 4) . "-" . substr($hash, 4, 4) . "-" . substr($hash, 8, 4) . "-" . substr($hash, 12, 4);
		//return substr($hash, 0, 2) . "-" . substr($hash, 2, 2) . "-" . substr($hash, 4, 2) . "-" . substr($hash, 6, 2);
	}
	/**
	 * Generates the specified amount of keys. Be aware that this function just
	 * creates an array of keys with no state assigned.
	 *
	 * @param integer $amount The amount of keys that should be generated
	 * @return array
	 */
	function GenerateKeys($amount)
	{
		$keys = array();
		for ($i = 0; $i < $amount; $i++)
		{
			array_push($keys, GenerateKey());
			usleep(1);
		}
		return array_unique($keys);
	}
	/**
	 * Generates a new key file with the specified name that contains the specified
	 * amount of newly generated keys.
	 * Returns true if the key file was created successfully, otherwise false.
	 *
	 * @param integer $keyAmount The amount of keys that should be generated.
	 * @param string $fileName The name of the key file that should be created.
	 */
	function CreateKeyFile($keyAmount, $fileName)
	{
		$handle = fopen($fileName, 'w');
		if (!$handle)
			return false;
		$keys = GenerateKeys($keyAmount);
		$data = "Nr;Key;Status\n";
		$count = count($keys) - 1;
		for ($i = 0; $i < $count; $i++)
			$data = $data . $i . ";" . $keys[$i] . ";" . KEYSTATE_UNISSUED. "\n";
		$data = $data . $count . ";" . $keys[$count] . ";". KEYSTATE_UNISSUED;
		fwrite($handle, $data);
		fclose($handle);
		return true;
	}
	/**
	 * Opens the file with the specified name and reads all key data that the file contains.
	 * The resulting data type will be an array of arrays consisting of the key and its state.
	 * Returns null if the key file could not be found or read.
	 *
	 * @param string $fileName The file which should be read
	 * @return array
	 */
	function ReadKeyFile($fileName)
	{
		if (!file_exists($fileName))
			return null;
		$handle = fopen($fileName, 'r');
		if (!$handle)
			return null;
		$data = fread($handle, filesize($fileName));
		$lines = explode("\n", $data);
		$keyData = array();
		for ($i = 1; $i < count($lines); $i++)
		{
			$tmp = explode(";", $lines[$i]);
			array_push($keyData, array($tmp[1], $tmp[2]));
		}
		return $keyData;
	}
	/**
	 * Writes the specified key data to the file with the specified name.
	 * The data type of the $keyData should be an array of arrays consisting of the key and its state.
	 * Returns true if the key file was successfully written, otherwise false.
	 *
	 * @param string $fileName The name of the file to which the key data should be written.
	 * @param array $keyData The key data which should be written to the file. Passed by reference.
	 * @return boolean
	 */
	function WriteKeyFile($fileName, &$keyData)
	{
		if (!isset($fileName) || !isset($keyData))
			return false;
		$handle = fopen($fileName, 'w+');
		if ($handle == null)
			return false;
		// debug code
		//echo "<script type='text/javascript'>alert('file opened');</script>";
		$data = "Nr;Key;Status\n";
		$count = count($keyData) - 1;
		for ($i = 0; $i < $count; $i++)
			$data = $data . $i . ";" . $keyData[$i][0] . ";" . $keyData[$i][1] . "\n";
		$data = $data . $count . ";" . $keyData[$count][0] . ";" . $keyData[$count][1];
		$res = fwrite($handle, $data);
		// debug Code
		//if ($res == false)
		//	echo "<script type='text/javascript'>alert('file not written');</script>";
		//else
		//	echo "<script type='text/javascript'>alert('file written');</script>";
		fclose($handle);
		if ($res) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
	 * Get the current state of the specified key which will be one of the defines
	 * KEYSTATE constants.
	 * The data type of the key data should be an array of arrays consisting of the key and its state.
	 *
	 * @param array $keyData The key data array in which the key should be found. Passed by reference.
	 * @param string $key The key which state should be got. Passed by reference.
	 * @return integer
	 */
	function GetKeyState(&$keyData, &$key)
	{
		for ($i = 0; $i < count($keyData); $i++)
			if ($key == $keyData[$i][0])
				return $keyData[$i][1];
		return KEYSTATE_NONEXISTENT;
	}
	/**
	 * Set the state of the specified key to the specified state.
	 * The data type of the $keyData should be an array of arrays consisting of the key and its state.
	 * Returns true if the key was found within the key data and the state has been changed, otherwise false.
	 *
	 * @param array $keyData The key data array in which the key should be found. Passed by reference.
	 * @param string $key The key which state should be changed.
	 * @param integer $newState The new state of the specified key. Must be on of the KEYSTATE constants.
	 * @return boolean
	 */
	function SetKeyState(&$keyData, &$key, $newState)
	{
		for ($i = 0; $i < count($keyData); $i++)
			if ($key == $keyData[$i][0])
			{
				$keyData[$i][1] = $newState;
				return true;
			}
		return false;
	}
?>
