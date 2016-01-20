<?php
/**
 *  meta_tag_controller.php
 *
 * @package
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version Author: bislewl  1/12/2016 11:10 PM Modified in meta_tags_controller
 */


require('includes/application_top.php');
$meta_tag_groups_query = $db->Execute("SELECT DISTINCT meta_tag_group FROM " . TABLE_META_TAGS . " ORDER BY meta_tag_group");
$meta_tag_groups = array();
while (!$meta_tag_groups_query->EOF) {
    $meta_tag_group_id = str_replace(' ', '-', $meta_tag_groups_query->fields['meta_tag_group']);
    $meta_tag_group_name = str_replace('_',' ',$meta_tag_groups_query->fields['meta_tag_group']);
    $meta_tag_groups[] = array(
        'id' => $meta_tag_group_id,
        'name' => ucwords($meta_tag_group_name),
    );
    $meta_tag_groups_query->MoveNext();
}

?>
    <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <style type="text/css">
            .container {
                width: 1700px;
                max-width: 90%;
                margin-top: 15px;
            }

            .tab-pane table {
                width: 100%;
            }

            input[name="page_id"], .language {
                width: 60px;
            }

        </style>

        <script language="javascript" src="includes/menu.js"></script>
        <script language="javascript" src="includes/general.js"></script>

        <script language="javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <script type="text/javascript">
            <!--
            function init() {
                cssjsmenu('navbar');
                if (document.getElementById) {
                    var kill = document.getElementById('hoverJS');
                    kill.disabled = true;
                }
                if (typeof _editor_url == "string") HTMLArea.replaceAll();
            }
            // -->
        </script>
        <script>


            function loadMetaTags(dataTarget) {
                var metaGroup = dataTarget.replace("#tabs-", "");
                $('.tab-pane').removeClass('active');
                $(dataTarget).addClass('active');
                $('#metaTagGroupTabs .active').removeClass('active');
                $(this).parent().addClass('active');
                if (metaGroup === 'add-new') {
                    return false;
                }
                // populate tab on click
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo FILENAME_AJAX_META_TAGS_CONTROLLER; ?>", //Relative or absolute path to response.php file
                    data: 'meta_action=view&group=' + metaGroup,
                    success: function (data) {
                        var tableHeadLanguage = 'Language';
                        var tableHeadName = 'Name';
                        var tableHeadTitle = 'Meta Title';
                        var tableHeadDescription = 'Meta Description';
                        var tableHeadKeywords = 'Meta Keywords';
                        if (metaGroup === 'site_wide') {
                            tableHeadName = '';
                            tableHeadTitle = 'Title';
                            tableHeadDescription = 'Site Tagline';
                            tableHeadKeywords = 'Custom Keywords';
                        }

                        var table = '';
                        $('#data-' + metaGroup).empty();
                        table = table + '<table class=".table-striped">';
                        table = table + '<tr>';
                        table = table + '<th>ID</th>';
                        table = table + '<th>' + tableHeadLanguage + '</th>';
                        table = table + '<th>' + tableHeadName + '</th>';
                        table = table + '<th>' + tableHeadTitle + '</th>';
                        table = table + '<th>' + tableHeadDescription + '</th>';
                        table = table + '<th>' + tableHeadKeywords + '</th>';
                        table = table + '</tr>';

                        $(data.metatags).each(function (index, metaTag) {
                            table = table + '<tr>';
                            table = table + '<td><input type="text" class="form-control metaTagInput-' + metaTag.id + '" name="page_id" disabled value="' + metaTag.id + '"><input type="hidden" name="page_id" class="metaTagInput-' + metaTag.id + '" value="' + metaTag.id + '"></td>';
                            table = table + '<td class="language"><input type="hidden" class="metaTagInput-' + metaTag.id + '" name="language_id" value="' + metaTag.language_id + '">' + metaTag.language + '</td>';
                            table = table + '<td><input type="text" class="form-control metaTagInput-' + metaTag.id + '" name="name" disabled value="' + metaTag.name + '"></td>';
                            table = table + '<td><input type="text" class="form-control metaTagInput-' + metaTag.id + '" name="title" value="' + metaTag.title + '"></td>';
                            table = table + '<td><textarea rows="5" class="form-control metaTagInput-' + metaTag.id + '" name="description">' + metaTag.description + '</textarea></td>';
                            table = table + '<td><textarea rows="5" class="form-control metaTagInput-' + metaTag.id + '" name="keywords">' + metaTag.keywords + '</textarea></td>';
                            table = table + '<td>';
                            table = table + '<button type="button" class="btn btn-primary" id="updateMeta' + metaTag.id + '" onclick="updateMetaTags(\'' + metaTag.id + '\',\'' + metaGroup + '\')">Update</button>';
                            if(metaTag.removeable === 'true'){
                                table = table + '<button type="button" class="btn btn-danger" onclick="deletePage(\''+metaTag.id+'\',\''+metaGroup+'\')">Delete</button>';
                            }
                            table = table + '</td>';
                            table = table + '</tr>';
                        });

                        table = table + '</table>';
                        $('#data-' + metaGroup).html(table);
                    }
                });
                return false;
            }
            function updateMetaTags(id, group) {
                var values = $('.metaTagInput-' + id).serialize();
                console.log(id + ',' + group + ',' + values);
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo FILENAME_AJAX_META_TAGS_CONTROLLER; ?>", //Relative or absolute path to response.php file
                    data: 'meta_action=save&group=' + group + '&' + values,
                    success: function (data) {

                    }
                })
            }
            function addPage() {
                var values = $('.newPage').serialize();
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo FILENAME_AJAX_META_TAGS_CONTROLLER; ?>", //Relative or absolute path to response.php file
                    data: 'meta_action=add&' + values,
                    success: function (data) {

                    }
                })
            }
            function deletePage(id, group) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo FILENAME_AJAX_META_TAGS_CONTROLLER; ?>", //Relative or absolute path to response.php file
                    data: 'meta_action=delete&id=' +id+'&group='+group ,
                    success: function (data) {

                    }
                })
            }
            $(function () {
                $('#metaTagGroupTabs a').click(function () {
                    var dataTarget = $(this).attr('data-target');
                    loadMetaTags(dataTarget);
                });
            })
        </script>
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0"
          bgcolor="#FFFFFF"
          onLoad="init()">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
    <div class="container">
        <div class="row">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">

            </div>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <ul class="nav nav-pills" id="metaTagGroupTabs">
                    <li><a data-target="#tabs-products" data-toggle="tab">Products</a></li>
                    <li><a data-target="#tabs-categories" data-toggle="tab">Categories</a></li>
                    <li><a data-target="#tabs-ez-pages" data-toggle="tab">EZ Pages</a></li>
                    <?php
                    foreach ($meta_tag_groups as $meta_tag_groups_val) {
                        ?>
                        <li><a data-target="#tabs-<?php echo $meta_tag_groups_val['id']; ?>"
                               data-toggle="tab"><?php echo $meta_tag_groups_val['name']; ?></a></li>
                        <?php
                    }
                    reset($meta_tag_groups);
                    ?>
                    <li><a data-target="#tabs-add-new" data-toggle="tab">Add Page</a></li>
                </ul>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">

            </div>
        </div>
        <div class="row">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">

            </div>
            <div class="tab-content">
                <div id="tabs-products" class="tab-pane">
                    <div id="data-products">
                    </div>
                </div>
                <div id="tabs-categories" class="tab-pane">
                    <div id="data-categories">
                    </div>
                </div>
                <div id="tabs-ez-pages" class="tab-pane">
                    <div id="data-ez-pages">
                    </div>
                </div>
                <?php
                foreach ($meta_tag_groups as $meta_tag_groups_val) {
                    ?>
                    <div id="tabs-<?php echo $meta_tag_groups_val['id']; ?>" class="tab-pane">
                        <div id="data-<?php echo $meta_tag_groups_val['id']; ?>">
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div id="tabs-add-new" class="tab-pane">
                    <hr>
                    <div id="add-new-page">
                        Page Name: <input type="text" name="new_page_name" class="form-control newPage">
                        Group: <input type="text" name="group" class="form-control newPage">
                        <input type="hidden" name="language_id" value="1" class="newPage">
                        <button class="btn btn-primary" onclick="addPage()">Add Page</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">

        </div>
    </div>


    </div>


    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
    <br/>
    </body>
    </html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>