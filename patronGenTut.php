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
for ($i = 0; $i < count($keyData); $i++) {
	if($keyData[$i][1] == KEYSTATE_UNISSUED) {
		?>
			<div class="ticket">
				<div class="ticketimg"><img class="tutorticketimg" src="css/ese-logo.png"/></div>
				<div class="tickettext">
					<div class="patron">Tutor</div>
					<!-- <div class="room">&nbsp;</div> -->
					<!-- <div class="time">&nbsp;</div> -->
					<br />
					<code><?php echo substr($keyData[$i][0], 0, 10) . "<br />" .  substr($keyData[$i][0], 10, 19); ?></code>
				</div>
				<div class="evalink">Tutoren-Eva unter https://ese.ifsr.de/2015/eva/tut</div>
			</div>
		<?php
	}
}
?>
		</div>
	</body>
</html>
