<?
$count = 0;
$templates_override['show_line.php'][$count]['filename'] = "show_line/show_line-comment.php";
$templates_override['show_line.php'][$count]['match']['class'] = "comment";

$count++;
$templates_override['show_line.php'][$count]['filename'] = "show_line/show_line-file.php";
$templates_override['show_line.php'][$count]['match']['class'] = "file";

$count++;
$templates_override['show_line.php'][$count]['filename'] = "show_line/show_line-contact.php";
$templates_override['show_line.php'][$count]['match']['class'] = "contact";




$count = 0;
$templates_override['small_line.php'][$count]['filename'] = "small_line/small_line-internal_link.php";
$templates_override['small_line.php'][$count]['match']['class'] = "internal_link";

$count++;
$templates_override['small_line.php'][$count]['filename'] = "small_line/small_line-link.php";
$templates_override['small_line.php'][$count]['match']['class'] = "link";




$count = 0;
$templates_override['show_item.php'][$count]['filename'] = "show_item/show_item-file.php";
$templates_override['show_item.php'][$count]['match']['class'] = "file";

$count++;
$templates_override['show_item.php'][$count]['filename'] = "show_item/show_item-contact.php";
$templates_override['show_item.php'][$count]['match']['class'] = "contact";


/*

$count = 0;
$templates_override['data_show.php'][$count]['filename'] = "data_show/data_show-file.php";
$templates_override['data_show.php'][$count]['match']['class'] = "file";

$count++;
$templates_override['data_show.php'][$count]['filename'] = "data_show/data_show-article.php";
$templates_override['data_show.php'][$count]['match']['class'] = "article";
*/
?>