<?php
/**
 *  ajax_meta_tags_controller.php
 *
 * @package
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version Author: bislewl  1/12/2016 11:58 PM Modified in meta_tags_controller
 */

require('includes/application_top.php');

$action = zen_db_prepare_input($_POST['meta_action']);
$meta_group = strtolower(zen_db_prepare_input($_POST['group']));
$id = zen_db_prepare_input($_POST['page_id']);
$title = zen_db_prepare_input(addslashes($_POST['title']));
$keywords = zen_db_prepare_input(addslashes($_POST['keywords']));
$description = zen_db_prepare_input(addslashes($_POST['description']));
$language_id = zen_db_prepare_input($_POST['language_id']);
$new_page_name = zen_db_prepare_input($_POST['new_page_name']);


if (isset($action) && $action != '') {
    switch ($action) {
        case 'save':
            switch ($meta_group) {
                case 'products':
                case 'categories':
                    if ($meta_group == 'products') {
                        $table = TABLE_META_TAGS_PRODUCTS_DESCRIPTION;
                    }
                    if ($meta_group == 'categories') {
                        $table = TABLE_METATAGS_CATEGORIES_DESCRIPTION;
                    }
                    $current_metatag = $db->Execute("SELECT metatags_title FROM " . $table . " WHERE " . $meta_group . "_id='" . $id . "' AND language_id='" . $language_id . "'");
                    if ($current_metatag->RecordCount() > 0) {
                        $sql = "UPDATE " . $table . " SET metatags_title='" . $title . "', metatags_keywords='" . $keywords . "', metatags_description='" . $description . "' WHERE " . $meta_group . "_id='" . $id . "' AND language_id='" . $language_id . "'";
                    } else {
                        $sql = "INSERT INTO " . $table . " (" . $meta_group . "_id , language_id, metatags_title, metatags_keywords, metatags_description) VALUES  ('" . $id . "', '" . $language_id . "', '" . $title . "', '" . $keywords . "', '" . $description . "')";

                    }
                    $db->Execute($sql);
                    $respond['status'] = 'success';
                    $respond['current_tags_sql'];
                    $respond['sql'] = $sql;
                    break;
                case 'ez-pages':
                    $sql = "UPDATE " . TABLE_EZPAGES . " SET pages_meta_title='" . $title . "', pages_meta_keywords='" . $keywords . "', pages_meta_description='" . $description . "' WHERE pages_id='" . $id . "' AND languages_id='" . $language_id . "'";
                    $db->Execute($sql);
                    $respond['status'] = 'success';
                    $respond['sql'] = $sql;
                    break;
                case 'general':
                default:
                    $sql = "UPDATE " . TABLE_META_TAGS . " SET metatags_title='" . $title . "', metatags_keywords='" .$keywords."', metatags_description='" . $description . "' WHERE meta_tags_id='" . $id . "' AND language_id='" . $language_id . "'";
                    $db->Execute($sql);
                    $respond['status'] = 'success';
                    $respond['sql'] = $sql;
                    break;
            }
            break;
        case 'delete':
            $sql = "DELETE FROM ".TABLE_META_TAGS." WHERE meta_tag_group='".$meta_group."' AND meta_tags_id='".$id."'";
            $db->Execute($sql);
            $respond['status'] = 'success';
            $respond['sql'] = $sql;
            break;
        case 'add':
            $sql = "INSERT INTO " . TABLE_META_TAGS . " (page, language_id, meta_tag_group) VALUES ('" . $new_page_name . "','" . $language_id . "', '" . $meta_group . "')";
            $db->Execute($sql);
            $respond['status'] = 'success';
            $respond['sql'] = $sql;
            break;
        case 'view':
        default:
            if (!isset($meta_group) || $meta_group == '') {
                $meta_group = 'general';
            }
            switch ($meta_group) {
                case 'products':
                    $products_desc_query = $db->Execute("SELECT pd.products_id,  pd.products_name, pd.language_id, mp.metatags_title, mp.metatags_keywords, mp.metatags_description  FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd LEFT JOIN " . TABLE_META_TAGS_PRODUCTS_DESCRIPTION . " mp ON pd.products_id = mp.products_id AND pd.language_id = mp.language_id ORDER BY pd.products_name");
                    $products_meta = array();
                    while (!$products_desc_query->EOF) {
                        $products_meta[] = array(
                            'id' => $products_desc_query->fields['products_id'],
                            'name' => $products_desc_query->fields['products_name'],
                            'title' => stripslashes($products_desc_query->fields['metatags_title']),
                            'keywords' => stripslashes($products_desc_query->fields['metatags_keywords']),
                            'description' => stripslashes($products_desc_query->fields['metatags_description']),
                            'language' => zen_get_language_icon((int)$products_desc_query->fields['language_id']),
                            'language_id' => $products_desc_query->fields['language_id'],
                            'removeable' => 'false'
                        );
                        $products_desc_query->MoveNext();
                    }
                    $respond['group'] = $meta_group;
                    $respond['metatags'] = $products_meta;
                    break;
                case 'categories':
                    $categories_desc_query = $db->Execute("SELECT cd.categories_id, cd.categories_name, cd.language_id, mc.metatags_title, mc.metatags_keywords, mc.metatags_description FROM " . TABLE_CATEGORIES_DESCRIPTION . " cd LEFT JOIN " . TABLE_METATAGS_CATEGORIES_DESCRIPTION . " mc ON cd.categories_id = mc.categories_id AND cd.language_id = mc.language_id ORDER BY cd.categories_name");
                    $categories_meta = array();
                    while (!$categories_desc_query->EOF) {
                        $categories_meta[] = array(
                            'id' => $categories_desc_query->fields['categories_id'],
                            'name' => $categories_desc_query->fields['categories_name'],
                            'title' => stripslashes($categories_desc_query->fields['metatags_title']),
                            'keywords' => stripslashes($categories_desc_query->fields['metatags_keywords']),
                            'description' => stripslashes($categories_desc_query->fields['metatags_description']),
                            'language' => zen_get_language_icon((int)$categories_desc_query->fields['language_id']),
                            'language_id' => $categories_desc_query->fields['language_id'],
                            'removeable' => 'false'
                        );
                        $categories_desc_query->MoveNext();
                    }
                    $respond['group'] = $meta_group;
                    $respond['metatags'] = $categories_meta;
                    break;
                case 'ez-pages':
                    $ez_pages_query = $db->Execute("SELECT * FROM " . TABLE_EZPAGES . " ORDER BY pages_title");
                    $ez_pages_meta = array();
                    while (!$ez_pages_query->EOF) {
                        $ez_pages_meta[] = array(
                            'id' => $ez_pages_query->fields['pages_id'],
                            'name' => $ez_pages_query->fields['pages_title'],
                            'title' => stripslashes($ez_pages_query->fields['pages_meta_title']),
                            'keywords' => stripslashes($ez_pages_query->fields['pages_meta_keywords']),
                            'description' => stripslashes($ez_pages_query->fields['pages_meta_description']),
                            'language' => zen_get_language_icon((int)$ez_pages_query->fields['languages_id']),
                            'language_id' => $ez_pages_query->fields['languages_id'],
                            'removeable' => 'false'
                        );
                        $ez_pages_query->MoveNext();
                    }
                    $respond['group'] = $meta_group;
                    $respond['metatags'] = $ez_pages_meta;
                    break;
                default:
                    $other_meta_query = $db->Execute("SELECT * FROM " . TABLE_META_TAGS . " WHERE meta_tag_group='" . $meta_group . "'");
                    $other_meta = array();
                    while (!$other_meta_query->EOF) {
                        $page_name = $other_meta_query->fields['page'];
                        if($page_name != 'home' && $page_name != 'site_wide'){
                            $removable = 'true';
                        }
                        else{
                            $removable = 'false';
                        }
                        $other_meta[] = array(
                            'id' => $other_meta_query->fields['meta_tags_id'],
                            'name' => $page_name,
                            'title' => stripslashes($other_meta_query->fields['metatags_title']),
                            'keywords' => stripslashes($other_meta_query->fields['metatags_keywords']),
                            'description' => stripslashes($other_meta_query->fields['metatags_description']),
                            'language' => zen_get_language_icon((int)$other_meta_query->fields['language_id']),
                            'language_id' => $other_meta_query->fields['language_id'],
                            'removeable' => $removable
                        );
                        $other_meta_query->MoveNext();
                    }
                    $respond['group'] = $meta_group;
                    $respond['metatags'] = $other_meta;
                    break;
            }
    }
}
echo json_encode($respond);