<?php
    $parents = array(
        'sort_order'   => 'ASC',
        'sort_column'  => 'post_title',
        'hierarchical' => 1,
        'exclude'      => '',
        'include'      => '',
        'meta_key'     => '',
        'meta_value'   => '',
        'authors'      => '',
        'child_of'     => 0,
        'parent'       => 0,
        'exclude_tree' => '',
        'number'       => '',
        'offset'       => 0,
        'post_type'    => 'page',
        'post_status'  => 'publish'
    ); 
    $parentPages = get_pages( $parents ); 

    // // トークンを記載します
    // $token = 'FLwNXpEWlUxqe3Temg5gVNk0OPhnJDuMruua9qs23Yy';
    // // リクエストヘッダを作成します
    // $message = 'LINEからの通知です。';
    // $query = http_build_query(['message' => $message]);
    // $header = [
    //         'Content-Type: application/x-www-form-urlencoded',
    //         'Authorization: Bearer ' . $token,
    //         'Content-Length: ' . strlen($query)
    // ];
    // $ch = curl_init('https://notify-api.line.me/api/notify');
    // $options = [
    //     CURLOPT_RETURNTRANSFER  => true,
    //     CURLOPT_POST            => true,
    //     CURLOPT_HTTPHEADER      => $header,
    //     CURLOPT_POSTFIELDS      => $query
    // ];
    // // curl_setopt_array($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt_array($ch, $options);
    // curl_exec($ch);
    // curl_close($ch);

    // define('LINE_API_URL'  ,"https://notify-api.line.me/api/notify");
    // define('LINE_API_TOKEN','nKcR7mYu0ldOtWGgH2zOq6yCciAaTwI0LLd1YOJGXcy');    // 「TOKEN」は取得したトークンに変更してください
    // function post_message($message){
    //     $data = array(
    //                         "message" => $message
    //                     );
    //     $data = http_build_query($data, "", "&");
    //     $options = array(
    //         'http'=>array(
    //             'method'=>'POST',
    //             'header'=>"Authorization: Bearer " . LINE_API_TOKEN . "\r\n"
    //                     . "Content-Type: application/x-www-form-urlencoded\r\n"
    //                     . "Content-Length: ".strlen($data)  . "\r\n" ,
    //             'content' => $data
    //         )
    //     );
    //     $context = stream_context_create($options);
    //     $resultJson = file_get_contents(LINE_API_URL,FALSE,$context );
    //     $resutlArray = json_decode($resultJson,TRUE);
    //     if( $resutlArray['status'] != 200)  {
    //         return false;
    //     }
    //     return true;
    // }
    // post_message("はじめての投稿");  // 送信するメッセージ
?>

<?php get_header(); ?>

<section class="uk-container">
    <h2 class="uk-heading-small">INDEX</h2>
    <table class="uk-table uk-table-small uk-table-divider">
        <thead>
            <tr>
                <th>Title</th>
                <th>Contents</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ( $parentPages as $parentPage ) {
                $option = '<a href="' . get_page_link( $parentPage->ID ) . '">';
                $option .= $parentPage->post_title;
                $option .= '</a>';
                echo 
                    '<tr>
                        <td>' . $option . '</td>
                        <td>';
                            $childes = array(
                                'sort_order'   => 'ASC',
                                'sort_column'  => 'post_title',
                                'hierarchical' => 1,
                                'child_of'     => 0,
                                'parent'       => $parentPage->ID,
                                'offset'       => 0,
                                'post_type'    => 'page',
                                'post_status'  => 'publish'
                            ); 
                            $childePages = get_pages( $childes ); 

                            foreach ( $childePages as $childePage ) {
                                $option2 = '<a href="' . get_page_link( $childePage->ID ) . '">';
                                $option2 .= $childePage->post_title;
                                $option2 .= '</a></br>';    
                                echo $option2;
                            }
                    echo  '</td>
                    </tr>';
            }
        ?>
            <tr> <!-- 最終段にボーダーラインとスペースをつくるため -->
                <td></td><td></td>
            </tr>
        </tbody>
    </table>
</section>

<?php get_footer(); ?>
