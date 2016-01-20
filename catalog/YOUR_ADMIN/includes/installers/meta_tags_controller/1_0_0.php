<?php
/**
 *  1_0_0.php
 *
 * @package
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version Author: bislewl  1/12/2016 11:16 PM Modified in meta_tags_controller
 */

// adding meta tags controller
$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150) { // continue Zen Cart 1.5.0
    $admin_page = 'toolsMetaTagsController';
    // delete configuration menu
    $db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page . "' LIMIT 1;");
    // add configuration menu
    if (!zen_page_key_exists($admin_page)) {
        if ((int)$configuration_group_id > 0) {
            zen_register_admin_page($admin_page,
                'BOX_TOOLS_META_TAGS_CONTROLLER',
                'FILENAME_META_TAG_CONTROLLER',
                '',
                'tools',
                'Y',
                $configuration_group_id);

            $messageStack->add('Enabled Meta Tags Controller', 'success');
        }
    }
}

// adding meta tags controller
$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150) { // continue Zen Cart 1.5.0
    $admin_page = 'toolsMetaTagsControllerAjax';
    // delete configuration menu
    $db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page . "' LIMIT 1;");
    // add configuration menu
    if (!zen_page_key_exists($admin_page)) {
        if ((int)$configuration_group_id > 0) {
            zen_register_admin_page($admin_page,
                'BOX_TOOLS_META_TAGS_CONTROLLER_AJAX',
                'FILENAME_AJAX_META_TAGS_CONTROLLER',
                '',
                'tools',
                'N',
                $configuration_group_id);

            $messageStack->add('Enabled Meta Tags Controller Ajax', 'success');
        }
    }
}

if (!$sniffer->field_exists (TABLE_EZPAGES, 'pages_meta_title')) {
    $db->Execute ("ALTER TABLE " . TABLE_EZPAGES . " ADD pages_meta_title VARCHAR(255) NOT NULL DEFAULT '', ADD pages_meta_keywords text, ADD pages_meta_description text");

}

$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_META_TAGS . " (
	`meta_tags_id` int(11) NOT NULL,
	`page` varchar(255) NOT NULL DEFAULT '',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `metatags_title` varchar(255) NOT NULL DEFAULT '',
  `metatags_keywords` text,
  `metatags_description` text,
  `meta_tag_group` varchar(256)
  PRIMARY KEY (`meta_tags_id`,`language_id`));"
);


global $sniffer;
if (!$sniffer->field_exists(TABLE_META_TAGS, 'meta_tag_group')) $db->Execute("ALTER TABLE " . TABLE_META_TAGS . " ADD meta_tag_group varchar(256);");

$db->Execute("INSERT INTO ".TABLE_META_TAGS." (`page`, `language_id`, `metatags_title`, `metatags_keywords`, `metatags_description`, `meta_tag_group`) VALUES
(1, 'site_wide', 1, 'Zen Cart!', 'The Art of E-commerce', 'ecommerce, open source, shop, online shopping, store', 'site_wide'),
(2, 'home', 1, '', 'Home Meta Description', 'Meta Keywords', 'general'),
(3, 'contact_us', 1, '', NULL, NULL, 'general'),
(4, 'privacy', 1, '', NULL, NULL, 'general'),
(5, 'conditions', 1, '', NULL, NULL, 'general');");