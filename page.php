<?php get_header(); ?>

<section class="uk-container">
    <h2 class="uk-heading-small"><?php the_title(); ?></h2>
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
        
        //　子固定ページがなければテーブルを表示する
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
                    ?>           
                </tbody>
            </table>

    <?php endif; ?>   
</section>

<?php get_footer(); ?>