<?php
    $args = array(
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
        'category_name'=> 'chamber strings',
        'post_type'    => 'post',
        'post_status'  => 'publish'
    ); 
    // $pages = get_pages( $args ); 
    $the_query = new WP_Query($args);
?>

<table class="uk-table uk-table-small uk-table-divider">
    <thead>
        <tr>
            <th>Title</th>
        </tr>
    </thead>
    <tbody>

    <?php
        if ($the_query->have_posts()) :
            while ($the_query->have_posts()) : $the_query->the_post();
    ?>
                <tr>
                    <td><?php the_title(); ?></td>
                </tr>
        <?php endwhile; else: ?>
            <p>投稿はありません</p>
    <?php endif; ?>

        <tr> <!-- 最終段にボーダーラインとスペースをつくるため -->
            <td></td>
        </tr>
    </tbody>
</table>