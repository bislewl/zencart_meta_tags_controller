<?php
/**
 *  1_0_2.php
 *
 * @package
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version Author: bislewl  1/28/2016 8:58 PM Modified
 */
$normal_defined_pages = array('products_new', 'contact_us', 'specials', 'reviews', 'about_us', 'shipping', 'privacy', 'conditions', 'sitemap');
foreach ($normal_defined_pages as $normal_defined_page) {
    $page_present = $db->Execute("SELECT * FROM ".TABLE_META_TAGS." WHERE page='".$normal_defined_page."'");
    if($page_present->RecordCount() == 0) {
        if (defined('META_TAG_DESCRIPTION_' . strtoupper($normal_defined_page))) {
            $meta_description = addslashes(constant('META_TAG_DESCRIPTION_' . strtoupper($normal_defined_page)));
        } else {
            $meta_description = '';
        }
        if (defined('META_TAG_KEYWORDS_PAGE_' . strtoupper($normal_defined_page))) {
            $meta_keywords = addslashes(constant('META_TAG_KEYWORDS_PAGE_' . strtoupper($normal_defined_page)));
        } else {
            $meta_keywords = '';
        }
        if (defined('META_TAG_TITLE_PAGE_' . strtoupper($normal_defined_page))) {
            $meta_title = addslashes(constant('META_TAG_TITLE_PAGE_' . strtoupper($normal_defined_page)));
        } else {
            $meta_title = '';
        }

        $db->Execute("REPLACE INTO " . TABLE_META_TAGS . " (`page`, `language_id`, `metatags_title`, `metatags_keywords`, `metatags_description`, `meta_tag_group`) VALUES
    ('" . $normal_defined_page . "','1','" . $meta_title . "','" . $meta_keywords . "','" . $meta_description . "','general')");
    }
}

$ez_pages = $db->Execute("SELECT * FROM " . TABLE_EZPAGES);
while (!$ez_pages->EOF) {
    $ez_page_id = $ez_pages->fields['pages_id'];
    if (defined('META_TAG_DESCRIPTION_EZPAGE_' . $ez_page_id) && $ez_pages->fields['pages_meta_description'] == '') {
        $meta_description = addslashes(constant('META_TAG_DESCRIPTION_EZPAGE_' . $ez_page_id));
    } else {
        $meta_description = '';
    }
    if (defined('META_TAG_KEYWORDS_EZPAGE_' . $ez_page_id) && $ez_pages->fields['pages_meta_keywords'] == '') {
        $meta_keywords = addslashes(constant('META_TAG_KEYWORDS_EZPAGE_' . $ez_page_id));
    } else {
        $meta_keywords = '';
    }
    if (defined('META_TAG_TITLE_EZPAGE_' . $ez_page_id) && $ez_pages->fields['pages_meta_title'] == '') {
        $meta_title = addslashes(constant('META_TAG_TITLE_EZPAGE_' . $ez_page_id));
    } else {
        $meta_title = '';
    }
    $db->Execute($sql = "UPDATE " . TABLE_EZPAGES . " SET pages_meta_title='" . $meta_title . "', pages_meta_keywords='" . $meta_keywords . "', pages_meta_description='" . $meta_description . "' WHERE pages_id='" . $ez_page_id . "' AND languages_id='1'");
    $ez_pages->MoveNext();
}