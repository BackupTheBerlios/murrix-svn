<?

$datatypes = array(	"array",
			"boolean",
			"date",
			"time",
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
			"xhtml",
			"url");

foreach ($datatypes as $datatype)
	require_once("class.mvar.$datatype.php");

?>