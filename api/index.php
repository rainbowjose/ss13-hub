<?php
    define("INDEX", "yes");
    include '../core/class.core.php';
    $core = new Core();
    $core->loc('api');
	include '../core/class.tg.rounds.php';
	$ro = new Rounds();
	include '../core/class.tg.bans.php';
	$ba = new Bans();
	include '../core/class.tg.library.php';
	$li = new Library();
	include '../core/class.tg.deaths.php';
	$de = new Deaths();
?>
<?php
header('Content-Type: application/json');

if (isset($_GET['data'])) {
	switch($_GET['data']) {
		case 'rounds':
			$ro->roundsQuery('json', 1);
			break;
		case 'rounds_fastload':
			$_GET['page'] = $_GET['offset'];
			$ro->roundsQuery('print', 50);
			break;
		case 'rounds_tp':
			echo $ro->roundsQuery('tp');
			break;
		case 'deaths':
			$de->deathsQuery('json', 1);
			break;
		case 'books':
			$li->libraryQuery('json', 1);
			break;
		case 'bans':
			$ba->bansQuery('json', 1);
			break;
		case 'admins':
			$core->adminsQuery();
			break;
		case 'lw':
			$lw = $de->lastwords();
			echo '{"lastword": "'.iconv("windows-1251", "utf-8", $lw['last_words']).'"}';
			break;
		case 'stats':
			if (!isset($_GET['round']) or $_GET['round'] === '') {
				echo '{"error": "Usage: /api/?data=stats&round=ID&stat=NAME"}';
				break;
			}
			if (is_numeric($_GET['round'])) {
				$r = $_GET['round'];
				if (!isset($_GET['stat']) or $_GET['stat'] === '') {
					$s = 'all';
				} else {
					$s = $_GET['stat'];
				}
				echo json_encode ($ro->statQueryJson($r, $s));
			} else {
				echo '{"error": "Wrong round"}';
				break;
			}
			break;
		default:
			echo '{"error": "Wrong data"}';
			break;
	}
} else {
	echo '{"error": "Wrong data, try to use /api/?data=admins or something."}';
}
?>
