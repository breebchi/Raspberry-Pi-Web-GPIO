<?php
include 'set_config_vars.php';

// Get params for update.
$updateConfig		= isset($_GET['updateConfig']) && ($_GET['updateConfig']!= 'undefined') 	? $_GET['updateConfig'] 	: 0;
$debugModeTemp			= isset($_GET['debugMode']) && ($_GET['debugMode']!= 'undefined') 	? $_GET['debugMode'] 	: 0;
$showDisabledPinsTemp 	= isset($_GET['showDisabledPins'])  	&& ($_GET['showDisabledPins']!= 'undefined') 		? $_GET['showDisabledPins'] 		: 0;

// Set up state icons.
$on =  'images/checkbox_checked_icon.png';
$off = 'images/checkbox_unchecked_icon.png';

// Update config fields as (if) needed.
$query_update = "";

try {
	if ($updateConfig>0) {
		$query_update = 'UPDATE config SET debugMode = :debugMode, showDisabledPins = :disabledPins WHERE configVersion = 1';
		$qry_result = $db->prepare($query_update);
		$qry_result->execute(array(':debugMode'=>$debugModeTemp, ':disabledPins'=>$showDisabledPinsTemp));
	}

	// Select the only row.
	$query = 'SELECT * FROM config WHERE configVersion = 1';

	$qry_result = $db->prepare($query);
	$qry_result->execute();

	// Config table has only ONE row to return.
	$row = $qry_result->fetch(PDO::FETCH_ASSOC);

	$debugMode = $row['debugMode'];
	$showDisabledPins = $row['showDisabledPins'];

	// Build Result String.
	$display_string = "		<table>\r\n";

	// Debug Mode.
	$display_string .= "			<tr>\r\n";
	$display_string .= "				<td>Enable Debug Mode</td>\r\n";
	$display_string .= "				<td><a href=\"#\" onclick=\"showConfig(1," . ($debugMode == 1 ? '0':'1') . "," . $showDisabledPins . ")\">";

	switch ($debugMode){
		case 1 :
			$display_string .= "<img src=\"$on\" />";
			break;
		case 0 :
			$display_string .= "<img src=\"$off\" />";
			break;
	}
	$display_string .= "</a></td>\r\n";
	$display_string .= "			</tr>\r\n";

	// Show Disabled Pins.
	$display_string .= "			<tr>\r\n";
	$display_string .= "				<td>Show Disabled Pins</a></td>\r\n";
	$display_string .= "				<td><a href=\"#\" onclick=\"showConfig(1," . $debugMode . "," . ($showDisabledPins == 1 ? '0':'1') . ")\">";

	switch ($showDisabledPins){
		case 1 :
			$display_string .= "<img src=\"$on\" />";
			break;
		case 0 :
			$display_string .= "<img src=\"$off\" />";
			break;
	}
	$display_string .= "</a></td>\r\n";
	$display_string .= "			</tr>\r\n";

	// Close table.
	$display_string .= "		</table>\r\n";

	// Display it.
	print $display_string;

	if ($debugMode) {
		//debug output
		print '<pre>Query params: ' . $updateConfig . ' ' . $debugModeTemp . ' ' . $showDisabledPinsTemp . '</pre>';
		print '<pre>DB    params: ' . $debugMode . ' ' . $showDisabledPins. '</pre>';
		print '<pre>' . $query_update . '</pre>';
		print '<pre>' . $query . '</pre>';
	}
} catch(Exception $e) {
	echo 'Exception -> ';
	var_dump($e->getMessage());
}
?>