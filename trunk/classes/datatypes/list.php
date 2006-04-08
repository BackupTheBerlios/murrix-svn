<?

$datatypes = array(	"array",
			"boolean",
			"date",
			"file",
			"hidden",
			"icon",
			"markuptext",
			"node",
			"password",
			"selection",
			"text",
			"textline",
			"thumbnail",
			"thumbnailid",
			"xhtml");

foreach ($datatypes as $datatype)
	require_once("class.mvar.$datatype.php");

?>