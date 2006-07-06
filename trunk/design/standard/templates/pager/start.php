<?
$num_per_page_db = $object->getMeta($pagername."_num_per_page", 25);
$num_per_page = $num_per_page_db == "all" ? count($children) : $num_per_page_db;
$page_num = isset($args[$pagername.'_page']) ? $args[$pagername.'_page'] : 1;
$num_pages = ceil(count($children)/$num_per_page);

if ($page_num <= 0)
	$page_num = 1;
else if ($page_num > $num_pages)
	$page_num = $num_pages;

$start = ($page_num-1)*$num_per_page;
$end = $page_num*$num_per_page;

if ($end > count($children))
	$end = count($children);
?>
