<?
global $root_id;
$root = new mObject($root_id);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>
		<meta name="robots" content="nofollow"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<link rel="alternate" type="application/rss+xml" href="rssbackend.php" title="MURRiX RSS"/>

		<link rel="shortcut icon" href="<?=geticon($root->getIcon())?>" type="image/x-icon"/>
		<title><?=$root->getVarValue("description")?></title>
		
		<?
		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";
		?>
	</head>

	<body>
	<?
		if (is_array($args))
		{
		?>
			<div id="header">
				<div id="header_wrapper">
					<div id="header_logo">
						<?=img(geticon($root->getIcon(), 64))?>
					</div>
					
					<div id="header_name">
						<?=$root->getVarValue("description")?>
					</div>
				</div>
			</div>
	
			<div class="clear"></div>
	
			<div class="rsslist">
			<?
			foreach ($args as $feed)
			{
			?>
				<a class="biglink" href="rssbackend.php?id=<?=$feed['id']?>"><?=$feed['title']?></a> - <?=$feed['description']?><br/>
			<?
			}
			?>
			</div>
			<?
		}
		else
		{
		?>
			<div id="content">
			<?
			$object = $args;
			include(gettpl("scripts/show", $args));
			?>
			</div>
		<?
		}
	?>
	</body>
</html>

