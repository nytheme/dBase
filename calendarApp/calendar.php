<?php
    require_once(__DIR__ . '/config/config.php');
    $db_log = new \Db_log\Model();
    $tasks = $db_log->getAll();

    $array_tasks = [];
    $array_comments = [];
    $array_tasks_for_js = [];
    $array_comments_for_js = [];
    
    foreach ($tasks as $task) {
        $array_tasks[] = [ $task->date_time, $task->task, $task->date_time_task ];
        $array_comments[$task->date_time] = [$task->category, $task->task, $task->comment];
        //JSで登録があるかどうか参照するための配列
        $array_tasks_for_js[] = substr( $task->date_time_task, 0,10 );
        $array_comments_for_js[$task->date_time] =  $task->comment; // 連想配列
    }
    
    $json_array = json_encode( $array_tasks_for_js );
    $json_array_comments = json_encode( $array_comments_for_js );
    
    if (isset($_GET['ymd'])) {
        $ymd = $_GET['ymd'];
    } else {
        // 今日の日付を表示
        $ymd = date('Ymd');
    }
    $checkbox_text = $db_log->getAllCheckbox_text($ymd);
    //var_dump($checkbox_text);
    $array_cbt = [];
    foreach ($checkbox_text as $cbt) {
        $array_cbt[] = [$cbt->date, $cbt->check1, $cbt->text];
    }
    var_dump($array_cbt);
    $json_array_cbt = json_encode($array_cbt);
?>

<!DOCTYPE html>
<html lang="ja" class="uk-background-secondary uk-light">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dBase</title>
    
    <link rel="stylesheet" href="../uikit-3.16.17/css/uikit.min.css">
    <!-- <link rel="stylesheet" href="../css/header_footer-style.css"> -->
    <link rel="stylesheet" href="style.css">
    
    <script src="../uikit-3.16.17/js/uikit.min.js"></script>
    <script src="../uikit-3.16.17/js/uikit-icons.min.js"></script>

</head>
<body class="" style="">
    <header class="uk-container" style="margin-top: 10px;">
        <a href="http://dbase.local/"><span>INDEX</span></a>
    </header>

    <section class="uk-container">
        <?php
            // 前日・翌日リンクが押された場合は、GETパラメーターから日付を取得
            // if (isset($_GET['ymd'])) {
            //     $ymd = $_GET['ymd'];
            // } else {
            //     // 今日の日付を表示
            //     $ymd = date('Ymd');
            // }
            // タイムスタンプを作成し、フォーマットをチェックする
            $timestamp = strtotime($ymd . '-01');
            if ($timestamp === false) {
                $ymd = date('Ymd');
                $timestamp = strtotime($ymd . '-01');
            }
            // カレンダーのタイトルを作成　例）2017年7月
            $html_title = date('Y年n月d日', $timestamp);
            // 前日・翌日の年月を取得
            // mktimeを使う mktime(hour,minute,second,month,day,year)
            $prev = date('Ymd', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp)-1, date('Y', $timestamp)));
            $next = date('Ymd', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp)+1, date('Y', $timestamp)));
           
            $json_date = json_encode( $ymd );
        ?>
        <div class="day_selector">
            <a href="?ymd=<?php echo $prev; ?>" class="prev">&lt;</a>
            <h3><?php echo $html_title; ?></h3>
            <a href="?ymd=<?php echo $next; ?>" class="next">&gt;</a>
        </div>  
    </section>

    <section class="uk-container">
        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label><input class="uk-checkbox cBox_1" type="checkbox"value="1"
                <?php if ($array_cbt[0][1]==1) { echo 'checked';} ?>> 脚</label>
            <label><input class="uk-checkbox cBox_2" type="checkbox"value="2"> 三角筋</label>
            <label><input class="uk-checkbox cBox_3" type="checkbox"value="3"> 大胸筋</label>
            <label><input class="uk-checkbox cBox_4" type="checkbox"value="4"> 上腕二頭筋(前)</label>
            <label><input class="uk-checkbox cBox_5" type="checkbox"value="5"> 上腕三頭筋(後)</label>
            <label><input class="uk-checkbox cBox_6" type="checkbox"value="6"> 僧帽筋</label>
        </div>
    </section>

    <section class="uk-container">
        <div class="uk-child-width-expand@s" uk-grid>
        <?php 
            function insertStr($text, $insert, $num){
                return substr_replace($text, $insert, $num, 0);
            }
        ?> 
        <?php for ( $startHour=900; $startHour<=2100; $startHour+=100 ): 
                if ( $startHour === 1600 || $startHour === 1700 || $startHour === 1800 ) { continue; } ?>
            <div>
                <p><?php echo insertStr($startHour, ':', -2); ?></p>
                <?php for ($i=0; $i<=50; $i+=50): ?>

                <?php   $value =  substr($ymd,2);
                        $value .= sprintf('%04d', $startHour + $i);
                        //echo  $array_comments[$value][0];
                ?>

                <div style="display:flex;">
                    <select 
                        id="catSelector<?php echo substr($ymd,2);echo sprintf('%04d', $startHour + $i);?>" 
                        class="uk-select uk-form-width-xsmall" style="font-size:80%;"
                    >
                        <option></option>
                        <option value="10" 
                            <?php 
                                if (array_key_exists($value,$array_comments)) {
                                    if ( $array_comments[$value][0] == 10 ) {echo "selected";}
                                } ?>
                        >IT</option>
                        <option value="20"
                            <?php if (array_key_exists($value,$array_comments)) {
                                    if ( $array_comments[$value][0] == 20 ) {echo "selected";}
                                } ?>
                        >Music</option>
                        <option value="30"
                            <?php if (array_key_exists($value,$array_comments)) {
                                    if ( $array_comments[$value][0] == 30 ) {echo "selected";}
                                } ?>
                        >DIY</option>
                        <option value="40"
                            <?php if (array_key_exists($value,$array_comments)) {
                                    if ( $array_comments[$value][0] == 40 ) {echo "selected";}
                                } ?>
                        >Learn</option>
                        <option value="50"
                            <?php if (array_key_exists($value,$array_comments)) {
                                    if ( $array_comments[$value][0] == 50 ) {echo "selected";}
                                } ?>
                        >Play</option>
                        <option value="60"
                            <?php if (array_key_exists($value,$array_comments)) {
                                    if ( $array_comments[$value][0] == 60 ) {echo "selected";}
                                } ?>
                        >Train</option>
                    </select> 

                    <!-- IT Form -->
                    <form 
                        id="taskSelector10<?php echo sprintf('%04d', $startHour + $i);?>" style="margin:0;width:100%";
                        <?php
                            $value =  substr($ymd,2);
                            $value .= sprintf('%04d', $startHour + $i);
                            
                            if (!array_key_exists($value, $array_comments) || $array_comments[$value][0] >= 20) {
                                echo 'class="display"';
                            }
                        ?>
                    >
                        <select class="uk-select <?php echo substr($ymd,2); echo sprintf('%04d', $startHour + $i)?>">
                            <option value="<?php // 無選択時. データ削除も兼ねる
                                echo $value; ?>">
                            </option>

                        <?php for ($j=10; $j<=13; $j++): ?>
                            <option value="<?php 
                                    $value =  substr($ymd,2);
                                    $value .= sprintf('%04d', $startHour + $i);
                                    $value .= sprintf('%02d', $j);
                                    echo $value;
                                ?>"
                                <?php 
                                    if (in_array($value, array_column( $array_tasks, 2))) {
                                        //多次元配列内、最下層の配列の３番目（0, 1, 2）の値を存在確認
                                        echo "selected ";
                                    }
                                ?>
                            >
                                <?php 
                                    if     ($j==10) { echo "Wordpress(0)"; } 
                                    elseif ($j==11) { echo "Program(1)";   } 
                                    elseif ($j==12) { echo "AI(2)";        }
                                    elseif ($j==13) { echo "Python(3)";    } 
                                ?>
                            </option>
                        <?php endfor; ?>
                        </select>
                    </form><!-- IT Form -->
                    <!-- Music Form -->
                    <form 
                        id="taskSelector20<?php echo sprintf('%04d', $startHour + $i);?>" style="margin:0;width:100%;"
                        <?php
                            $value =  substr($ymd,2);
                            $value .= sprintf('%04d', $startHour + $i);
                            
                            if (!array_key_exists($value, $array_comments) || $array_comments[$value][0] < 20 || $array_comments[$value][0] > 29  ) { 
                                echo 'class="display"';
                            }
                        ?>
                    >
                        <select class="uk-select <?php echo substr($ymd,2); echo sprintf('%04d', $startHour + $i)?>">
                            <option value="<?php // 無選択時. データ削除も兼ねる
                                echo $value; ?>">
                            </option>

                        <?php for ($j=20; $j<=23; $j++): ?>
                            <option value="<?php 
                                    $value =  substr($ymd,2);
                                    $value .= sprintf('%04d', $startHour + $i);
                                    $value .= sprintf('%02d', $j);
                                    echo $value;
                                ?>"
                                <?php 
                                    if (in_array($value, array_column( $array_tasks, 2))) {
                                        //多次元配列内、最下層の配列の３番目（0, 1, 2）の値を存在確認
                                        echo "selected ";
                                    }
                                ?>
                            >
                                <?php 
                                    if     ($j==20) { echo "Guitar(0)";   } 
                                    elseif ($j==21) { echo "Recording(1)";} 
                                    elseif ($j==22) { echo "Mixing(2)";   }
                                    elseif ($j==23) { echo "Other(3)";    } 
                                ?>
                            </option>
                        <?php endfor; ?>
                        </select>
                    </form><!-- Music Form -->
                    <!-- DIY Form -->
                    <form 
                        id="taskSelector30<?php echo sprintf('%04d', $startHour + $i);?>" style="margin:0;width:100%;"
                        <?php
                            $value =  substr($ymd,2);
                            $value .= sprintf('%04d', $startHour + $i);
                            
                            if (!array_key_exists($value, $array_comments) || $array_comments[$value][0] < 30 || $array_comments[$value][0] > 39  ) { 
                                echo 'class="display"';
                            }
                        ?>
                    >
                        <select class="uk-select <?php echo substr($ymd,2); echo sprintf('%04d', $startHour + $i)?>">
                            <option value="<?php // 無選択時. データ削除も兼ねる
                                echo $value; ?>">
                            </option>

                        <?php for ($j=30; $j<=33; $j++): ?>
                            <option value="<?php 
                                    $value =  substr($ymd,2);
                                    $value .= sprintf('%04d', $startHour + $i);
                                    $value .= sprintf('%02d', $j);
                                    echo $value;
                                ?>"
                                <?php 
                                    if (in_array($value, array_column( $array_tasks, 2))) {
                                        //多次元配列内、最下層の配列の３番目（0, 1, 2）の値を存在確認
                                        echo "selected ";
                                    }
                                ?>
                            >
                                <?php 
                                    if     ($j==30) { echo "機材(0)"; } 
                                    elseif ($j==31) { echo "木工(1)"; } 
                                    elseif ($j==32) { echo "学習(2)"; }
                                    elseif ($j==33) { echo "Other(3)";} 
                                ?>
                            </option>
                        <?php endfor; ?>
                        </select>
                    </form><!-- DIY Form -->
                    <!-- Learn Form -->
                    <form 
                        id="taskSelector40<?php echo sprintf('%04d', $startHour + $i);?>" style="margin:0;width:100%;"
                        <?php
                            $value =  substr($ymd,2);
                            $value .= sprintf('%04d', $startHour + $i);
                            
                            if (!array_key_exists($value, $array_comments) || $array_comments[$value][0] < 40 || $array_comments[$value][0] > 49  ) { 
                                echo 'class="display"';
                            }
                        ?>
                    >
                        <select class="uk-select <?php echo substr($ymd,2); echo sprintf('%04d', $startHour + $i)?>">
                            <option value="<?php // 無選択時. データ削除も兼ねる
                                echo $value; ?>">
                            </option>

                        <?php for ($j=40; $j<=43; $j++): ?>
                            <option value="<?php 
                                    $value =  substr($ymd,2);
                                    $value .= sprintf('%04d', $startHour + $i);
                                    $value .= sprintf('%02d', $j);
                                    echo $value;
                                ?>"
                                <?php 
                                    if (in_array($value, array_column( $array_tasks, 2))) {
                                        //多次元配列内、最下層の配列の３番目（0, 1, 2）の値を存在確認
                                        echo "selected ";
                                    }
                                ?>
                            >
                                <?php 
                                    if     ($j==40) { echo "数学(0)"; } 
                                    elseif ($j==41) { echo "物理(1)"; } 
                                    elseif ($j==42) { echo "電気(2)"; }
                                    elseif ($j==43) { echo "英語(3)"; } 
                                ?>
                            </option>
                        <?php endfor; ?>
                        </select>
                    </form><!-- Learn Form -->
                    <!-- Play Form -->
                    <form 
                        id="taskSelector50<?php echo sprintf('%04d', $startHour + $i);?>" style="margin:0;width:100%;"
                        <?php
                            $value =  substr($ymd,2);
                            $value .= sprintf('%04d', $startHour + $i);
                            
                            if (!array_key_exists($value, $array_comments) || $array_comments[$value][0] < 50 || $array_comments[$value][0] > 59  ) { 
                                echo 'class="display"';
                            }
                        ?>
                    >
                        <select class="uk-select <?php echo substr($ymd,2); echo sprintf('%04d', $startHour + $i)?>">
                            <option value="<?php // 無選択時. データ削除も兼ねる
                                echo $value; ?>">
                            </option>

                        <?php for ($j=50; $j<=53; $j++): ?>
                            <option value="<?php 
                                    $value =  substr($ymd,2);
                                    $value .= sprintf('%04d', $startHour + $i);
                                    $value .= sprintf('%02d', $j);
                                    echo $value;
                                ?>"
                                <?php 
                                    if (in_array($value, array_column( $array_tasks, 2))) {
                                        //多次元配列内、最下層の配列の３番目（0, 1, 2）の値を存在確認
                                        echo "selected ";
                                    }
                                ?>
                            >
                                <?php 
                                    if     ($j==50) { echo "Game(0)"; } 
                                    elseif ($j==51) { echo "睡眠(1)"; } 
                                    elseif ($j==52) { echo "(2)"; }
                                    elseif ($j==53) { echo "(3)"; } 
                                ?>
                            </option>
                        <?php endfor; ?>
                        </select>
                    </form><!-- Play Form -->
                </div>
                <input 
                    class="uk-input input<?php echo substr($ymd,2); echo sprintf('%04d', $startHour + $i)?>" 
                    name="<?php echo substr($ymd,2); echo sprintf('%04d', $startHour + $i)?>" 
                    placeholder="comment" style="margin-bottom:5%;" 
                    <?php 
                        $date_time_for_this = substr($ymd,2).sprintf('%04d', $startHour + $i);
                        if (array_key_exists($date_time_for_this, $array_comments)) {
                            echo 'value="' . $array_comments[$date_time_for_this][2]. '"';
                        } // $array_comments のkeyを日付にしている。該当の日付が存在したら、その中の3番目の値([２])がコメント
                    ?>
                ><!-- input -->
                <?php endfor; ?>
            </div>
            <?php 
                if ($startHour == 1300 || $startHour == 1600) { // 12:00 16:00 で改行
                    echo '</div><div class="uk-child-width-expand@s" uk-grid>';
                }
            ?>
        <?php endfor; ?>
        </div>

        <div class="uk-child-width-expand@s" uk-grid>
            <div>
                <textarea class="uk-textarea text<?php echo substr($ymd,2);?>" 
                    rows="10" placeholder="Textarea" aria-label="Textarea"
                ><?php if (!$array_cbt == null) { echo $array_cbt[0][2]; }?></textarea>
            </div>
        </div>

<style>
    .display{ display:none; }
</style>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script>
    //　年月日の取得
    let js_date = <?php echo $json_date; ?>; 
    let date = js_date.slice(2, 8);

    var catSelector = {}
    var taskSelector = {};
    var maxVal= 50;
    for (let i=900;i<=2100;i+=50) { 
        if (i<1600 && i>1900) { continue }// 1600~1900はHTMLにないので、そこをスキップしないとエラーが出る
        var _i = ( '0000' + i ).slice( -4 ); // 900 を 0900 のようにする
        var id = document.getElementById('catSelector' +date +_i);
        catSelector[date] = {[_i] : id}; // 変数で変数名をつくることができないので配列にする必要がある

        if ( _i== '0900') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '0900'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    }
                }
            }
        } else if ( _i== '0950') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '0950'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    
                    }
                }
            }
        } else if ( _i== '1000') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '1000'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    
                    }
                }
            }
        } else if ( _i== '1050') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '1050'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    
                    }
                }
            }
        } else if ( _i== '1100') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '1100'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    
                    }
                }
            }
        } else if ( _i== '1150') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '1150'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    
                    }
                }
            }
        } else if ( _i== '1200') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '1200'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    
                    }
                }
            }
        } else if ( _i== '1250') {
            catSelector[date][_i].onchange = function(){
                for (let value=10;value<=maxVal;value+=10 ) {
                    if (this.value == value) {
                        let hour = '1250'
                        for (let taskNo=10;taskNo<=maxVal;taskNo+=10) {
                            var taskID = document.getElementById('taskSelector' +taskNo +hour);
                            taskSelector[taskNo] = {[hour] : taskID}; 
                            if (taskNo == value) {
                                taskSelector[taskNo][hour].classList.remove('display');
                            } else {
                                taskSelector[taskNo][hour].classList.add('display');
                            }
                        }
                    
                    }
                }
            }
        }

    }


    // comment 入力スクリプト
    let js_array_comments = <?php echo $json_array_comments; ?>;
    //console.log(js_array_comments);
    for ($i1=2306110900;$i1<=2306111200;$i1+=50) {
        $('.input' + $i1).change(function() {
            let date_time_input = $(this).attr('name'); // input の name を取得
            let comment = $(this).val();
                console.log('date_time:' + date_time_input);
                console.log('comment:' + comment);
            $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                date_time     : date_time_input,
                comment       : comment,
                mode          :'comment'
            }, function(res) {
            });
        });
    } // comment 入力スクリプト ここまで
    
    // Task 選択プログラム
    let js_array = <?php echo $json_array; ?>;
    console.log('js_array:'+ js_array)
    var j = ( '0000' + 900 ).slice( -4 );
    for (let i3=2306110900;i3<=2306111250;i3+=50) {
         //console.log(j); 
        $('.' + i3).change(function() {
            var category = document.querySelector('#catSelector' + i3 ).value;
            //let category = $(this).parent().parent().parent().find('.catSelector').val();
            let date_time_task = $(this).val();
            let date_time = date_time_task.slice(0, 10);
            let task = date_time_task.slice(10, 12);
                console.log( date_time_task );
                console.log( 'date_time:' + date_time );
                console.log( 'category:' + category );
                console.log( 'task:' + task );

            if (task === '') { // 登録無しに変更（削除）
                console.log('登録無しに変更');   
                //JSに配列の値を指定して消去する関数は無いので以下で処理する
                var index = js_array.indexOf(date_time);
                console.log(js_array);
                console.log('index:'+index);
                js_array.splice(index, 1); 
                console.log(js_array);

                $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                    date_time     : date_time,
                    mode          : 'delete'
                }, function(res) {
                });

            } else if (js_array.indexOf(date_time) !== -1) { //配列の何番目に要素があるか検索。無ければ－１
                console.log('変更します');

                $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                    date_time     : date_time,
                    category      : category,
                    task          : task,
                    date_time_task: date_time_task,
                    mode          : 'edit'
                }, function(res) {
                });
                console.log(js_array);

            }  else if (js_array.indexOf(date_time) == -1) {
                console.log('新規登録します')
                //新規登録
                $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                    date_time     : date_time,
                    category      : category,
                    task          : task,
                    date_time_task: date_time_task,
                    mode          : 'register'
                }, function(res) {
                });

                js_array.push(date_time);
                console.log(js_array);
            }
        });
    } // Task 選択プログラム ここまで


    // チェックボックス
    let js_cbt = <?php echo $json_array_cbt; ?>;
    //console.log(js_cbt);
    for (let i=1;i<=6;i++) {
        $('.cBox_' + i).change(function() {
            // let date = $(this).attr('name'); 
            let val = $(this).val();
            let text = $('.text' + date).val();
            console.log(val);
            if (js_cbt.length >= 1)  { // 配列の中身を数える。０なら空
            //console.log('Edit');
            if (js_cbt[0][1]==1) {
                var check1 = 0
                js_cbt[0][1] = 0;
            } else {
                var check1 = 1
                js_cbt[0][1] = 1;
            }
            $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                date     : js_date,
                check1   : check1,
                text     : text,
                mode     :'Edit_check_and_text'
            }, function(res) {
            });
        } else {
             //console.log('New');
             $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                date     : js_date,
                check1   : check1,
                text     : text,
                mode     :'New_check_and_text'
            }, function(res) {
            });
            js_cbt.push(js_date); //DB参照用の配列に作成したデータを加える
        }
        });
    }
    
    //テキスト
    $('.text' + date).change(function() {
        let text = $(this).val()
        console.log(text);
        console.log('.text' + date);
        console.log('length: ' + js_cbt.length);
        if (js_cbt.length >= 1)  { // 配列の中身を数える。０なら空
            //console.log('Edit');
            if (js_cbt[0][1]==1) {
                var check1 = 1
                //console.log('length_after: 'js_cbt.length);
            } else {
                var check1 = 0
                //console.log('length_after: 'js_cbt.length);
            }
            $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                date     : js_date,
                check1   : check1,
                text     : text,
                mode     :'Edit_check_and_text'
            }, function(res) {
            });
        } else {
             //console.log('New');
             $.post('/wp-content/themes/dBase/calendarApp/lib/Controller.php', {
                date     : js_date,
                
                text     : text,
                mode     :'New_check_and_text'
            }, function(res) {
            });
            js_cbt.push(js_date); //DB参照用の配列に作成したデータを加える
        }
    });
 </script>

        

    </section>

</body>
</html>