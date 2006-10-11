<?

/* Add default links */
addLinkType("sub", "sub");
addLinkType("data", "data");
addLinkType("link", "link");
addLinkType("parent", "parent");
addLinkType("partner", "partner");
addLinkType("birth", "birth");
addLinkType("death", "death");

function addLinkType($name, $link_type)
{
	$_SESSION['murrix']['link_types'][$link_type] = $name;
}

function getLinkTypes()
{
	return $_SESSION['murrix']['link_types'];
}

?>