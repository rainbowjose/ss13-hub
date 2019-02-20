<?php

if(!defined("INDEX")) die("ACCESS DENIED");

class Library extends Core {

    public function libraryMain() {
        if (isset($_GET['page'])) {
            return 'page';
        } elseif (isset($_GET['id'])) {
            return 'id';
        } else {
            header("Location: /library/page/1");
            return;
        }
    }

    public function libraryQuery($wh, $nrp = "30") {
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

        $total_rows = $this->db->getCol("SELECT COUNT(*) FROM library")[0];
        $total_pages = ceil($total_rows / $nrp);
        if ($total_pages < $page) {
            die('<center>HACKING DENIED</center>');
        }

        if ((isset($_GET["term"])) && ($_GET["term"] != '')) {
            $term = htmlspecialchars($_GET["term"]);
            $res_data = $this->db->query("SELECT * FROM library WHERE title=?s ORDER BY id DESC LIMIT $offset, ?i",$term, $nrp);
        } else {
            $res_data = $this->db->query("SELECT * FROM library ORDER BY id DESC LIMIT $offset, ?i",$nrp);
        }

        if ($wh == 'print') {
            while($row = $this->db->fetch($res_data)) {
                $bp = $this->bookProcess($row);
                echo "
                <tr ".$bp['col'].">";
                if ($bp['deleted'] != 1) {
                    echo "
                    <th scope=row".$bp['id']."><a href='/library/".$bp['id']."'>".$bp['id']."</a></th>";
                } else {
                    echo "
                    <th scope=row".$bp['id']."><i class='fas fa-times'></th>";
                }
                echo "
                    <td>".iconv("windows-1251", "utf-8", $bp['author'])."</td>
                    <td nowrap>".iconv("windows-1251", "utf-8", $bp['title'])."</td>
                    <td nowrap>".$bp['category']."</td>
                    <td align='center' nowrap>".$bp['rating']." ".$bp['rr']."</td>
                </tr>";
            }
        } elseif ($wh == 'total') {
            return $total_pages;
        } elseif ($wh == 'page') {
            return $page;
        } elseif ($wh == 'bibliotekary') {
            while($row = $this->db->fetch($res_data)) {
                $bp = $this->bookProcess($row);
                echo "
                <tr ".$bp['col'].">
                    <th scope=row".$bp['id']."><a href='/library/".$bp['id']."'>".$bp['id']."</a></th>
                    <td>".iconv("windows-1251", "utf-8", $bp['author'])."</td>
                    <td nowrap>".iconv("windows-1251", "utf-8", $bp['title'])."</td>
                    <td nowrap>".$bp['category']."</td>
                    <td align='center' nowrap>".$bp['rating']." ".$bp['rr']."</td>";
                if ($bp['deleted'] != 1) {
                    echo "
                    <td align='center' nowrap><a href='?p=bookconfig&remove=".$bp['id']."'>X</td>";
                } else {
                    echo "
                    <td align='center' nowrap><a href='?p=bookconfig&restore=".$bp['id']."'>R</td>";
                }
                echo "
                </tr>";
            }
        } elseif ($wh == 'json') {
            while($row = $this->db->fetch($res_data)) {
                $row['author'] = iconv("windows-1251", "utf-8", $row['author']);
                $row['title'] = iconv("windows-1251", "utf-8", $row['title']);
                $row['content'] = iconv("windows-1251", "utf-8", $row['content']);
                $rnd[] = $row;
            }
            print json_encode($rnd);
            return;
        } else {
            return;
        }
    }

    public function bookProcess($bk) {

        $bk['col'] = '';

        if ($bk['rating'] == 0) {
                $bk['rr'] = "<i class='fas fa-question' style='color: gray;'></i>";
            } elseif ($bk['rating'] >= 0) {
                $bk['rr'] = "<i class='fas fa-thumbs-up' style='color: green;'></i>";
            } else {
                $bk['rr'] = "<i class='fas fa-thumbs-down' style='color: red;'></i>";
            }

        switch ($bk['category']) {
            case 'Adult':
                $bk['col'] = 'class="table-danger censored"';
                break;
            case 'Fiction':
                $bk['col'] = 'class="table-success"';
                break;
            case 'Religion':
                $bk['col'] = 'class="table-primary"';
                break;
            case 'Reference':
                $bk['col'] = 'class="table-warning"';
                break;
        }

        if ($bk['deleted'] == 1) {
            $bk['author'] = 'Censored';
            $bk['title'] = 'Removed';
            $bk['content'] = 'Meow.';
            $bk['category'] = 'Trash';
            $bk['col'] = 'class="table-primary"';
        }

        return $bk;
    }

    public function bookQuery($id, $data) {
        $res_data = $this->db->query("SELECT * FROM library WHERE id = ?i",$id);
        $bk = $this->db->fetch($res_data);
        if ($bk['id']) {
            $bk = $this->bookProcess($bk);
            switch ($data) {
                case 'author':
                    $bk['author'] = iconv("windows-1251", "utf-8", $bk['author']);
                    echo $bk['author'];
                    break;
                case 'title':
                    $bk['title'] = iconv("windows-1251", "utf-8", $bk['title']);
                    echo $bk['title'];
                    break;
                case 'content':
                    $bk['content'] = iconv("windows-1251", "utf-8", $bk['content']);
                    echo $bk['content'];
                    break;
                case 'round':
                    echo $bk['round_id_created'];
                    break;
                case 'time':
                    echo "<time title='".$bk['datetime']."' datetime='".$bk['datetime']."'>".$bk['datetime']."</time>";
                    break;
                case 'rating':
                    echo $bk['rating'];
                    break;
                case 'rr':
                    echo $bk['rr'];
                    break;
            }
        } else {
            die('пошел на хуй какер мамин');
        }
    }
}
?>
