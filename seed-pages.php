<?php
function upsert_page_by_slug($args) {
    $existing = get_page_by_path($args['post_name'], OBJECT, 'page');
    if ($existing) {
        wp_update_post(array_merge(['ID' => $existing->ID], $args));
        return $existing->ID;
    }
    return wp_insert_post($args);
}
$home_id = upsert_page_by_slug([
    'post_type' => 'page','post_status' => 'publish','post_title' => 'ホーム','post_name' => 'home','post_content' => "<!-- wp:paragraph --><p>SIgroup株式会社は、労働者派遣・有料職業紹介・通信・イベントの4領域を横断し、課題に応じた人員体制と実行支援を提供します。</p><!-- /wp:paragraph -->"
]);
$company_id = upsert_page_by_slug([
    'post_type' => 'page','post_status' => 'publish','post_title' => '会社概要','post_name' => 'company','post_content' => "<!-- wp:heading --><h2 class=\"wp-block-heading\">会社概要</h2><!-- /wp:heading --><!-- wp:paragraph --><p>会社情報を以下に掲載します。</p><!-- /wp:paragraph --><!-- wp:pattern {\"slug\":\"sigroup-playground/company-profile\"} /-->"
]);
$recruit_id = upsert_page_by_slug([
    'post_type' => 'page','post_status' => 'publish','post_title' => '採用情報','post_name' => 'recruit','post_content' => "<!-- wp:heading --><h2 class=\"wp-block-heading\">採用情報</h2><!-- /wp:heading --><!-- wp:paragraph --><p>柔軟な働き方と現場での成長機会を両立できる人材を募集しています。</p><!-- /wp:paragraph --><!-- wp:list --><ul><li>通信・営業・イベント領域の実務ポジション</li><li>現場理解と実行責任を持てる人材を歓迎</li><li>詳細条件は個別案内</li></ul><!-- /wp:list -->"
]);
$contact_id = upsert_page_by_slug([
    'post_type' => 'page','post_status' => 'publish','post_title' => 'お問い合わせ','post_name' => 'contact','post_content' => "<!-- wp:heading --><h2 class=\"wp-block-heading\">お問い合わせ</h2><!-- /wp:heading --><!-- wp:paragraph --><p>事業相談、採用に関するお問い合わせを受け付けています。</p><!-- /wp:paragraph --><!-- wp:list --><ul><li>TEL: 086-238-5396</li><li>FAX: 086-238-5397</li><li>住所: 岡山県岡山市北区厚生町2-11-14 3F 2号</li></ul><!-- /wp:list -->"
]);
$privacy_id = upsert_page_by_slug([
    'post_type' => 'page','post_status' => 'publish','post_title' => '個人情報保護方針','post_name' => 'privacy','post_content' => "<!-- wp:heading --><h2 class=\"wp-block-heading\">個人情報保護方針</h2><!-- /wp:heading --><!-- wp:paragraph --><p>当社は個人情報の適切な取得、利用、管理に努めます。</p><!-- /wp:paragraph --><!-- wp:list --><ul><li>法令・規範の遵守</li><li>目的外利用の防止</li><li>安全管理措置の継続的改善</li></ul><!-- /wp:list -->"
]);
update_option('show_on_front', 'page');
update_option('page_on_front', $home_id);
$menu_name = 'Primary Navigation';
$menu = wp_get_nav_menu_object($menu_name);
$menu_id = $menu ? $menu->term_id : wp_create_nav_menu($menu_name);
$items = [
    ['title' => 'ホーム', 'object_id' => $home_id],
    ['title' => '会社概要', 'object_id' => $company_id],
    ['title' => '採用情報', 'object_id' => $recruit_id],
    ['title' => 'お問い合わせ', 'object_id' => $contact_id],
    ['title' => '個人情報保護方針', 'object_id' => $privacy_id],
];
$existing_items = wp_get_nav_menu_items($menu_id);
if ($existing_items) {
    foreach ($existing_items as $item) {
        wp_delete_post($item->ID, true);
    }
}
foreach ($items as $item) {
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title' => $item['title'],
        'menu-item-object' => 'page',
        'menu-item-object-id' => $item['object_id'],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'
    ]);
}
$locations = get_theme_mod('nav_menu_locations', []);
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
?>