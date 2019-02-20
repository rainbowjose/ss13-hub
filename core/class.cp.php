<?php

if(!defined("INDEX")) die("ACCESS DENIED");

class Cpi extends Core {

	function __construct() {
		if (!$this->check_auth()) {
			die('NOT AUTHORIZED');
		}

		if (isset($_GET['json'])) {
			switch ($_GET['json']) {
				case 'ss':
					echo implode('<br>', $this->scmd('status'));
					break;
				case 'cs':
					echo implode('<br>', $this->scmd('cstatus'));
					break;
				case 'dd':
					echo implode('<br>', $this->scmd('dlog'));
					break;
				case 'cl':
					echo implode('<br>', $this->scmd('clog'));
					break;
				case 'ul':
					echo implode('<br>', $this->scmd('ulog'));
					break;
				case 'dcl':
					$this->plog();
					break;
				case 'compile':
					$this->scmd('compile');
					$this->log("Compile");
					break;
				case 'update':
					$this->scmd('update');
					$this->log("Update");
					break;
				case 'reload':
					$this->scmd('reload');
					$this->log("Reload");
					break;
				case 'start':
					$this->scmd('start');
					$this->log("Start");
					break;
				case 'stop':
					$this->scmd('stop');
					$this->log("Stop");
					break;
				default:
					echo 'NULL';
					break;
			}
			die();
		}
		$this->dcpa();
	}

	private function check_auth($l = FALSE) {
		$accounts = [["login" => "admin", 			 "hash" => "admin"],
					 ["login" => "user",  			 "hash" => "password"]];

		if ($l) {
			foreach ($accounts as $key => $val) {
				if ($accounts[$key]['hash'] == $_COOKIE["dcpat"]) {
					$login = $accounts[$key]['login'];
					break;
				}
			}
			return $login;
		}

		if (isset($_GET['key'])) {
			$h = $_GET['key'];
			foreach ($accounts as $key => $val) {
				if ($accounts[$key]['hash'] == $h) {
					setcookie("dcpat", $h);
					header('Location: /dcp/index.php');
					die('Redirecting...');
					break;
				}
			}
		}

		if (isset($_COOKIE["dcpat"])) {
			$cock = $_COOKIE["dcpat"];
			foreach ($accounts as $key => $val) {
				if ($accounts[$key]['hash'] == $cock) {
					$a = TRUE;
					break;
				} else {
					$a = FALSE;
				}
			}
		} else {
			$a = FALSE;
		}
		return $a;
	}

	function dcpa() {
		if (isset($_GET['p'])){
			switch ($_GET['p']) {
				case 'config':
					$this->wconfig();
					return 'config';
					break;
				case 'wordconfig':
					$this->bconfig();
					return 'wordconfig';
					break;
				case 'bookconfig':
					$this->bookmanager();
					return 'bookconfig';
					break;
				case 'log':
					return 'log';
					break;
				default:
					header('Location: /dcp/?p=log');
					return 'log';
					break;
			}
		} else {
			header('Location: /dcp/?p=log');
			return 'log';
		}
	}

	function wconfig() {
		if (isset($_POST['config'])) {
			$this->sconfig('config.txt', $_POST['config']);
			$this->log("Edited config.txt");
		}
		if (isset($_POST['game_options'])) {
			$this->sconfig('game_options.txt', $_POST['game_options']);
			$this->log("Edited game_options.txt");
		}
		if (isset($_POST['lavaruinblacklist'])) {
			$this->sconfig('lavaruinblacklist.txt', $_POST['lavaruinblacklist']);
			$this->log("Edited lavaruinblacklist.txt");
		}
		if (isset($_POST['maps'])) {
			$this->sconfig('maps.txt', $_POST['maps']);
			$this->log("Edited maps.txt");
		}
		if (isset($_POST['motd'])) {
			$this->sconfig('motd.txt', $_POST['motd']);
			$this->log("Edited motd.txt");
		}
		if (isset($_POST['admins'])) {
			$this->sconfig('admins.txt', $_POST['admins']);
			$this->log("Edited config.txt");
		}
		if (isset($_POST['admin_ranks'])) {
			$this->sconfig('admin_ranks.txt', $_POST['admin_ranks']);
			$this->log("Edited admin_ranks.txt");
		}
	}

	function bconfig() {
		if (isset($_POST['bad_words'])) {
			$this->sbconfig('bad_words.fackuobema', $_POST['bad_words']);
			$this->log("Edited bad_words.fackuobema");
		}
		if (isset($_POST['debix_list'])) {
			$this->sbconfig('debix_list.fackuobema', $_POST['debix_list']);
			$this->log("Edited debix_list.fackuobema");
		}
		if (isset($_POST['exc_end'])) {
			$this->sbconfig('exc_end.fackuobema', $_POST['exc_end']);
			$this->log("Edited exc_end.fackuobema");
		}
		if (isset($_POST['exc_full'])) {
			$this->sbconfig('exc_full.fackuobema', $_POST['exc_full']);
			$this->log("Edited exc_full.fackuobema");
		}
		if (isset($_POST['exc_start'])) {
			$this->sbconfig('exc_start.fackuobema', $_POST['exc_start']);
			$this->log("Edited exc_start.fackuobema");
		}
	}

	function bookmanager() {
		if (isset($_GET['remove'])) {
			$id = $_GET['remove'];
			$this->db = new SafeMySQL();
			$this->db->query("UPDATE library SET deleted = '1' WHERE id=?i", $id);
			$this->log("Removed Book ID: ".$id);
		}
		if (isset($_GET['restore'])) {
			$id = $_GET['restore'];
			$this->db = new SafeMySQL();
			$this->db->query("UPDATE library SET deleted = '0' WHERE id=?i", $id);
			$this->log("Restored Book ID: ".$id);
		}
	}

	function gconfig($c) {
		echo iconv("windows-1251", "utf-8", file_get_contents('/home/traitor/YTgstation/config/'.$c));
	}

	function sconfig($c, $d) {
		$d = iconv("utf-8", "windows-1251", $d);
		file_put_contents('/home/traitor/YTgstation/config/'.$c, $d);
	}

	function gbconfig($c) {
		echo iconv("windows-1251", "utf-8", file_get_contents('/home/traitor/YTgstation/config/autoeban/'.$c));
	}

	function sbconfig($c, $d) {
		$d = iconv("utf-8", "windows-1251", $d);
		file_put_contents('/home/traitor/YTgstation/config/autoeban/'.$c, $d);
	}

	function log($dt) {
		$who = $this->check_auth(TRUE);
		$fp = fopen("dcp.log", "a");
		$dt = "[".date('Y-m-d H:i:s')."] [".$who."]: ".$dt."\r\n";
		fwrite($fp, $dt);
		fclose($fp);
	}

	function plog() {
		$fp = fopen("dcp.log", "r");
		if ($fp) {
			while (!feof($fp)) {
				$l = fgets($fp, 999);
				echo $l."<br />";
			}
		}
		fclose($fp);
	}

	function scmd($cmd) {
		exec('sudo -H -u traitor sh -c "cd /home/traitor/ && sh starter.sh '.$cmd.'" 2>&1', $o);
		return $o;
	}
}
?>
