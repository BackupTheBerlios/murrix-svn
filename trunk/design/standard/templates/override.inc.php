<?
$count = 0;
$templates_override['show_line.php'][$count]['filename'] = "show_line/show_line-comment.php";
$templates_override['show_line.php'][$count]['match']['class'] = "comment";



$count = 0;
$templates_override['small_line.php'][$count]['filename'] = "small_line/small_line-internal_link.php";
$templates_override['small_line.php'][$count]['match']['class'] = "internal_link";

$count++;
$templates_override['small_line.php'][$count]['filename'] = "small_line/small_line-link.php";
$templates_override['small_line.php'][$count]['match']['class'] = "link";
?>