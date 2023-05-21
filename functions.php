<?php
//ショートコードを使ったphpファイルの呼び出し方法
function dbtable_Include($params = array()) {
    extract(shortcode_atts(array('file' => 'default'), $params));
    ob_start();
    include(STYLESHEETPATH . "/shortcode/dbtable/$file.php");
    return ob_get_clean();
}
add_shortcode('dbtable', 'dbtable_Include');

//GutenbergのブロックエディタにCSSを適用
function custom_editor_settings() {
    add_theme_support( 'editor-styles' );
    add_editor_style('css/editor-style.css');
}
add_action('after_setup_theme', 'custom_editor_settings');