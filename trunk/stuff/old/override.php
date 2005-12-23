<?

$count = 0;
$templates_override['show'][$count]['filename'] = "show_contact_group.php";
$templates_override['show'][$count]['match']['class'] = "contact_group";

$count++;
$templates_override['show'][$count]['filename'] = "show_contact_person.php";
$templates_override['show'][$count]['match']['class'] = "contact_person";

$count++;
$templates_override['show'][$count]['filename'] = "show_file.php";
$templates_override['show'][$count]['match']['class'] = "file";

?>