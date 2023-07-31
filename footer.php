<?php
    require_once __DIR__ . '/vendor/autoload.php';

    use PHPHtmlParser\Dom;
    use PHPHtmlParser\Options;

    // 文字コードを設定する。
    // 日本語だと文字コードの自動解析がうまく動かないようなので、
    // ページに合わせて設定する必要があります
    $options = new Options();
    $options->setEnforceEncoding('utf8');

    // 文字化けする場合は Shift JIS を試してみてください
    // $options->setEnforceEncoding('sjis');

    // ページを解析
    $url2 = 'https://sprintars.riam.kyushu-u.ac.jp/forecastj_list_CH.html';
    $dom2 = new Dom();
    $dom2->loadFromUrl($url2, $options);

    // 商品名を取得
    $forecastAir    = $dom2->find('th');
    ?>
    <table class="uk-table uk-table-small uk-table-divider">
    <thead>
            <tr>
                <th></th>
            <?php
                for ($ii=1; $ii<=8; $ii++) {
                    echo '<th>' . $forecastAir[$ii]->text .'</th>';
                    // echo $bgcolor[$ii]->bgcolor. "\n";
                }
            ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PM2.5</td>
            <?php
                for ( $ii=62; $ii<=68; $ii++ ) {
                    if ( $forecastAir[$ii]->bgcolor == 'skyblue' ) {
                        echo '<td style="color:skyblue;">少ない</td>';
                    } else if ( $forecastAir[$ii]->bgcolor == 'lawngreen'  ){
                        echo '<td style="color:lawngreen;">やや多い</td>';
                    } else if ( $forecastAir[$ii]->bgcolor == 'darkorange' ){
                        echo '<td style="color:darkorange;">多い</td>';
                    } else if ( $forecastAir[$ii]->bgcolor == 'red' ){
                        echo '<td style="color:red;">非常に多い</td>';
                    }
                }
            ?>
            </tr>
            <tr>
                <td>黄砂</td>
            <?php
                for ($ii=70; $ii<=76; $ii++) {
                    if ( $forecastAir[$ii]->bgcolor == 'skyblue' ) {
                        echo '<td style="color:skyblue;">少ない</td>';
                    } else if ( $forecastAir[$ii]->bgcolor == 'lawngreen'  ){
                        echo '<td style="color:lawngreen;">やや多い</td>';
                    } else if ( $forecastAir[$ii]->bgcolor == 'darkorange' ){
                        echo '<td style="color:darkorange;">多い</td>';
                    } else if ( $forecastAir[$ii]->bgcolor == 'red' ){
                        echo '<td style="color:red;">非常に多い</td>';
                    }
                }
            ?>
            </tr>
        </tbody>
    </table>

   
<?php
    // $i   = 0;
    // $day = 0;
    // foreach( $citydates as $citydate ) {
    //     echo mb_substr($citydate->text,0,6);
    //     if ( $i >= 1 ) {       
    //     echo '(' . $normaldays[$day]->text  . ')' . "\n";
    //     $day += 1;
    //     }

    //     $i += 1;
    // }

?>
    <footer class="uk-container">
    
        <div class="position">
            <a href="" class="uk-icon-link" uk-icon="icon:pencil;ratio:1.2">Write </a>
            <a href="<?php echo get_edit_post_link( get_the_ID() ); ?>" class="uk-icon-link" uk-icon="icon:file-edit;ratio:1.2">Rewrite </a>
            <a href="<?php echo get_template_directory_uri(); ?>/calendarApp/calendar.php" class="uk-icon-link" uk-icon="icon:calendar;ratio:1.2">Calendar </a>
        </div>

    </footer>
</body>
</html>