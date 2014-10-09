<?php
//============================================================================
// Name        : questionnaireLib.php
// Authors     : Patrick Reipschläger, Lucas Woltmann
// Version     : 1.1
// Date        : 07-2014
// Description : Provides functions for handling questionnaire text files
//               for the ESE questionnaire for students and tutors.
//============================================================================

	define ("STUDENT_QUESTIONNAIRE", "questionnaires/student_questionnaire.txt");
	define ("TUTOR_QUESTIONNAIRE", "questionnaires/tutor_questionnaire.txt");
	define ("PATRONS", "questionnaires/patrons.txt");
	// Possible Questionnaire Element Types
	// Should be used by all scripts when referencing them
	/**
	 * Headline element
	 * Parameter:
	 * - Text for the Headline
	 */
	define ("Q_HEADLINE", "Headline");
	/**
	 * TextBox Element
	 * Parameter:
	 * - Label for the TextBox
	 */
	define ("Q_TEXTBOX", "TextBox");
	/**
	 * Legend Element
	 * no Parameter
	 */
	define ("Q_LEGEND", "Legend");
	/**
	 * Question Element
	 * Parameter:
	 * - Label for the Question
	 */
	define ("Q_QUESTION", "Question");
	/**
	 * Comment Element
	 * Parameter:
	 * - Label for the CommentBox
	 */
	define ("Q_COMMENT", "Comment");
	/**
	 * DropDown Element
	 * Parameter:
	 * - Label for the DropDownMenu
	 */
	define ("Q_DROPDOWN", "DropDown");

	/**
	 * Reads the questionnaire file with the specified name and returns an array
	 * resembling the questionnaire data. The array consists of array indexed by
	 * the unique ids of the elements that was specified within the questionnaire
	 * file. The element arrays itself consist the type of the element, and a list
	 * of parameters depending on the type of element.
	 * If the file could not be opened or read, null is returned.
	 *
	 * @param string $fileName The name of the file that should be read.
	 * @return array
	 */
	function ReadQuestionnaireFile($fileName)
	{
		if (!file_exists($fileName))
			return null;
		$handle = fopen($fileName, 'r');
		if (!$handle)
			return null;
		$rawData =  fread($handle, filesize($fileName));
		$lines = explode("\n", $rawData);
		$data = array();
		for ($i = 1; $i < count($lines); $i++)
			if (trim($lines[$i]) != "" && $lines[$i][0] != "#")
			{
				$tmp = explode(";", $lines[$i]);
				$entry = array();
				for ($j = 1; $j < count($tmp); $j++)
					array_push($entry, trim($tmp[$j]));
				$data[trim($tmp[0])] = $entry;
			}
		return $data;
	}

	function ReadPatronsFile($fileName)
	{
		if (!file_exists($fileName))
			return null;
		$handle = fopen($fileName, 'r');
		if (!$handle)
			return null;
		$rawData =  fread($handle, filesize($fileName));
		$lines = explode("\n", $rawData);
		$data = array();
		for ($i = 0; $i < count($lines); $i++)
			if(trim($lines[$i]) != "")
				$data[trim($lines[$i])] = trim($lines[$i]);

		return $data;
	}
?>