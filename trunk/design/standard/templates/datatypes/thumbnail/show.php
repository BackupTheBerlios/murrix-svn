<?
$thumbnail = new mThumbnail($args['value']);
$thumbnail->Show();

$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;
?>