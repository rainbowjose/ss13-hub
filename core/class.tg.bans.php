<?php

if(!defined("INDEX")) die("ACCESS DENIED");

class Bans extends Core {

	public function bansQuery($wh, $nrp = "100") {
		if (isset($_GET['page'])) {
				if (is_numeric($_GET['page'])) {
					$page = $_GET['page'];
					} else {
					$page = 1;
					}
				} else {
					$page = 1;
				}

		if ($page <= 0) {
			die('<center>HACKING DENIED</center>');
		}

		$offset = ($page-1) * $nrp;

		if ($offset <= -1) {
			return;
		}

		$total_rows = $this->db->getCol("SELECT COUNT(*) FROM ban")[0];
		$total_pages = ceil($total_rows / $nrp);
		if ($total_pages < $page) {
			die('<center>HACKING DENIED</center>');
		}

		if ((isset($_POST["ckey"])) && ($_POST["ckey"] != '' && (isset($_POST["a_ckey"])) && ($_POST["a_ckey"] != ''))) {
			$a_ckey = htmlspecialchars($_POST["a_ckey"]);
			$ckey = htmlspecialchars($_POST["ckey"]);
			$res_data = $this->db->query("SELECT * FROM ban WHERE a_ckey=?s AND ckey=?s ORDER BY id DESC LIMIT $offset, ?i",$a_ckey, $ckey, $nrp);
		} elseif ((isset($_POST["ckey"])) && ($_POST["ckey"] != '')) {
			$ckey = htmlspecialchars($_POST["ckey"]);
			$res_data = $this->db->query("SELECT * FROM ban WHERE ckey=?s ORDER BY id DESC LIMIT $offset, ?i",$ckey, $nrp);
		} elseif ((isset($_POST["a_ckey"])) && ($_POST["a_ckey"] != '')) {
			$a_ckey = htmlspecialchars($_POST["a_ckey"]);
			$res_data = $this->db->query("SELECT * FROM ban WHERE a_ckey=?s ORDER BY id DESC LIMIT $offset, ?i",$a_ckey, $nrp);
		} else {
			$res_data = $this->db->query("SELECT * FROM ban ORDER BY id DESC LIMIT $offset, ?i",$nrp);
		}

		if ($wh == 'print') {
			$lb = null;
			$r = '';
			while($row = $this->db->fetch($res_data)) {
				$rnd = $this->banProcess($row);
				if ($lb['duration'] !== $rnd['duration'] && $lb['reason'] !== $rnd['reason']) {
					$r .= "
					<tr class='newbansys'></tr>
					<tr class='table-".$rnd['col']."'>
						<td nowrap scope=row".$rnd['id']." rowspan='2' width='48' height='32'><a href='/rounds/".$rnd['round_id']."'><i class='far fa-circle'></i> ".$rnd['round_id']."</a></td>
						<td nowrap>".$rnd['ckey']."</td>
						<td nowrap>".$rnd['a_ckey']."</td>

						<td colspan='2' width='40'>".$rnd['duration']." m</td>
						<td rowspan='2' colspan='30' class='ban-reason'>".iconv("windows-1251", "utf-8", $rnd['reason'])."</td>
					</tr>

					<tr class='table-".$rnd['col']."'>
						<td nowrap colspan='1'><time title='".$rnd['bantime']."' datetime='".$rnd['bantime']."'>".$rnd['bantime']."</time></td>
						<td colspan='1' nowrap><time title='".$rnd['expiration_time']."' datetime='".$rnd['expiration_time']."'>".$rnd['expiration_time']."</time></td>
						<td style='font-size: 8pt; white-space:nowrap;'>".$rnd['role']."</td>
						<td>".$rnd['unbanned']."</td>
					</tr>
					";
					$sb = true;
				} else {
					if ($sb) {
						$r .= "<tr class='table-".$rnd['col']."'><td colspan='3'></td><td colspan='30' style='font-size: 8pt; padding: 0 4px 4px 4px;'>";
						$sb = false;
					}
					$r .= "
						<div class='rolemini'>".$rnd['role']." (".$rnd['duration']." m) ".$rnd['unbanned']."</div>
					";
				}
				$lb['duration'] = $rnd['duration'];
				$lb['reason'] 	= $rnd['reason'];
			}
			echo $r;

		} elseif ($wh == 'total') {
			return $total_pages;
		} elseif ($wh == 'page') {
			return $page;
		} elseif ($wh == 'json') {
			while($row = $this->db->fetch($res_data)) {
				$row['reason'] = iconv("windows-1251", "utf-8", $row['reason']);
				$rnd[] = $row;
			}
			print json_encode($rnd);
			return;
		} else {
			return;
		}
	}

	public function banProcess($bn) {

		$bn['col'] = '';

		$ub = date_create($bn['unbanned_datetime']);
		$ed = date_create($bn['expiration_time']);
		$bt = date_create($bn['bantime']);

		$bn['bantime'] = $bt->format('Y-m-d\TH:i:s\Z');

		$bn['reason'] = filter_var($bn['reason'], FILTER_SANITIZE_STRING);

		if ($bn['unbanned_datetime'] == NULL) {
			if ($ed < $ub) {
				$bn['unbanned'] = '<i style="color: green; float: right;" class="fas fa-fw fa-check-circle"></i>';
			} else {
				$bn['unbanned'] = '<i style="color: red; float: right;" class="fas fa-fw fa-times-circle"></i>';
			}
		} else {
			$bn['unbanned'] = '<i style="color: green; float: right;" class="fas fa-fw fa-check-circle"></i>';
		}

		if ($bn['expiration_time'] != NULL) {
			$bn['expiration_time'] = $ed->format('Y-m-d\TH:i:s\Z');
		} else {
			$bn['expiration_time'] = 'Никогда';
		}

		if ($bn['expiration_time'] != 'Никогда') {
			$rt = date_create('2050-01-01 00:00:00');
			if ($rt <= $ed) {
				$bn['duration'] = '2147483647 ';
				$bn['col'] = 'inverse';
				if ($bn['role'] != 'Server') {
					$bn['col'] = 'danger';
				}
			} else {
				$diff = $bt->diff($ed);
				$m = ($diff->days * 24 * 60) +
				           ($diff->h * 60) + $diff->i;
				$bn['duration'] = $m;
			}
		} else {
			$bn['duration'] = '2147483647 ';
			if ($bn['unbanned_datetime'] == NULL) {
				$bn['unbanned'] = '<i style="color: black; float: right;" class="fas fa-fw fa-infinity"></i> ';
				$bn['col'] = 'inverse';
				if ($bn['role'] != 'Server') {
					$bn['col'] = 'danger';
				}
			}
		}

		return $bn;
	}
}
?>
