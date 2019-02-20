<?php define("INDEX", "yes"); include 'db.php'  ?>

//may be work, but shit

<?php

$db = new SafeMySQL();

if(!isset($_GET['hash']) and !isset($_GET['key'])) {
    if (!isset($_GET['token']) or !isset($_GET['secret'])) {
        header("Location: /");
        die('IP-BAN');
    }

    $sec = 'very secret';

    if ($_GET['secret'] == $sec and $_GET['token'] != '') {
        $hash = hash('tiger192,3', $_GET['token']);
        $isac = $db->fetch($db->query("SELECT hash FROM acchub WHERE hash=?s", $hash));
        if ($isac['hash'] = $hash) {
            echo $isac['hash'];
        } else {
            $db->query("INSERT INTO acchub (ckey, hash) VALUES (?s, ?s)", $_GET['token'], $hash);
            echo $hash;
        }
    } else {
        header("Location: /");
        die('IP-BAN');
    }
} else {
    $auth = $db->fetch($db->query("SELECT ckey, hash FROM acchub WHERE ckey=?s AND hash=?s", $_GET['ckey'], $_GET['hash']));
    if ($auth) {
        $cookie = hash('tiger192,3', $auth['ckey'] .''. $auth['hash']);
        setcookie("HUB_USER", $cookie);
        header("Location: /user.php");
    } else {
        die('IP-BAN');
    }
}
?>
