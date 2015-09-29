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
		array("Edsger W. Dijkstra", "APB/E005", "09:00"),
		array("Kurt Gödel", "APB/E006", "09:10"),
		array("Konrad Zuse", "APB/E007", "09:20"),
		array("Donald E. Knuth", "APB/E010", "09:30"),
		array("John von Neumann", "APB/E009", "09:40"),
		array("Tim Berners-Lee", "APB/E008", "09:50"),
		array("Alan Turing", "SCH/A214", "10:00"),
		array("Ada Lovelace", "SCH/A252", "10:10"),
		array("Grace Hopper", "SCH/A185", "10:20"),
		array("Richard M. Stallman", "SCH/A184", "10:30"),
		array("Linus Torvalds", "SCH/A419", "10:40"),
		array("Noam Chomsky", "MER/03", "10:50"),
		array("Christiane Floyd", "MER/01", "11:00"),
		array("Stephen A. Cook", "GER/39", "11:10"),
		array("Ken Thompson", "GER/09", "11:20"),
		array("Marc Andreesen", "GER/54", "11:30"),
		// we need slightly more Master tickets
		array("Master Inf/MInf", "APB/E023", ""),
		array("Master Inf/MInf", "APB/E023", "")
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
		?>
			<div class="ticket">
				<div class="patron"><?php echo $patrons[$pno][0] ?></div>
				<div class="tickettext">
					<div class="ticketimg"><img src="css/ese-logo.png"/></div>
					<div class="room">Tutorium in <br><strong><?php echo $patrons[$pno][1] ?></strong></div>
					<div class="time">
						<?php
							// don't display start date if there is none (Master case)
							echo ($patrons[$pno][2]=="") ? "&nbsp;" : "Einschreibestart Dienstag <strong>".$patrons[$pno][2]." Uhr</strong>";
						?>
					</div>
				</div>
			</div>
		<?php
	}
?>
		</div>
	</body>
</html>
