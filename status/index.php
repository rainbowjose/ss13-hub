<?php
// it works!

if (isset($_GET['server'])) {
	switch ($_GET['server']) {
		case 'yellow':
			$servers[0] = Array();
			$servers[0]["address"] = "185.255.178.35";
			$servers[0]["port"] = 2019;
			$servers[0]["servername"] = "Yellow";
			break;
		case 'fallout':
			$servers[0] = Array();
			$servers[0]["address"] = "185.255.178.35";
			$servers[0]["port"] = 1337;
			$servers[0]["servername"] = "Fallout";
			break;
		case 'vgstation':
			$servers[0] = Array();
			$servers[0]["address"] = "185.255.178.35";
			$servers[0]["port"] = 2025;
			$servers[0]["servername"] = "vgstation";
			break;
		default:
			die('Nothing for you.');
			break;
	}
} else {
	die('Nothing for you.');
}

function export($addr, $port, $str) {

	if (!@fsockopen($addr,$port,$errno,$errstr,1)) {
		draw_yellow_dead();
		die();
	}

	global $error;
	// All queries must begin with a question mark (ie "?players")
	if($str{0} != '?') $str = ('?' . $str);
	/* --- Prepare a packet to send to the server (based on a reverse-engineered packet structure) --- */
	$query = "\x00\x83" . pack('n', strlen($str) + 6) . "\x00\x00\x00\x00\x00" . $str . "\x00";
	/* --- Create a socket and connect it to the server --- */
	$server = socket_create(AF_INET,SOCK_STREAM,SOL_TCP) or exit("ERROR");
	socket_set_option($server, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 2, 'usec' => 0)); //sets connect and send timeout to 2 seconds

	if(!socket_connect($server,$addr,$port)) {
		$error = true;
		return "ERROR";
	}
	/* --- Send bytes to the server. Loop until all bytes have been sent --- */
	$bytestosend = strlen($query);
	$bytessent = 0;
	while ($bytessent < $bytestosend) {
		//echo $bytessent.'<br>';
		$result = socket_write($server,substr($query,$bytessent),$bytestosend-$bytessent);
		//echo 'Sent '.$result.' bytes<br>';
		if ($result===FALSE) die(socket_strerror(socket_last_error()));
		$bytessent += $result;
	}
	/* --- Idle for a while until recieved bytes from game server --- */
	$result = socket_read($server, 10000, PHP_BINARY_READ);
	socket_close($server); // we don't need this anymore
	if($result != "") {
		if($result{0} == "\x00" || $result{1} == "\x83") { // make sure it's the right packet format
			// Actually begin reading the output:
			$sizebytes = unpack('n', $result{2} . $result{3}); // array size of the type identifier and content
			$size = $sizebytes[1] - 1; // size of the string/floating-point (minus the size of the identifier byte)
			if($result{4} == "\x2a") { // 4-byte big-endian floating-point
				$unpackint = unpack('f', $result{5} . $result{6} . $result{7} . $result{8}); // 4 possible bytes: add them up together, unpack them as a floating-point
				return $unpackint[1];
			}
			else if($result{4} == "\x06") { // ASCII string
				$unpackstr = ""; // result string
				$index = 5; // string index
				while($size > 0) { // loop through the entire ASCII string
					$size--;
					$unpackstr .= $result{$index}; // add the string position to return string
					$index++;
				}
				return $unpackstr;
			}
		}
	}
	//if we get to this point, something went wrong;
	$error = true;
	return "ERROR";
}

function getvar($array,$var) {
	if (array_key_exists($var, $array))
		return $array[$var];
	return null;
}

foreach ($servers as $server) {
	$port = $server["port"];
	$addr = $server["address"];
	$data = export($addr, $port, '?status');
	if(is_string($data)) {
		//remove pesky null-terminating bytes
		$data = str_replace("\x00", "", $data);
	}
	$variable_value_array = Array();
	if ((!$data || strpos($data, "ERROR") !== false) && (array_key_exists("restarting", $lastinfo))) {
		$variable_value_array['restarting'] = $lastinfo['restarting'] + 1;
	}
	// Split the retrieved data into easily-accessible arrays
	$data_array = explode("&", $data);
	for($i = 0; $i < count($data_array); $i++) {
		//Split the row by the = sign into the identifier at index 0 and the value at index 1 (if the value exists)
		$row = explode("=", $data_array[$i]);
		if(isset($row[1])){
			//All should go here... but just in case.
			$variable_value_array[$row[0]] = $row[1];
		}else{
			$variable_value_array[$row[0]] = null;
		}
	}
	$variable_value_array['cachetime'] = time();
	if (array_key_exists('gamestate', $variable_value_array))
		if ($variable_value_array['gamestate'] == 4)
			$variable_value_array['restarting'] = 1;
	$serverinfo = $variable_value_array;

	if (isset($_GET['key']) && $_GET['key'] == 'zxcvbnasd') {
		include '../api/db.php';
		$db = new SafeMySQL();
		$sname = $server["servername"];
		$time = date("Y-m-d H:i:s");
		$online = $serverinfo['players'];
		$admins = $serverinfo['admins'];
		$db->query("INSERT INTO online (online, time, server, admins) VALUES ('$online', '$time', '$sname', '$admins')");
		die('Your IP recorded.');
	} elseif (isset($_GET['key']) && $_GET['key'] == 'json') {
		echo json_encode($serverinfo);
	} else {
		header("Content-type: image/png");
		switch ($server["servername"]) {
			case "Yellow":
				draw_yellow($serverinfo);
				break;
			case "Fallout":
				draw_fallout($serverinfo);
				break;
			case "vgstation":
				draw_vg($serverinfo);
				break;
			}
	}
}

function draw_yellow($serverinfo) {
	if ($_COOKIE["cs"] == 'icy') {
		$canvas = 		imagecreatefrompng ('yellow_icy.png');
		$orange =		imagecolorallocate ($canvas, 128, 255, 255);
	} else {
		$canvas = 		imagecreatefrompng ('yellow.png');
		$orange =		imagecolorallocate ($canvas, 255, 183, 122);
	}
	$background = 	imagecolorallocate ($canvas, 0, 0, 0);
	$black = 		imagecolorallocate ($canvas, 0, 0, 0);
	$red =   		imagecolorallocate ($canvas, 255, 0, 0);
	$green = 		imagecolorallocate ($canvas, 0, 255, 0);
	$white = 		imagecolorallocate ($canvas, 255, 255, 255);

	imagestring ($canvas, 5, 10, 4, 'Yellow', $orange);
	imagestring ($canvas, 5, 252, 4, $serverinfo['players'].'/60', $orange);
	imagestring ($canvas, 4, 112, 4, str_replace("+", " ", $serverinfo['map_name']), $orange);
	imagestring ($canvas, 4, 16, 30, $serverinfo['mode'], $orange);
	imagestring ($canvas, 4, 224, 30, gmdate("H:i:s", $serverinfo['round_duration']), $orange);

	imagepng ($canvas);
	imagedestroy ($canvas);
}

function draw_yellow_dead() {
	if ($_COOKIE["cs"] == 'icy') {
		$canvas = 		imagecreatefrompng ('yellow_dead.png');
		$orange =		imagecolorallocate ($canvas, 64, 125, 125);
	} else {
		$canvas = 		imagecreatefrompng ('yellow_dead2.png');
		$orange =		imagecolorallocate ($canvas, 125, 96, 62);
	}

	imagestring ($canvas, 5, 8, 4, 'Server', $orange);

	imagepng ($canvas);
	imagedestroy ($canvas);
}

function draw_fallout($serverinfo) {
	if ($_COOKIE["cs"] == 'icy') {
		$canvas = 		imagecreatefrompng ('yellow_icy.png');
		$orange =		imagecolorallocate ($canvas, 128, 255, 255);
	} else {
		$canvas = 		imagecreatefrompng ('yellow.png');
		$orange =		imagecolorallocate ($canvas, 255, 183, 122);
	}
	$background = 	imagecolorallocate ($canvas, 0, 0, 0);
	$black = 		imagecolorallocate ($canvas, 0, 0, 0);
	$red =   		imagecolorallocate ($canvas, 255, 0, 0);
	$green = 		imagecolorallocate ($canvas, 0, 255, 0);
	$white = 		imagecolorallocate ($canvas, 255, 255, 255);

	imagestring ($canvas, 5, 8, 4, 'Fallout', $orange);
	imagestring ($canvas, 5, 252, 4, $serverinfo['players'].'/60', $orange);
	imagestring ($canvas, 4, 93, 4, str_replace("+", " ", $serverinfo['map_name']), $orange);
	imagestring ($canvas, 4, 16, 30, $serverinfo['mode'], $orange);
	imagestring ($canvas, 4, 224, 30, gmdate("H:i:s", $serverinfo['round_duration']), $orange);

	imagepng ($canvas);
	imagedestroy ($canvas);
}

function draw_vg($serverinfo) {
	if ($_COOKIE["cs"] == 'icy') {
		$canvas = 		imagecreatefrompng ('yellow_icy.png');
		$orange =		imagecolorallocate ($canvas, 128, 255, 255);
	} else {
		$canvas = 		imagecreatefrompng ('yellow.png');
		$orange =		imagecolorallocate ($canvas, 255, 183, 122);
	}
	$background = 	imagecolorallocate ($canvas, 0, 0, 0);
	$black = 		imagecolorallocate ($canvas, 0, 0, 0);
	$red =   		imagecolorallocate ($canvas, 255, 0, 0);
	$green = 		imagecolorallocate ($canvas, 0, 255, 0);
	$white = 		imagecolorallocate ($canvas, 255, 255, 255);

	imagestring ($canvas, 3, 8, 4, 'vgstation', $orange);
	imagestring ($canvas, 5, 252, 4, $serverinfo['players'].'/60', $orange);
	imagestring ($canvas, 4, 112, 4, str_replace("+", " ", $serverinfo['map_name']), $orange);
	imagestring ($canvas, 4, 16, 30, $serverinfo['mode'], $orange);
	//imagestring ($canvas, 4, 224, 30, gmdate("H:i:s", $serverinfo['cachetime']), $orange);

	imagepng ($canvas);
	imagedestroy ($canvas);
}
?>
