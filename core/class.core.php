<?php

if(!defined("INDEX")) die("ACCESS DENIED");

include 'class.db.php';
include 'class.config.php';


class Core {

	function __construct() {
		$this->db = new SafeMySQL();
	}

	function loc($loc) {
		switch ($loc) {
			case 'rounds':
				include 'class.tg.rounds.php';
				$this->ro = new Rounds();
				break;
			case 'bans':
				include 'class.tg.bans.php';
				$this->ba = new Bans();
				break;
			case 'library':
				include 'class.tg.library.php';
				$this->li = new Library();
				break;
			case 'deaths':
				include 'class.tg.deaths.php';
				$this->de = new Deaths();
				break;
			case 'dcp':
				include 'class.cp.php';
				include 'class.tg.library.php';
				$this->li = new Library();
				break;
			case 'api':
				break;
			default:
				$this->loc = 'Unknown';
		}
		$this->loc = $loc;
	}

	public function lw() {
		$res_data = $this->db->query("select id, name, last_words from death where not last_words = '' order by id DESC LIMIT 1");
		$lw = $this->db->fetch($res_data);
		return $lw;
	}

	//Объявление стилей

	public function header() {
		echo '
	<title>'.HUB()['hub_name'].' - '.ucfirst($this->loc).'</title>
	<meta property="og:title" content="Frosty HUB" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="/styles/n.png" />
	<meta property="og:image:secure_url" content="/styles/n.png" />
	<meta property="og:description" content="';
	$lw = $this->lw();
	echo ''.$lw['name'].' whispers in his last breath, «'.iconv("windows-1251", "utf-8", $lw['last_words']).'»';
	echo '" />
	<script src="/styles/jquery.min.js"></script>
	<link rel="stylesheet" href="/styles/main.css?v=1.8">
	<link href="https://fonts.googleapis.com/css?family=Caveat|Kelly+Slab|Yanone+Kaffeesatz|Play|Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
	<link rel="stylesheet" href="/styles/experimental.bg.css" />';
		if  (isset($_COOKIE["cs"])) {
			switch  ($_COOKIE["cs"]) {
				case 'orange':
					echo '<link href="/styles/orange.css" rel="stylesheet" id="theme" />';
					break;
				case 'white':
					echo '<link href="/styles/white.css"  rel="stylesheet" id="theme"  />';
					break;
				case 'black':
					echo '<link href="/styles/black.css"  rel="stylesheet" id="theme"  />';
					break;
				case 'icy':
					echo '<link href="/styles/icy.css"    rel="stylesheet" id="theme"  />';
					break;
			}
		} else {
			setcookie("cs", HUB()['hub_theme']);
			echo '<link href="/styles/orange.css" rel="stylesheet" id="theme" />';
		}
	}

	public function navbar() {
		echo '
	<div id="tb">T S</div>
	<div class="gbpls">
		<div id="stars"></div>
    	<div id="stars2"></div>
    	<div id="stars3"></div>
	</div>
	<div class="navbar">
		<div class="navbar-in">
			<a class="navbar-brand" href="/">Frosty HUB</a>
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="/rounds/">
					<i class="fas fa-dice"></i> Раунды
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/deaths/">
					<i class="fas fa-skull"></i> Смерти
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/bans/">
					<i class="fas fa-ban"></i> Баны
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/library/">
					<i class="fas fa-book"></i> Библиотека
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/map/" target="_blank">
					<i class="fas fa-map"></i> Карта
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/online/">
					<i class="fas fa-chart-area"></i> Онлайн
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/play/">
					<i class="fas fa-wheelchair"></i> Играть
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://github.com/daxsc/YTgstation/" target="_blank">
					<i class="fab fa-github"></i> GitHub
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://discord.gg/5aXdgXv" target="_blank">
					<i class="fab fa-discord"></i> Discord
					</a>
				</li>
			</ul>
		</div>
	</div>';
	}

	//Объявление скриптов

	public function footer() {
		echo '<div class="footjob"><hr><center><small>ALPHA_STATE_VERSION_1.00 | LOCAL TIME: '.date('d-m-Y h:i:s A').'</small></center><hr></div>';
		echo '
	<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter48222425 = new Ya.Metrika2({ id:48222425, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/48222425" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
	<script src="/styles/tippy.all.min.js"></script>
	<script src="/styles/jquery.tablesorter.min.js"></script>
	<script src="/styles/jquery.timeago.js"></script>
	<script src="/styles/js.cookie.min.js"></script>
	<script src="/styles/functs.js?v=1.3"></script>
	';
	}

	//обработчик админов

	public function adminsQuery() {

		$res_data = $this->db->query("SELECT * FROM admin ORDER BY `rank`");
		while($row = $this->db->fetch($res_data)) {
			$a[] = $row;
		}
		print json_encode($a);
		return;
	}

	//обработчик онлайна

	public function timeonline() {
		$online = $this->db->query("SELECT * FROM legacy_population ORDER BY id DESC LIMIT 800");
		while($row = $this->db->fetch($online)) {
			$date = date_format(date_create($row['time']), 'Y, m, d, H, i, s');
			echo "{ x: new Date(Date.UTC (".$date.")), y: ".$row['playercount']."}, ";
		}
	}

	public function adminsonline() {
		$online = $this->db->query("SELECT * FROM legacy_population ORDER BY id DESC LIMIT 800");
		while($row = $this->db->fetch($online)) {
			if ($row['admincount'] == NULL) { $row['admincount'] = 0; };
			$date = date_format(date_create($row['time']), 'Y, m, d, H, i, s');
			echo "{ x: new Date(Date.UTC (".$date.")), y: ".$row['admincount']."}, ";
		}
	}

	public function ctheming() {
		if  (isset($_COOKIE["cs"])) {
			switch  ($_COOKIE["cs"]) {
				case 'orange':
					$c[1] = '#1a0a00';
					$c[2] = '#4d1f00';
					$c[3] = '#b34400';
					$c[4] = '#ff6600';
					$c[5] = '#ff944d';
					break;
				case 'white':
					$c[1] = '#ffffff';
					$c[2] = '#aaaaaa';
					$c[3] = '#000000';
					$c[4] = '#000000';
					$c[5] = '#000000';
					break;
				case 'black':
					$c[1] = '#222';
					$c[2] = '#222';
					$c[3] = '#ddd';
					$c[4] = '#aaa';
					$c[5] = '#aaa';
					break;
				case 'icy':
					$c[1] = '#001a1a';
					$c[2] = '#004d4d';
					$c[3] = '#80ffff';
					$c[4] = '#b3ffff';
					$c[5] = '#e6ffff';
					break;
			}
		}
		return $c;
	}

	public function ct($w) {
		if ($w == 1) {
			echo date("Y/m/d H:i:s");
		} else {
			echo date("Y/m/d H:i:s");
		}

	}
}
?>
