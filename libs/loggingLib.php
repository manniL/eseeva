<?php
//============================================================================
// Name        : loggingLib.php
// Author      : Patrick Reipschläger
// Version     : 1.0
// Date        : 08-2013
// Description : Provides functions for creating, reading and appending
//               log files and data for the ESE evaluation for students
//               and tutors.
//============================================================================

	define ("STUDENTLOGFILE", "logs/ESE_studentLog.txt");
	define ("TUTORLOGFILE", "logs/ESE_tutorLog.txt");
	
	/**
	 * Reads all question data out of a provided log file array that has been split by new lines.
	 * This function should NOT BE CALLED directly, instead the ReadLogFile function should be
	 * used to read a log file as a whole.
	 * All data is written to the specified question data array and
	 * True is returned if a valid question data block was found, otherwise false.
	 *
	 * @param array $lines An array of string resembling the different lines of the log file. Passed by reference.
	 * @param integer $index The index within the lines array by which the processing should start.
	 *        The index is passed by reference and will be incremented by this function.
	 * @param array $questionData An array to which all question data is written to.
	 * @return boolean.
	 */
	function ReadLogQuestionData(&$lines, &$index, &$questionData)
	{
		// check if the current line is a question block
		// and return false if that is not the case
		if (trim($lines[$index]) != "###QuestionData###")
			return false;
		// the index must be incremented so the next loop starts at the right line
		$index++;
		while($index < count($lines))
		{
			// if the character '#' indicates that the question data block has been finished
			// so the loop is completed
			if ($lines[$index][0] == "#")
				break;
			// split the current line by the character ';' to get the individual data elements
			$tmp = explode(";", $lines[$index]);
			// set identifier is the first data element and is used to init the field of the
			// current question in the questionData array
			$questionData[$tmp[0]] = array();
			// push all other data elements in the previously created array
			for ($i = 1; $i < count($tmp); $i++)
				array_push($questionData[$tmp[0]], $tmp[$i]);
			// increment the index to continue with the next line of the file
			$index++;
		}
		return true;
	}
	/**
	 * Reads all tutor data out of a provided log file array that has been split by new lines.
	 * This function should NOT BE CALLED directly, instead the ReadLogFile function should be
	 * used to read a log file as a whole.
	 * All data is written to the specified tutor data array.
	 * True is returned if a valid tutor data block was found, otherwise false.
	 *
	 * @param array $lines An array of string resembling the different lines of the log file. Passed by reference.
	 * @param integer $index The index within the lines array by which the processing should start.
	 *        The index is passed by reference and will be incremented by this function.
	 * @param array $tutorData An array to which all tutor data is written to.
	 * @return boolean.
	 */
	function ReadLogTutorData(&$lines, &$index, &$tutorData)
	{
		// check if the current line is a tutor data block
		// and return false if that is not the case
		if (trim($lines[$index]) != "###TutorData###")
			return false;
		// the index must be incremented so the next loop starts at the right line
		$index++;
		// parse the tutor data - its basically the same as the question data
		while($index < count($lines))
		{
			// if the character '#' indicates that the tutor data block has been finished
			// so the loop is completed
			if ($lines[$index][0] == "#")
				break;
			// split the current line by the character ';' to get the individual data elements
			$tmp = explode(";", $lines[$index]);
			// set identifier is the first data element and is used to init the field of the
			// current tutor in the tutorData array
			$tutorData[$tmp[0]] = array();
			// push all other data elements in the previously created array
			for ($i = 1; $i < count($tmp); $i++)
				array_push($tutorData[$tmp[0]], $tmp[$i]);
			// increment the index to continue with the next line of the file
			$index++;
		}
		return true;
	}
	/**
	 * Reads all comment data out of a provided log file array that has been split by new lines.
	 * This function should NOT BE CALLED directly, instead the ReadLogFile function should be
	 * used to read a log file as a whole.
	 * All data is written to the specified comment data array.
	 * True is returned if a valid comment data block was found, otherwise false.
	 *
	 * @param array $lines An array of string resembling the different lines of the log file. Passed by reference.
	 * @param integer $index The index within the lines array by which the processing should start.
	 *        The index is passed by reference and will be incremented by this function.
	 * @param array $commentData An array to which all comment data is written to.
	 * @return boolean.
	 */
	function ReadLogCommentData(&$lines, &$index, &$commentData)
	{
		// check if the current line is a comment data block
		// and return false if that is not the case
		if (trim($lines[$index]) != "###CommentData###")
			return false;
		// the index must be incremented so the next loop starts at the right line
		$index++;
		// the current comment that gets pushed to the commentData array
		// when a comment end sequence has been found
		$comment = "";
		// parse the comment data
		while($index < count($lines))
		{
			// if the line start with the character '~' the line is
			// interpreted as the comment end sequence and the current
			// comment is pushed at the end of the comment array
			// and the loop continues with a new comment
			if (trim($lines[$index]) != "" && $lines[$index][0] == "~")
			{
				// the last character of the comment is erased because it's
				// just an unnecessary new line
				array_push($commentData, substr($comment, 0, -1));
				$comment = "";
			}
			// otherwise the current comment is simply appended by the
			// current line
			else
				$comment = $comment . $lines[$index] . "\n";
			// increment the index to continue with the next line of the file
			$index++;
		}
		return true;
	}
	/**
	 * Reads the log file with the specified name and writes all log data to the corresponding arrays.
	 * Returns true if the log file has been successfully written, otherwise false.
	 * Is guaranteed to initialize the specified log arrays, even when the log file could not be
	 * read in which case they will simply be empty.
	 * 
	 * @param array $questionData Will be created by this function and is passed by reference.
	 *                            Is an array of arrays that consists of the unique id, the question
	 *                            and the fields for the possible response options which contain
	 *                            the number of times that option has been selected.
	 * @param array $tutorData    Will be created by this function and is passed by reference.
	 *                            Is an array of arrays that consists of the tutor name and the
	 *                            fields for the possible response options which contain the number
	 *                            of times that option has been selected.
	 * @param array $commentData  Will be created by this function and is passed by reference.
	 *                            Is an array of string that resemble the different comments that
	 *                            have been made.
	 */
	function ReadLogFile($fileName, &$questionData, &$tutorData, &$commentData)
	{
		// init arrays regardless of the file not being found or eventual errors
		$questionData = array();
		$tutorData = array();
		$commentData = array();
		// return if the file does not exist
		if (file_exists($fileName) == false)
			return false;
		// open the file and get its length
		$handle = fopen($fileName, 'r');
		if (!$handle)
			return false;
		$length = filesize($fileName);
		// if the length is zero, nothing can be read, so return
		if ($length == 0)
			return false;
		// otherwise read the file and split the string at each new line
		$fileData = fread($handle, $length);
		$lines = explode("\n", $fileData);
		fclose($handle);
		// begin parsing of the file, the beginning is the second line, because the
		// first should contains the disclaimer
		$index = 1;
		ReadLogQuestionData($lines, $index, $questionData);
		ReadLogTutorData($lines, $index, $tutorData);
		ReadLogCommentData($lines, $index, $commentData);
		return true;
	}
	/**
	 * Writes the specified log arrays to a log file with the specified name.
	 * Expects data in the same format as provided by the ReadLogFile function.
	 * Returns true if the file was written successfully, otherwise false.
	 *
	 * @param string $fileName The name of the file to which the log data should be written.
	 * @param array $questionData The question data that should be written to the log file.
	 * @param array $tutorData The tutor data that should be written to the log file.
	 * @param array $commentData The list of comments that should be written to the log file. Passed by reference.
	 * @return boolean
	 */
	function WriteLogFile($fileName, &$questionData, &$tutorData, &$commentData)
	{
		$handle = fopen($fileName, 'c');
		if (!$handle)
			return false;
		// write disclaimer and question data block identifier
		$fileData = "# Automatically generated file - Do not Change ! #\n###QuestionData###\n";
		// write all question data to the file
		foreach($questionData as $id => $entry)
		{
			// the order is id, question and the number of times each of the six answers was picked
			$fileData = $fileData . $id . ";";
			for ($i = 0; $i < 6; $i++)
				$fileData = $fileData . $entry[$i] . ";";
			$fileData = $fileData . $entry[6] . "\n";
		}
		// write tutor data block identifier
		$fileData = $fileData . "###TutorData###\n";
		// write all tutor data to the file
		foreach($tutorData as $id => $entry)
		{
			// the order is tutor name and the number of times each of the six answers was picked
			$fileData = $fileData . $id . ";";
			for ($i = 0; $i < 5; $i++)
				$fileData = $fileData . $entry[$i] . ";";
			$fileData = $fileData . $entry[5] . "\n";
		}
		// write comment data block identifier
		$fileData = $fileData . "###CommentData###";
		// write comment data
		foreach($commentData as $comment)
			// each comment is escapd by a new line containing three tilde characters
			$fileData = $fileData . "\n" . $comment . "\n~~~";
		// write the generated data to the file and close it
		// use exclusive lock
		flock($handle, LOCK_EX);
		ftruncate($handle, 0);
		fwrite($handle, $fileData);
		fflush($handle);
		flock($handle, LOCK_UN);
		fclose($handle);
		return true;
	}
	/**
	 * Add the question data from the specified questionnaire form data to an existing form data array.
	 * 
	 * @param array $formData The list of all form elements which for most cases will simply be the
	 *                        $_POST array that has been submitted by the questionnaire form.
	 *                        Passed by reference.
	 * @param array $commentData The list of existing question data that will be appended by the question
	 *                           Data of the form. Passed by reference.
	 */
	function AddQuestionData(&$formData, &$questionData, &$questionnaire)
	{
		foreach($formData as $id => $value)
		{
			if (!isset($questionnaire[$id]))
				continue;
			// get the type of the element with the same id as the form element from the questionnaire
			$type = $questionnaire[$id][0];
			// check if the element is a question on continue with the next one if that is not the case
			if ($type != "Question")
				continue;
			// if there is not field for the current element in the question dsta array, create a new
			// blank field containing the question and zeros for the number of times each answer was picked
			if (array_key_exists($id, $questionData) == false)
				$questionData[$id] = array($questionnaire[$id][1], 0, 0, 0, 0, 0, 0);
			// increment the answer that was selected in the formular by one
			$questionData[$id][(int)$value]++;
		}
	}
	/**
	 * Add the tutor data from the specified questionnaire form data to an existing tutor data array.
	 * 
	 * @param array $formData The list of all form elements which for most cases will simply be the
	 *                        $_POST array that has been submitted by the questionnaire form.
	 *                        Passed by reference.
	 * @param array $commentData The list of existing tutor data that will be appended by the tutor
	 *                           Data of the form. Passed by reference.
	 */
	function AddTutorData(&$formData, &$tutorData)
	{
		// get the name of the tutor from the form
		$tutorName = $formData["tutorName"];
		// get the selected answer of the tutorRating from the form
		$tutorValue = $formData["tutorRating"];
		// if there is no field for the current tutor in the tutor array, create a new
		// nlank one with zeros for the number of times each answer was picked
		if (array_key_exists($tutorName, $tutorData) == false)
			$tutorData[$tutorName] = array(0, 0, 0, 0, 0, 0);
		// increment the answer that was selected in the form by one
		$tutorData[$tutorName][$tutorValue - 1]++;
	}
	/**
	 * Add the comment data from the specified questionnaire form data to an existing comment data array.
	 * 
	 * @param array $formData The list of all form elements which for most cases will simply be the
	 *                        $_POST array that has been submitted by the questionnaire form.
	 *                        Passed by reference.
	 * @param array $commentData The list of existing comments that will be appended by the comment
	 *                           Data of the form. Passed by reference.
	 */
	function AddCommentData(&$formData, &$commentData)
	{
		// if the comment field was filled, the comment
		// array is appended by the new comment
		if (array_key_exists("comment", $formData) && trim($formData["comment"]) != "")
			array_push($commentData, $formData["comment"]);
	}
?>
