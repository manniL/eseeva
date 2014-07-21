<?php
//============================================================================
// Name        : analysis.php
// Author      : Patrick Reipschläger
// Version     : 0.5
// Date        : 08-2013
// Description : Analysis a ESE Evaluation log file. The file that is analysed
//               may be passed as parameter with the URL.
//============================================================================
	include_once 'libs/formLib.php';
	include_once 'libs/questionnaireLib.php';
	include_once 'libs/loggingLib.php';
  include_once 'libs/chartLib.php';
	
	// variables for the log data
	$questionData;
	$tutorData;
	$commentData;
	// Default log file is the student log file defined in 'loggingLib.php'
	$logFile = STUDENTLOGFILE;
	// if a logFile parameter has been passed in the URL, than that value will
	// be used instead of the default value (with the added folder name)
	if (isset($_GET["logFile"]))
		$logFile = "logs/" . $_GET["logFile"];
	// read the existing log file, if there is no existing log file, the RadLogFile
	// function guarantees the initialization of the log variables, which will
	// result in the same outcome as if an empty log file is read	
	ReadLogFile($logFile, $questionData, $tutorData, $commentData);
?>
<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
		<title>ESE Evaluation Analyse</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<div class="container">
			<?php
				CreateHeadline("ESE Evaluation - Data Analysis");
				// if there was any question Data in the log file, display it 
            
				if (count($questionData) > 0)
				{
					CreateSectionHeader("Question Evaluation");
					CreateLegend();
					foreach ($questionData as $question)
					{
						CreateRowHeader();
						echo "  <div class=\"col-sm-8\">\n";
						// The question itself
						echo "    <p class=\"lead\"><span>" . $question[0];
						//average
            			echo "</span><span style=\"float:right;\">⌀" . 
            				round(
            					($question[1]+2*$question[2]+3*$question[3]+4*$question[4]+5*$question[5])
            					/(array_sum(array_slice($question,1,5))), 
            					2)
            	 			. "</span>\n";
            	 		echo "</p>\n";
						echo "  </div>\n";
						echo "	<div class=\"col-sm-4\">\n";
						echo "    <div class=\"row\">\n";
            
            $width = 800;
            $height = 300;
            
            //find max of answers to set max of x-axis, max of y-axis is always seven, because there are six possibilities to answer
            $values = $question;
            array_shift($values);
            $maxX = max($values)+1;
            $maxY = 7;
            
            $img = CreateImage($width, $height);
 
						// the amount of answers for the different options and a nice group of bars
						for ($i = 1; $i < 7; $i++)
            {
							echo "      <div class=\"col-2\"><p class=\"lead center\">" . $question[$i] . "</p></div>\n";
              $color = ImageColorAllocate($img, 0, 150*($question[$i]/$maxX) + 50, 0);
              DrawBar($img, $question[$i], $maxX, $i+1, $maxY,  $question[$i], $color);
            }
            
            //finish image and save it
            $caption = array("N/A","--","-", "0", "+", "++");
            DrawCoords($img, $maxX, $maxY, $caption);
            
            $file=str_replace("?", "", str_replace(" ", "", $question[0]));
            
            ImagePNG($img,"question".$file.".png");
            ImageDestroy($img);
            
						echo "    </div>\n";
						echo "  </div>\n";
            echo "  <div class=\"col-sm-3\">\n";
            echo "  </div>\n";
            echo "  <div class=\"col-sm-9\">\n";
            echo "    <img src=\"question".$file.".png\" class=\"lead center\">";
            echo "  </div>\n";
						echo "</div>\n"; 
					}
				}
				// if there was any tutor Data in the log file, display it
				if (count($tutorData) > 0)
				{
					CreateSectionHeader("Tutor Evaluation");
					CreateLegend();
					foreach ($tutorData as $tutorName => $tutor)
					{
						CreateRowHeader();
						echo "  <div class=\"col-sm-8\">\n";
						// the name of the tutor
						echo "    <p class=\"lead\"><span>" . $tutorName . "</span>\n";
						
						//average
            			echo "<span style=\"float:right;\">⌀" . 
            				round(
            					($tutor[0]+2*$tutor[1]+3*$tutor[2]+4*$tutor[3]+5*$tutor[4])
            					/(array_sum($tutor)-$tutor[5]), 2)
            	 			. "</span>\n";
            	 		echo "</p>\n";						
						
						echo "  </div>\n";
						echo "	<div class=\"col-sm-4\">\n";
						echo "    <div class=\"row\">\n";
            
            $width = 800;
            $height = 300;
            
            //find max of answers to set max of x-axis, max of y-axis is always seven, because there are six possibilities to answer
            $maxX = max($tutor)+1;
            $maxY = 7;
            
            $img = CreateImage($width, $height);
            
						// the amount of answers for the different options and a picture 
						for ($i = 0; $i < 6; $i++)
            {
							echo "      <div class=\"col-2\"><p class=\"lead center\">" . $tutor[$i] . "</p></div>\n";
              $color = ImageColorAllocate($img, 0, 150*($tutor[$i]/$maxX) + 50, 0);
              DrawBar($img, $tutor[$i], $maxX,  $i+2, $maxY,  $tutor[$i], $color);
            }
            
            //finish image and save it
            $caption = array("N/A","--","-", "0", "+", "++");
            DrawCoords($img, $maxX, $maxY, $caption);
            
            $file = str_replace(" ", "", $tutorName);
            ImagePNG($img,"tutor".$file.".png");
            ImageDestroy($img);
            
						echo "    </div>\n";
						echo "  </div>\n";
            echo "  <div class=\"col-sm-3\">\n";
            echo "  </div>\n";
            echo "  <div class=\"col-sm-9\">\n";
            echo "    <img src=\"tutor".$file.".png\" class=\"lead center\">";
            echo "  </div>\n";
						echo "</div>\n";
            
					}
				}
				// if there was any comment Data in the log file, display it
				if (count($commentData) > 0)
				{
					CreateSectionHeader("Comments");
					foreach ($commentData as $comment)
						// replace all new lines with html breaks to properly display multi-line comments
						CreateParagraph(str_replace("\n", "<br/>\n", $comment));
				}
			?>
		</div>
	</body>
</html>