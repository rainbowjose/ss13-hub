<?php

if(!defined("INDEX")) die("ACCESS DENIED");

class Deaths extends Core {

    public function deathsMain() {
        if (isset($_GET['page'])) {
            return 'page';
        } elseif (isset($_GET['id'])) {
            return 'id';
        } elseif (isset($_GET['round'])) {
            return 'page';
        } else {
            header("Location: /deaths/page/1");
            return;
        }
    }

    public function lastwords() {
        $res_data = $this->db->query("select id, name, last_words from death where not last_words = '' order by id DESC LIMIT 1");
        $lw = $this->db->fetch($res_data);
        return $lw;
    }

    public function deathsQuery($wh, $nrp = "50") {
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

        $total_rows = $this->db->getCol("SELECT COUNT(*) FROM death")[0];
        $total_pages = ceil($total_rows / $nrp);
        if ($total_pages < $page) {
            die('<center>HACKING DENIED</center>');
        }

        $r_end = $this->db->fetch($this->db->query("SELECT id FROM round ORDER BY id DESC LIMIT 1"));

        $r_state = $r_end['id'];

        if ((isset($_GET["round"]))) {
            $round = htmlspecialchars($_GET["round"]);
            $total_pages = 1;
            $res_data = $this->db->query("SELECT * FROM death WHERE NOT round_id='$r_state' AND round_id=?s ORDER BY id DESC LIMIT $offset, ?i", $round, $nrp);
        } else {
            $res_data = $this->db->query("SELECT * FROM death WHERE NOT round_id='$r_state' ORDER BY id DESC LIMIT $offset, ?i",$nrp);
        }

        if ($wh == 'print') {
            while($row = $this->db->fetch($res_data)) {
                $dp = $this->deathProcess($row);
                echo "
                <tr class='newbansys'></tr>
                <tr ".$dp['col'].">
                    <th scope=row".$dp['id']."><a href='/deaths/".$dp['id']."'><i class='fas fa-user-times'></i> ".$dp['id']."</a></th>
                    <td>".iconv("windows-1251", "utf-8", $dp['name'])." - ".iconv("windows-1251", "utf-8", $dp['job'])."<span class='role'>".$dp['special']."</span><br><span class='text-muted'>".$dp['byondkey']."</span></td>
                    <td nowrap>".$dp['mapname']." - ".iconv("windows-1251", "utf-8", $dp['pod'])." (".$dp['x_coord'].", ".$dp['y_coord'].", ".$dp['z_coord'].")<br><i class='text-muted'>".iconv("windows-1251", "utf-8", $dp['last_words'])."</i></td>
                    <td nowrap><time title='".$dp['tod']."' datetime='".$dp['tod']."'>".$dp['tod']."</time><br><a href='/rounds/".$dp['round_id']."'><i class='far fa-circle'></i> ".$dp['round_id']."</td>
                    <td nowrap align='middle'><span class='damage brute' title='Физический (BRUTE)'>".$dp['bruteloss']."</span><span class='damage fire' title='Ожоговый (FIRE)'>".$dp['fireloss']."</span><span class='damage oxy' title='Кислородный (OXYGEN)'>".$dp['oxyloss']."</span><span class='damage tox' title='Токсины (TOXIC)'>".$dp['toxloss']."</span><span class='damage brain' title='Мозговой (BRAIN)'>".$dp['brainloss']."</span><span class='damage clone' title='Генетический (CLONE)'>".$dp['cloneloss']."</span><span class='damage stamina' title='Выносливость (STAMINA)'>".$dp['staminaloss']."</span></td>
                </tr>";
            }
        } elseif ($wh == 'total') {
            return $total_pages;
        } elseif ($wh == 'page') {
            return $page;
        } elseif ($wh == 'json') {
            while($row = $this->db->fetch($res_data)) {
                $row['last_words'] = iconv("windows-1251", "utf-8", $row['last_words']);
                $row['name'] = iconv("windows-1251", "utf-8", $row['name']);
                $rnd[] = $row;
            }
            print json_encode($rnd);
            return;
        } else {
            return;
        }
    }

    public function deathProcess($dk) {

        $dk['col'] = '';

        if ($dk['laname'] != NULL) {
            $dk['col'] = 'class="murder"';
        }

        switch ($dk['special']) {
            case 'traitor':
                $dk['special'] = 'предатель';
                break;
            case 'antagonist':
                $dk['special'] = 'антагонист';
                break;
            case 'xenomorph':
                $dk['special'] = 'ксеноморф';
                break;
            case 'Lone Operative':
                $dk['special'] = 'соло оперативник';
                break;
        }

        switch ($dk['suicide']) {
            case 1:
                $dk['col'] = 'class="suicide"';
                break;
        }

        return $dk;
    }

    public function deathDbQuery($id) {
        $res_data = $this->db->query("SELECT * FROM death WHERE id = ?i",$id);
        $dk = $this->db->fetch($res_data);
        return $dk;
    }

    public function deathQuery($id, $data, $dk) {
        if ($dk['id']) {
            $dk = $this->deathProcess($dk);
            switch ($data) {
                case 'id':
                    echo $dk['id'];
                    break;
                case 'byondkey':
                    echo $dk['byondkey'];
                    break;
                case 'name':
                    $dk['name'] = iconv("windows-1251", "utf-8", $dk['name']);
                    echo $dk['name'];
                    break;
                case 'laname':
                    $dk['laname'] = iconv("windows-1251", "utf-8", $dk['laname']);
                    return $dk['laname'];
                    break;
                case 'lakey':
                    echo $dk['lakey'];
                    break;
                case 'vitals':
                    echo "<span class='damage brute' title='Физический (BRUTE)'>".$dk['bruteloss']."</span><span class='damage fire' title='Ожоговый (FIRE)'>".$dk['fireloss']."</span><span class='damage oxy' title='Кислородный (OXYGEN)'>".$dk['oxyloss']."</span><span class='damage tox' title='Токсины (TOXIC)'>".$dk['toxloss']."</span><span class='damage brain' title='Мозговой (BRAIN)'>".$dk['brainloss']."</span><span class='damage clone' title='Генетический (CLONE)'>".$dk['cloneloss']."</span><span class='damage stamina' title='Выносливость (STAMINA)'>".$dk['staminaloss']."</span>";
                    break;
                case 'rank':
                    echo ''.iconv("windows-1251", "utf-8", $dk['job']).'<span class="role">'.$dk['special'].'</span>';
                    break;
                case 'tod':
                    echo "<time title='".$dk['tod']."' datetime='".$dk['tod']."'>".$dk['tod']."</time> - <a href='/rounds/".$dk['round_id']."'><i class='far fa-circle'></i> ".$dk['round_id'].'</a>';
                    break;
                case 'loc':
                    echo $dk['mapname']." - ".iconv("windows-1251", "utf-8", $dk['pod'])." (".$dk['x_coord'].", ".$dk['y_coord'].", ".$dk['z_coord'].")";
                    break;
                case 'lwe':
                    if ($dk['last_words'] != '') {
                        return 1;
                    }
                    break;
                case 'lw':
                    echo "<i>".iconv("windows-1251", "utf-8", $dk['last_words'])."</i>";
                    break;
            }
        } else {
            die('пошел на хуй какер мамин');
        }
    }
}
?>
