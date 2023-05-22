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
