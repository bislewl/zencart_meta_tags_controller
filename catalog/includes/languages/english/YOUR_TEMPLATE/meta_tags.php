<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: meta_tags.php 18697 2011-05-04 14:35:20Z wilt $
 * Modified by Anne (Picaflor-Azul.com) Winchester Responsive v1.0
 */


$meta_tags_home = $db->Execute("SELECT * FROM " . TABLE_META_TAGS . " WHERE page='home' AND language_id='" . (int)$_SESSION['languages_id'] . "'");
if ($meta_tags_home->RecordCount() > 0) {
    define('HOME_PAGE_TITLE', $meta_tags_home->fields['metatags_title']);
    define('HOME_PAGE_META_DESCRIPTION', $meta_tags_home->fields['metatags_description']);
    define('HOME_PAGE_META_KEYWORDS', $meta_tags_home->fields['metatags_keywords']);
} else {
    // Home Page Only:
    define('HOME_PAGE_META_DESCRIPTION', 'Home Meta Description');
    define('HOME_PAGE_META_KEYWORDS', 'Meta Keywords');

    // NOTE: If HOME_PAGE_TITLE is left blank (default) then TITLE and SITE_TAGLINE will be used instead.
    define('HOME_PAGE_TITLE', ''); // usually best left blank
}
$meta_tags_sitewide = $db->Execute("SELECT * FROM " . TABLE_META_TAGS . " WHERE page='site_wide' AND language_id='" . (int)$_SESSION['languages_id'] . "'");
if ($meta_tags_sitewide->RecordCount() > 0) {
    define('TITLE', $meta_tags_sitewide->fields['metatags_title']);
    define('SITE_TAGLINE', $meta_tags_sitewide->fields['metatags_description']);
    define('CUSTOM_KEYWORDS', $meta_tags_sitewide->fields['metatags_keywords']);
} else {
    // page title
    define('TITLE', 'Zen Cart!');
// Site Tagline
    define('SITE_TAGLINE', 'The Art of E-commerce');
// Custom Keywords
    define('CUSTOM_KEYWORDS', 'ecommerce, open source, shop, online shopping, store');
}


// BELOW SHOULD NOT NEED TO BE MODIFIED


// EZ-Pages meta-tags.  Follow this pattern for all ez-pages for which you desire custom metatags. Replace the # with ezpage id.
// If you wish to use defaults for any of the 3 items for a given page, simply do not define it.
// (ie: the Title tag is best not set, so that site-wide defaults can be used.)
// repeat pattern as necessary
define('META_TAG_DESCRIPTION_EZPAGE_#', '');
define('META_TAG_KEYWORDS_EZPAGE_#', '');
define('META_TAG_TITLE_EZPAGE_#', '');

// Per-Page meta-tags. Follow this pattern for individual pages you wish to override. This is useful mainly for additional pages.
// replace "page_name" with the UPPERCASE name of your main_page= value, such as ABOUT_US or SHIPPINGINFO etc.
// repeat pattern as necessary
define('META_TAG_DESCRIPTION_page_name', '');
define('META_TAG_KEYWORDS_page_name', '');
define('META_TAG_TITLE_page_name', '');

// Review Page can have a lead in:
define('META_TAGS_REVIEW', 'Reviews: ');

// separators for meta tag definitions
// Define Primary Section Output
define('PRIMARY_SECTION', ' : ');

// Define Secondary Section Output
define('SECONDARY_SECTION', ' - ');

// Define Tertiary Section Output
define('TERTIARY_SECTION', ', ');

// Define divider ... usually just a space or a comma plus a space
define('METATAGS_DIVIDER', ' ');

// Define which pages to tell robots/spiders not to index
// This is generally used for account-management pages or typical SSL pages, and usually doesn't need to be touched.
define('ROBOTS_PAGES_TO_SKIP', 'login,logoff,create_account,account,account_edit,account_history,account_history_info,account_newsletters,account_notifications,account_password,address_book,advanced_search,advanced_search_result,checkout_success,checkout_process,checkout_shipping,checkout_payment,checkout_confirmation,cookie_usage,create_account_success,contact_us,download,download_timeout,customers_authorization,down_for_maintenance,password_forgotten,time_out,unsubscribe,info_shopping_cart,popup_image,popup_image_additional,product_reviews_write,ssl_check,shopping_cart');


// favicon setting
// There is usually NO need to enable this unless you need to specify a path and/or a different filename
define('FAVICON', 'favicon.ico');

