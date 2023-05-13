<!DOCTYPE html>
<html lang="ja" class="uk-background-secondary uk-light" >
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dBase</title>
    
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/uikit-3.16.17/css/uikit.min.css">
    <script src="<?php echo get_template_directory_uri(); ?>/uikit-3.16.17/js/uikit.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/uikit-3.16.17/js/uikit-icons.min.js"></script>
</head>
<body class="" style="">
    <header>

    </header>

    <section class="uk-container">
        <h1 class="uk-heading-medium"><?php the_title(); ?></h1>
        <?php the_content(); ?>
        <?php
            // 必要なオブジェクトを用意する
            $my_wp_query = new WP_Query();
            $all_wp_pages = $my_wp_query->query( array(
                'post_type' => 'page',
                'nopaging'  => 'true',
                'post_status' => 'publish'
            ) );
            // すべての固定ページから Portfolio の子ページを探す
            $page_children = get_page_children( get_the_ID(), $all_wp_pages );
            // print_r( $page_children);
            
            if ( !empty($page_children) ) :
        ?>
                    <table class="uk-table uk-table-small uk-table-divider">
                        <thead>
                            <tr>
                                <th>Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                            
                                foreach ( $page_children as $child ) {
                                    $option = '<a href="' . get_page_link( $child->ID ) . '">';
                                    $option .= $child->post_title;
                                    $option .= '</a>';
                                    echo '<tr><td>' . $option . '</td></tr>';
                                }
                                // echo 'title : ' . $page_children;
                            ?>           
                        </tbody>
                    </table>

            <?php endif; ?>   
    </section>

  
    <footer>
        
    </footer>
</body>
</html>