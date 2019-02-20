<?php

if(!defined("INDEX")) die("ACCESS DENIED");

class Rounds extends Core {

    public function roundsMain() {
        if (isset($_GET['page'])) {
            return 'page';
        } elseif (isset($_GET['round']) && $_GET['round'] !== 'page') {
            return 'round';
        } else {
            header("Location: /rounds/page/1");
            return;
        }
    }

    public function roundDbQuery($rn) {
        $r_end = $this->db->fetch($this->db->query("SELECT id FROM round ORDER BY id DESC LIMIT 1"));
        $r_state = $r_end['id'];
        $res_data = $this->db->query("SELECT * FROM round WHERE NOT id='$r_state' AND id = ?i",$rn);
        $rnd = $this->db->fetch($res_data);
        return $rnd;
    }

    public function roundQuery($rn, $data, $rnd) {
        if ($rnd['id']) {
            $rnd = $this->roundProcess($rnd);
            switch ($data) {
                case 'id':
                    echo $rnd['id'];
                    break;
                case 'gm':
                    echo $rnd['game_mode'];
                    break;
                case 'es':
                    echo $rnd['end_state'];
                    break;
                case 'start':
                    echo "<time title='".$rnd['start_datetime']."' datetime='".$rnd['start_datetime']."'>".$rnd['start_datetime']."</time>";
                    break;
                case 'end':
                    echo "<time title='".$rnd['end_datetime']."' datetime='".$rnd['end_datetime']."'>".$rnd['end_datetime']."</time>";
                    break;
                case 'init':
                    echo "<time title='".$rnd['initialize_datetime']."' datetime='".$rnd['initialize_datetime']."'>".$rnd['initialize_datetime']."</time>";
                    break;
                case 'shut':
                    echo "<time title='".$rnd['shutdown_datetime']."' datetime='".$rnd['shutdown_datetime']."'>".$rnd['shutdown_datetime']."</time>";
                    break;
                case 'time':
                    if ($rnd['shutdown_datetime'] != 'Неизвестно' && $rnd['start_datetime'] != 'Неизвестно' && $rnd['end_datetime'] != 'Неизвестно') {
                        $start = date_create($rnd['start_datetime']);
                        $end = date_create($rnd['end_datetime']);
                        $duration = date_diff($start, $end);
                        echo $duration->format('%H:%I:%S');
                    } else {
                        echo 'Неизвестно';
                    }
                    break;
                case 'itime':
                    if ($rnd['shutdown_datetime'] != 'Неизвестно') {
                        $start = date_create($rnd['initialize_datetime']);
                        $end = date_create($rnd['start_datetime']);
                        $duration = date_diff($start, $end);
                        echo $duration->format('%H:%I:%S');
                    } else {
                        echo 'Неизвестно';
                    }
                    break;
                case 'stime':
                    if ($rnd['shutdown_datetime'] != 'Неизвестно' && $rnd['start_datetime'] != 'Неизвестно' && $rnd['end_datetime'] != 'Неизвестно') {
                        $start = date_create($rnd['end_datetime']);
                        $end = date_create($rnd['shutdown_datetime']);
                        $duration = date_diff($start, $end);
                        echo $duration->format('%H:%I:%S');
                    } else {
                        echo 'Неизвестно';
                    }
                    break;
                case 'map':
                    echo $rnd['map_name'];
                    break;
                case 'sn':
                    if ($rnd['station_name'] != NULL) {
                        echo iconv("windows-1251", "utf-8", $rnd['station_name']);
                    } else {
                        echo 'Неизвестно';
                    }
                    break;
                case 'sh':
                    if ($rnd['shuttle_name']) {
                        echo $rnd['shuttle_name'];
                    } else {
                        echo 'No shuttle';
                    }
                    break;
                case 'survrate':
                    $survdata = $this->db->query("SELECT json FROM feedback WHERE round_id = ?i AND key_name = 'round_end_stats'",$rn);
                    $surv = $this->db->fetch($survdata);
                    if ($surv == NULL) {
                        break;
                    }
                    $sd = json_decode($surv['json'], TRUE);
                    if ($sd['data']['players']['total'] == 0) {
                        break;
                    }
                    $sd['data']['deadp'] = $sd['data']['players']['dead'] / $sd['data']['players']['total']  * 100;
                    $sd['data']['survp'] = $sd['data']['survivors']['total'] / $sd['data']['players']['total']  * 100;
                    $sd['data']['escap'] = $sd['data']['escapees']['total'] / $sd['data']['players']['total']  * 100;
                    return $sd['data'];
                    break;
                case 'sreason':
                    $srdata = $this->db->query("SELECT json FROM feedback WHERE round_id = ?i AND key_name = 'shuttle_reason'",$rn);
                    $shr = $this->db->fetch($srdata);
                    $sr = json_decode($shr['json'], TRUE);
                    return $sr['data'][0];
                    break;
                case 'antags':
                    $antagdata = $this->db->query("SELECT json FROM feedback WHERE round_id = ?i AND key_name = 'antagonists'",$rn);
                    $antags = $this->db->fetch($antagdata);
                    if ($antags == NULL) {
                        echo 'None';
                        break;
                    }
                    $ag = json_decode($antags['json'], TRUE);
                    $ag = $this->processAntags($ag);
                    return;
                    break;
                case 'expl':
                    $expdata = $this->db->query("SELECT json FROM feedback WHERE round_id = ?i AND key_name = 'explosion'",$rn);
                    $explosions = $this->db->fetch($expdata);
                    if ($explosions == NULL) {
                        echo 'None';
                        break;
                    }
                    $ex = json_decode($explosions['json'], TRUE);
                    $ex = $this->processExplosions($ex);
                    break;
                case 'deaths':
                    $round = $rnd['id'];
                    $total_deaths = $this->db->getCol("SELECT COUNT(*) FROM death WHERE round_id=?s", $round)[0];
                    echo $total_deaths;
                    break;
                case 'misc':
                    $md = $this->db->query("SELECT key_name FROM feedback WHERE round_id = ?i",$rn);
                    while($row = $this->db->fetch($md)) {
                        echo '<a class="smts" href=/rounds/'.$rn.'/'.$row['key_name'].'>'.$this->csn($row)['key_name'].'</a> ';
                    }
                    break;
            }
        } else {
            die('раунд ещё не закончился, либо его не существует');
        }
        return;
    }

    function csn($s) {
        $f = array(
                'random_seed', 'dm_version', 'byond_version', 'byond_build',
                'cell_used', 'time_dilation_current', 'job_preferences',
                'religion_name', 'religion_deity', 'vending_machine_usage',
                'admin_verb', 'radio_usage', 'changeling_power_purchase',
                'security_level_changes', 'event_ran', 'food_made',
                'object_crafted', 'chemical_reaction', 'handcuffs',
                'science_techweb_unlock', 'food_harvested', 'item_printed',
                'slime_babies_born', 'item_used_for_combat', 'zone_targeted',
                'export_sold_cost', 'slime_core_harvested', 'gun_fired',
                'slime_cores_used', 'preferences_verb', 'changeling_powers',
                'cargo_imports', 'explosion', 'pick_used_mining', 'traumas',
                'ore_mined', 'surgeries_completed', 'mmis_filled',
                'shuttle_purchase', 'mobs_killed_mining', 'shuttle_reason',
                'antagonists', 'roundend_nukedisk', 'round_end_stats',
                'ahelp_stats', 'client_'
                );

        $t = array(
                'случайное зерно', 'версия DM', 'версия BYOND', 'билд BYOND',
                'использованные батареи', 'смещение времени', 'приоритет в ролях',
                'название религии', 'божество религии', 'использованные раздатчики',
                'настройки админов', 'использование связи', 'выбранные силы генокрадов',
                'смены уровней безопасности', 'события', 'еды создано',
                'объектов создано', 'химические реакции', 'наручники',
                'продвижение науки', 'еды собрано', 'предметов напечатано',
                'рождено слаймов', 'предметы использованы в бою', 'куда целились',
                'экспорты', 'собрано ядер слаймов', 'выстрелов из оружия',
                'использовано ядер слаймов', 'смены настроек', 'силы генокрадов',
                'импорты', 'взрывы', 'инструменты раскопок', 'травмы', 'руды добыто',
                'операций выполнено', 'заполнено MMI', 'шаттлов куплено', 'убито мобов в шахте',
                'причина шаттла', 'антаги', 'местонахождение диска нюки', 'популяция в конце',
                'статистика админхелпа', 'клиентская '
                );
        $s = str_replace($f, $t, $s);
        return $s;
    }

    public function roundsQuery($wh, $nrp = "50") {
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

        $r_end = $this->db->fetch($this->db->query("SELECT id FROM round ORDER BY id DESC LIMIT 1"));
        $r_state = $r_end['id'];

        $total_rows = $this->db->getCol("SELECT COUNT(*) FROM round WHERE NOT id='$r_state'")[0];
        $total_pages = ceil($total_rows / $nrp);

        if ($total_pages < $page) {
            die('<center>HACKING DENIED</center>');
        }

        $res_data = $this->db->query("SELECT * FROM round WHERE NOT id='$r_state' ORDER BY id DESC LIMIT $offset, ?i",$nrp);

        switch ($wh) {
            case 'print':
                while($row = $this->db->fetch($res_data)) {
                    $rnd = $this->roundProcess($row);
                    echo "
                    <tr class='newbansys'></tr>
                    <tr class='table-".$rnd['col']."'>
                        <td scope=row".$rnd['id']."><a href=/rounds/".$rnd['id']."><i class='far fa-circle'></i> ".$rnd['id']."</a></td>
                        <td>".$rnd['game_mode']."</td>
                        <td>".$rnd['end_state']."</td>
                        <td>".$rnd['map_name']."</td>
                        <td><time title='".$rnd['start_datetime']."' datetime='".$rnd['start_datetime']."'>".$rnd['start_datetime']."</time></td>
                        <td><time title='".$rnd['end_datetime']."' datetime='".$rnd['end_datetime']."'>".$rnd['end_datetime']."</time></td>
                    </tr>";
                }
                break;
            case 'total':
                return $total_pages;
                break;
            case 'page':
                return $page;
                break;
            case 'tp':
                return $total_pages;
                break;
            case 'json':
                while($row = $this->db->fetch($res_data)) {
                    print json_encode($row, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRETTY_PRINT);
                }
                return;
                break;
        }
    }

    public function roundProcess($rn) {

        $rn['col'] = '';
        switch ($rn['game_mode']) {
            case 'traitor':
                $rn['game_mode'] = '<i class="fas fa-fw fa-skull-crossbones"></i> Предатель';
                break;
            case 'extended':
                $rn['game_mode'] = '<i class="fas fa-fw fa-running"></i> Специальный';
                break;
            case 'changeling':
                $rn['game_mode'] = '<i class="fas fa-fw fa-spider"></i> Генокрад';
                break;
            case 'traitor+changeling':
                $rn['game_mode'] = '<i class="fas fa-fw fa-user-astronaut"></i> Предатель и Генокрад';
                break;
            case 'traitor+brothers':
                $rn['game_mode'] = '<i class="fas fa-fw fa-user-injured"></i> Предатель и Братки';
                break;
            case 'cult':
                $rn['game_mode'] = '<i class="fas fa-fw fa-book-dead text-danger"></i> Культ';
                break;
            case 'wizard':
                $rn['game_mode'] = '<i class="fas fa-fw fa-hat-wizard"></i> Маг';
                break;
            case 'monkey':
                $rn['game_mode'] = '<i class="fas fa-fw fa-dizzy"></i> Обезьянопокалипсис';
                break;
            case 'Internal Affairs':
                $rn['game_mode'] = '<i class="fas fa-fw fa-user-ninja"></i> Агент Внутренних Дел';
                break;
            case 'devil':
                $rn['game_mode'] = '<i class="fas fa-fw fa-handshake"></i> Дьявол';
                break;
            case 'blob':
                $rn['game_mode'] = '<i class="fas fa-fw fa-cubes"></i> Блоб';
                break;
            case 'sandbox':
                $rn['game_mode'] = '<i class="fas fa-fw fa-bug"></i> Песочница';
                break;
            case 'secret extended':
                $rn['game_mode'] = '<i class="fas fa-fw fa-bed"></i> Скрытый специальный';
                break;
            case 'meteor':
                $rn['game_mode'] = '<i class="fas fa-fw fa-meteor"></i> <b>МЕТЕОРЫ!!!</b>';
                break;
            case null:
                $rn['game_mode'] = '<i class="fas fa-fw fa-bug"></i> Неизвестный';
                break;
        }

        if ($rn['start_datetime'] != NULL) {
            $t = date_create($rn['start_datetime']);
            $rn['start_datetime'] = $t->format('Y-m-d\TH:i:s\Z');
        } else {
            $rn['start_datetime'] = 'Неизвестно';
        }

        if ($rn['end_datetime'] != NULL) {
            $t = date_create($rn['end_datetime']);
            $rn['end_datetime'] = $t->format('Y-m-d\TH:i:s\Z');
        } else {
            $rn['end_datetime'] = 'Неизвестно';
        }

        if ($rn['shutdown_datetime'] != NULL) {
            $t = date_create($rn['shutdown_datetime']);
            $rn['shutdown_datetime'] = $t->format('Y-m-d\TH:i:s\Z');
        } else {
            $rn['shutdown_datetime'] = 'Неизвестно';
        }

        if ($rn['initialize_datetime'] != NULL) {
            $t = date_create($rn['initialize_datetime']);
            $rn['initialize_datetime'] = $t->format('Y-m-d\TH:i:s\Z');
        } else {
            $rn['initialize_datetime'] = 'Неизвестно';
        }

        if ($rn['map_name'] == NULL) {
            $rn['map_name'] = 'Неизвестно';
        }

        switch ($rn['end_state']) {
            case 'nuke':
                $rn['col'] = 'inverse';
                $rn['end_state'] = '<i class="fas fa-fw fa-bomb"></i> Ядерный взрыв';
                break;
            case 'proper completion':
                $rn['end_state'] = '<i class="fas fa-fw fa-check"></i> Обычный конец';
                break;
            case 'restart vote':
                $rn['end_state'] = '<i class="fas fa-fw fa-redo"></i> Голосование за перезагрузку';
                break;
            case 'restart vote':
                $rn['end_state'] = '<i class="fas fa-fw fa-redo"></i> Голосование за перезагрузку';
                break;
            case NULL:
                $rn['end_state'] = '<i class="fas fa-fw fa-check"></i> Обычный конец';
                break;
        };

        switch ($rn['game_mode_result']) {
            case 'loss - staff stopped the monkeys':
                $rn['col'] = 'danger';
                $rn['end_state'] = '<i class="fas fa-fw fa-times"></i> Поражение - персонал справился с обезьянами';
                break;
            case 'loss - staff stopped the cult':
                $rn['col'] = 'danger';
                $rn['end_state'] = '<i class="fas fa-fw fa-times"></i> Поражение - персонал остановил культистов';
                break;
            case 'loss - rev heads killed':
                $rn['col'] = 'danger';
                $rn['end_state'] = '<i class="fas fa-fw fa-times"></i> Поражение - главы революционеров убиты';
                break;
            case 'win - cult win':
                $rn['col'] = 'success';
                $rn['end_state'] = '<i class="fas fa-fw fa-check"></i> Победа - культисты победили';
                break;
            case 'win - heads killed':
                $rn['col'] = 'success';
                $rn['end_state'] = '<i class="fas fa-fw fa-check"></i> Победа - главы убиты';
                break;
            case 'win - syndicate nuke':
                $rn['col'] = 'success';
                $rn['end_state'] = '<i class="fas fa-fw fa-check"></i> Победа - оперативники взорвали станцию';
                break;
            case NULL:
                $rn['end_state'] = '<i class="fas fa-fw fa-question"></i> Технический раунд';
                break;
        }

        if (preg_match('/admin reboot - by/', $rn['end_state'])) {
            $rn['end_state'] = preg_replace('/admin reboot - by/', '<i class="fas fa-fw fa-redo"></i> Перезагрузка сервера - ', $rn['end_state']);
            $rn['col'] = 'reboot';
        }

        return $rn;
    }

    //обработчик статистики

    public function jstt($data) {
        $table = '';
        foreach ($data as $key => $value) {
            if ($key != 'data') {
                $table .= '<tr><td><strong>'. $key .'</strong></td><td>';
            }
            if (is_object($value) || is_array($value)) {
                $table .= $this->jstt($value);
            } else {
                $table .= $value;
            }
            $table .= '</td></tr>';
        }
        return $table;
    }

    public function admin_jstt($data) {
        $table = '';
        foreach ($data as $key => $value) {
                foreach ($value as $key => $value) {
                    $keyname = $key;
                    foreach ($value as $key => $value) {
                        $table .= '<tr><td><strong>'. $keyname .'</strong></td>';
                        $table .= '<td>'. $key .'</td>';
                        $table .= '<td>'. $value .'</td></tr>';
                    }
                }
            }
        return $table;
    }

    public function sci_jstt($data) {
        $table = '';
        foreach ($data as $key => $value) {
                foreach ($value as $key => $value) {
                    $table .= '<tr><td><strong>'. $key .'</strong></td>';
                    foreach ($value as $key => $value) {
                        //$table .= '<tr><td><strong>'. $keyname .'</strong></td>';
                        //$table .= '<td>'. $key .'</td>';
                        $table .= '<td>'. $value .'</td>';
                    }
                    $table .= '</tr>';
                }
            }
        return $table;
    }

    public function weapon_jstt($data) {
        $table = '';
        $td = 0;
        foreach ($data as $key => $value) {
                foreach ($value as $key => $value) {
                    $keyname = $key;
                    foreach ($value as $key => $value) {
                        //$table .= '<tr><td><strong>'. $keyname .'</strong></td>';
                        $table .= '<tr><td><strong>'. $key .'</strong></td>';
                        $table .= '<td>'. $value .'</td>';
                        $table .= '<td>'. $keyname .'</td>';
                        $table .= '<td>'. $keyname * $value .'</td>';
                        $td += $keyname * $value;
                    }
                    $table .= '</tr>';
                }
            }
        $table .= '<tfoot><tr><th colspan="3"><strong>Общий урон</strong></th><td>'.$td.'</td></tr></tfoot>';
        return $table;
    }

    public function exp_jstt($data) {
        $table = '';
        $td = 0;
        foreach ($data as $key => $value) {
                foreach ($value as $key => $value) {
                    $keyname = $key;
                    foreach ($value as $key => $value) {
                        //$table .= '<tr><td><strong>'. $keyname .'</strong></td>';
                        $table .= '<tr><td><strong>'. $keyname .'</strong></td>';
                        $table .= '<td>'. $value .'</td>';
                        $table .= '<td>'. $key .'</td>';
                        $td += $key * $value;
                    }
                    $table .= '</tr>';
                }
            }
        $table .= '<tfoot><tr><th colspan="2"><strong>Общая выручка</strong></th><td>'.$td.'</td></tr></tfoot>';
        return $table;
    }

    public function changeling_jstt($data) {
        $table = '';
        foreach ($data as $key => $value) {
                foreach ($value as $key => $value) {
                    $keyname = $key;
                    if ($key == 'Absorb DNA') {
                        $ad = 0;
                        foreach ($value as $key => $value) {
                            $ad += $value;
                        }
                        $table .= '<tr><td><strong>'. $keyname .'</strong></td>';
                        $table .= '<td>'. $ad .'</td>';
                        $table .= '</tr>';
                    } else {
                        $table .= '<tr><td><strong>'. $keyname .'</strong></td>';
                        $table .= '<td>'. $value .'</td>';
                        $table .= '</tr>';
                    }

                }
            }
        return $table;
    }

    public function job_jstt($data) {
        $table = '';
        foreach ($data as $key => $value) {
            if ( ! is_numeric($key) and $key != 'data' and $key != 'high' and $key != 'medium' and $key != 'low' and $key != 'never' and $key != 'banned' and $key != 'young') {
                $table .= '<tr><td><strong>'.$key.'</strong></td>';
            }
            if (is_object($value) || is_array($value)) {
                $table .= $this->job_jstt($value);
            } else {
                $table .= '<td>'.$value.'</td>';
            }
        }
        $table .= '</tr>';
        return $table;
    }

    public function statQueryJson($r, $s) {
        if ($s == 'all') {
            $sq = $this->db->query("SELECT * FROM feedback WHERE round_id = ?i",$r);
            while($row = $this->db->fetch($sq)) {
                $stats[] = $row;
            }
        } else {
            $sq = $this->db->query("SELECT * FROM feedback WHERE round_id = ?i and key_name = ?s",$r, $s);
            $stats = $this->db->fetch($sq);
        }
        if ($stats) {
            return $stats;
        }
    }

    public function statQuery($r, $s) {
        $sq = $this->db->query("SELECT * FROM feedback WHERE round_id = ?i and key_name = ?s",$r, $s);
        $stats = $this->db->fetch($sq);
        if ($stats) {
            if ($stats['key_type'] == 'amount' or $stats['key_type'] == 'text') {
                echo '<center><h2>'.$stats['key_name'].': '.$this->jstt(json_decode($stats['json'])).'</h2></center>';
                return;
            }
            echo '<table class="stable table">';
            switch ($stats['key_name']) {
                case 'cell_used':
                    echo '<thead>
                            <tr>
                                <th>Батарейка</th>
                                <th>Количество использований</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/stock_parts/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'admin_verb':
                    echo '<thead>
                            <tr>
                                <th>Действие</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'radio_usage':
                    echo '<thead>
                            <tr>
                                <th>Канал</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'vending_machine_usage':
                    echo '<thead>
                            <tr>
                                <th>Раздатчик</th>
                                <th>Предмет</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    $r = array('/obj/machinery/vending/', '/obj/item/');
                    echo str_replace($r, '', $this->admin_jstt(json_decode($stats['json'])));
                    break;
                case 'wizard_spell_learned':
                    echo '<thead>
                            <tr>
                                <th>Заклинание</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'item_used_for_combat':
                    echo '<thead>
                            <tr>
                                <th>Оружие</th>
                                <th>Частота</th>
                                <th>Урон</th>
                                <th>Всего урона</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/', '', $this->weapon_jstt(json_decode($stats['json'])));
                    break;
                case 'zone_targeted':
                    echo '<thead>
                            <tr>
                                <th>Часть тела</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'gun_fired':
                    echo '<thead>
                            <tr>
                                <th>Оружие</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'security_level_changes':
                    echo '<thead>
                            <tr>
                                <th>Уровень</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'admin_toggle':
                    echo '<thead>
                            <tr>
                                <th>Действие</th>
                                <th>Тип</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->admin_jstt(json_decode($stats['json']));
                    break;
                case 'changeling_powers':
                    echo '<thead>
                            <tr>
                                <th>Способность</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->changeling_jstt(json_decode($stats['json']));
                    break;
                case 'preferences_verb':
                    echo '<thead>
                            <tr>
                                <th>Действие</th>
                                <th>Тип</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo $this->admin_jstt(json_decode($stats['json']));
                    break;
                case 'food_made':
                    echo '<thead>
                            <tr>
                                <th>Тип еды</th>
                                <th>Сделано</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/reagent_containers/food/snacks/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'event_ran':
                    echo '<thead>
                            <tr>
                                <th>Событие</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo str_replace('/datum/round_event/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'chemical_reaction':
                    echo '<thead>
                            <tr>
                                <th>Реагент</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'cargo_imports':
                    echo '<thead>
                            <tr>
                                <th>Куплено</th>
                                <th>Количество</th>
                                <th>Стоимость</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>';
                    echo str_replace('Общий урон', 'Общая сумма', $this->weapon_jstt(json_decode($stats['json'])));
                    break;
                case 'pick_used_mining':
                    echo '<thead>
                            <tr>
                                <th>Инструмент</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'traumas':
                    echo '<thead>
                            <tr>
                                <th>Травма</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo str_replace('/datum/brain_trauma/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'ore_mined':
                    echo '<thead>
                            <tr>
                                <th>Тип</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/stack/ore/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'surgeries_completed':
                    echo '<thead>
                            <tr>
                                <th>Тип</th>
                                <th>Частота</th>
                            </tr>
                        </thead>';
                    echo str_replace('/datum/surgery/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'mobs_killed_mining':
                    echo '<thead>
                            <tr>
                                <th>Тип</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo str_replace('/mob/living/simple_animal/hostile/asteroid/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'ahelp_stats':
                    echo '<thead>
                            <tr>
                                <th>Тип</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'client_byond_version':
                    echo '<thead>
                            <tr>
                                <th>Тип</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'changeling_power_purchase':
                    echo '<thead>
                            <tr>
                                <th>Способность</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'food_harvested':
                    echo '<thead>
                            <tr>
                                <th>Тип</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'slime_babies_born':
                    echo '<thead>
                            <tr>
                                <th>Тип слайма</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'slime_core_harvested':
                    echo '<thead>
                            <tr>
                                <th>Тип ядра</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->jstt(json_decode($stats['json']));
                    break;
                case 'round_end_stats':
                    echo '<thead>
                            <tr>
                                <th>Тип</th>
                                <th>Тип суммы</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo $this->admin_jstt(json_decode($stats['json']));
                    break;
                case 'object_crafted':
                    echo '<thead>
                            <tr>
                                <th>Предмет</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'item_printed':
                    echo '<thead>
                            <tr>
                                <th>Машина</th>
                                <th>Предмет</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    $r = array('/obj/machinery/', '/obj/item/');
                    echo str_replace($r, '', $this->admin_jstt(json_decode($stats['json'])));
                    break;
                case 'export_sold_cost':
                    echo '<thead>
                            <tr>
                                <th>Продано</th>
                                <th>Цена за штуку</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                        $r = array('/obj/machinery/', '/obj/item/', '/obj/structure/', 'stack/sheet/');
                        echo str_replace($r, '', $this->exp_jstt(json_decode($stats['json'])));
                    break;
                case 'handcuffs':
                    echo '<thead>
                            <tr>
                                <th>Предмет</th>
                                <th>Количество</th>
                            </tr>
                        </thead>';
                    echo str_replace('/obj/item/restraints/', '', $this->jstt(json_decode($stats['json'])));
                    break;
                case 'science_techweb_unlock':
                    echo '<thead>
                            <tr>
                                <th>ID</th>
                                <th>Тип</th>
                                <th>Технология</th>
                                <th>Стоимость</th>
                                <th>Время</th>
                            </tr>
                        </thead>';
                    echo $this->sci_jstt(json_decode($stats['json']));
                    break;
                case 'explosion':
                    $this->processExplosions((json_decode($stats['json'], TRUE)));
                    break;
                case 'antagonists':
                    $this->processAntags((json_decode($stats['json'], TRUE)));
                    break;
                case 'job_preferences':
                    echo '<thead>
                            <tr>
                                <th>Должность</th>
                                <th>Высоко</th>
                                <th>Средне</th>
                                <th>Низко</th>
                                <th>Никогда</th>
                                <th>Забанена</th>
                                <th>Малый возраст</th>
                            </tr>
                        </thead>';
                    echo $this->job_jstt(json_decode($stats['json']));
                    break;
                default:
                    echo $this->jstt(json_decode($stats['json']));
                    break;
            }
            echo '</table>';
            echo '<hr><dl class="jsjs">';
            echo '<dt>ID:</dt><dd>'.$stats['id'].'</dd>';
            echo '<dt>Тип:</dt><dd>'.$stats['key_type'].'</dd>';
            echo '<dt>Имя:</dt><dd>'.$stats['key_name'].'</dd>';
            echo '<dt>Время записи:</dt><dd>'.$stats['datetime'].'</dd>';
            echo '<dt>Версия:</dt><dd>'.$stats['version'].'</dd>';
            echo '<dt>Чистый JSON:</dt><dd><pre>'.$stats['json'].'</pre></dd>';
            echo '</dl>';
        } else {
            die();
        }
    }

    //обработчик взрывов

    public function processExplosions($pe) {
        echo '<table class="table t-expl">
            <thead>
                <tr>
                    <th><div>Уничтожение</div></th>
                    <th><div>Тяжелый</div></th>
                    <th><div>Лёгкий</div></th>
                    <th><div>Вспышка</div></th>
                    <th><div>Поджигающий</div></th>
                    <th><div>Локация</div></th>
                </tr>
            </thead>
            <tbody>';
        foreach ($pe['data'] as $key => $value) {
            echo '
                            <tr>
                                <td class="align-middle text-center">'.$pe['data'][($key)]['dev'].'</td>
                                <td class="align-middle text-center">'.$pe['data'][($key)]['heavy'].'</td>
                                <td class="align-middle text-center">'.$pe['data'][($key)]['light'].'</td>
                                <td class="align-middle text-center">'.$pe['data'][($key)]['flash'].'</td>
                                <td class="align-middle text-center">'.$pe['data'][($key)]['flame'].'</td>
                                <td class="align-middle">'.$pe['data'][($key)]['area'].'<br>
                                    <small>('.$pe['data'][($key)]['x'].', '.$pe['data'][($key)]['y'].', '.$pe['data'][($key)]['z'].')</small>
                                </td>
                            </tr>';
        }
        echo '</tbody>
        </table>';
    }

    //обработчик антагов

    public function processAntags($an) {
        $sc= 0;
        $nsc = 0;
        foreach ($an['data'] as $key => $value) { foreach ($an['data'][($key)]['objectives'] as $obj => $value) {
            if ($an['data'][($key)]['objectives'][($obj)]['result'] == 'SUCCESS') {
                    $sc += 1;
                } else {
                    $nsc += 1;
                }
            }
        }
        echo '
                <div class="r-antags-summary">
                    <h4>Общие результаты</h4>
                    <ul class="r-antags-summary-list">
                        <li class="r-antags-fault">
                            <span class="r-antags-fault">Провальных</span>
                            <strong>'.$nsc.'</strong>
                        </li>
                        <li class="r-antags-success">
                            <span class="r-antags-success">Успешных</span>
                            <strong>'.$sc.'</strong>
                        </li>
                    </ul>
                    <div class="r-antags-rate">
                        Антаги
                        <span class="r-antags-'.(($sc >= $nsc) ? 'success' : 'fault').'">'.(($sc >= $nsc) ? 'выполнили' : 'провалили').'</span>
                            большинство своих задач
                    </div>
                </div>';
        foreach ($an['data'] as $key => $value) {
            switch ($an['data'][($key)]['antagonist_name']) {
                case 'Traitor':
                    $an['data'][($key)]['antagonist_name'] = 'Предатель';
                    break;
                case 'Antagonist':
                    $an['data'][($key)]['antagonist_name'] = 'Антагонист';
                    break;
                case 'Xenomorph':
                    $an['data'][($key)]['antagonist_name'] = 'Ксеноморф';
                    break;
            }
            echo '
                <div class="r-antag-pick">
                    <h4 class="r-antag"><span class="text-muted">'.$an['data'][($key)]['antagonist_name'].'</span> '.$an['data'][($key)]['name'].'<small>/'.$an['data'][($key)]['key'].'</small></h4>
                    <ul class="r-antag-objectives">';
            foreach ($an['data'][($key)]['objectives'] as $obj => $value) {
                if ($an['data'][($key)]['objectives'][($obj)]['result'] == 'SUCCESS') {
                    $suc = 'success';
                    $an['data'][($key)]['objectives'][($obj)]['result'] = 'Успех';
                } else {
                    $suc = 'fault';
                    $an['data'][($key)]['objectives'][($obj)]['result'] = 'Провал';
                }
                echo '
                        <li class="r-antags-'.$suc.'">
                            <span class="r-antags-'.$suc.'">'.$an['data'][($key)]['objectives'][($obj)]['result'].'</span>
                            <strong>'.$an['data'][($key)]['objectives'][($obj)]['text'].'</strong><br>
                            <small>'.$an['data'][($key)]['objectives'][($obj)]['objective_type'].'</small>
                        </li>';
            }

            echo '
                    </ul>
                </div>';
        }
        return;

    }

    //Парсер логов
    public function lp() {
        $r = escapeshellcmd($_GET['round']);
        exec('sudo -H -u traitor sh -c "cd /home/traitor/YTgstation/data/ && python3 psv.py '.$r.'" 2>&1');
    }

    public function le() {
        $r = ($_GET['round']);
        if (file_exists('logs/new/round-'.$r.'/log.txt')) {
            echo '<a href="/logs/new/round-'.$r.'">LINK</a>';
        } else {
            echo 'Parsing...';
            $this->lp();
        }
    }
}
?>
