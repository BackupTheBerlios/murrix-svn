<?

$count = 0;
$templates_override['scripts/show.php'][$count]['filename'] = "scripts/show_contact_group.php";
$templates_override['scripts/show.php'][$count]['match']['class'] = "contact_group";

$count++;
$templates_override['scripts/show.php'][$count]['filename'] = "scripts/show_contact_person.php";
$templates_override['scripts/show.php'][$count]['match']['class'] = "contact_person";

$count++;
$templates_override['scripts/show.php'][$count]['filename'] = "scripts/show_file.php";
$templates_override['scripts/show.php'][$count]['match']['class'] = "file";


$count = 0;
$templates_override['show_line.php'][$count]['filename'] = "show_line_comment.php";
$templates_override['show_line.php'][$count]['match']['class'] = "comment";
?>