<?php
//============================================================================
// Name        : patronGen.php
// Author      : Patrick Reipschläger, Dirk Legler
// Version     : 1.0
// Date        : 10-2013
// Description : For generating the group name tickets.
//============================================================================
	include_once 'libs/keyLib.php';
	include_once 'libs/formLib.php';

	// start a session prevent sending the same post twice
	// if the user refreshes the page, it will default to the
	// access code screen
	//session_start();

	// the key file which should be edited, either the default key file or the one that was specified
	$keyFile = KEYFILE;
	if (isset($_GET["keyFile"]))
		$keyFile = $_GET["keyFile"];
	// holds the key data, generated from the key file
	$keyData = ReadKeyFile($keyFile);
	// the group names and rooms for Monday and their start times for Tuesday
	$patrons = array(
		array("Edsger W. Dijkstra", "INF/E005", "09:00"),
		array("Kurt Gödel", "INF/E006", "09:10"),
		array("Konrad Zuse", "INF/E007", "09:20"),
		array("Donald E. Knuth", "INF/E008", "09:30"),
		array("John von Neumann", "INF/E009", "09:40"),
		array("Tim Berners-Lee", "INF/E010", "09:50"),
		array("Alan Turing", "SCH/A216", "10:00"),
		array("Ada Lovelace", "BAR/106", "10:10"),
		array("Grace Hopper", "JAN/27", "10:20"),
		array("Richard M. Stallman", "BER/105", "10:30"),
		array("Linus Torvalds", "SCH/A01", "10:40"),
		array("Noam Chomsky", "SCH/A315", "10:50"),
		array("Christiane Floyd", "SCH/A215", "11:00"),
		array("Stephen A. Cook", "INF/2101", "11:10"),
		array("Ken Thompson", "SCH/A118", "11:20"),
		array("Marc Andreesen", "SCH/A316", "11:30"),
		// we need slightly more Master tickets
		array("Master Inf/MInf", "INF/E023", ""),
		array("Master Inf/MInf", "INF/E023", "")
	);

?>
<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
		<title>Namenspatronzettel</title>
		<link rel="stylesheet" type="text/css" href="css/print.css" />
	</head>
	<body>
		<div class="container">
<?php
for ($i = 0; $i < count($keyData); $i++)
	{
		$pno = $i%(count($patrons));
		echo "		<div class=\"ticket\"><div class=\"tickettext\">";
		echo "			<div class=\"patron\">".$patrons[$pno][0]."</div>";
		echo "			<div class=\"room\">Tutorium in <strong>".$patrons[$pno][1]."</strong></div>";
		echo "			<div class=\"time\">";
		// don't display start date if there is none
		echo ($patrons[$pno][2]=="")?"&nbsp;":"Einschreibestart Dienstag <strong>".$patrons[$pno][2]."</strong>";
		echo "</div>";
		echo "			<code>".$keyData[$i][0]."</code>";
		echo "		</div><div class=\"ticketimg\"><img src=\"css/ese-logo.png\"/></div>";
		echo "			<div class=\"evalink\">ESE-Evaluation unter https://ese.ifsr.de/2014/eva/</div>";
		echo "</div>";
	}
?>
		</div>
	</body>
</html>
