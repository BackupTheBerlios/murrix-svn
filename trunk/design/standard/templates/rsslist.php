<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>
		<meta name="robots" content="nofollow"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<link rel="alternate" type="application/rss+xml" href="rssbackend.php" title="MURRiX RSS"/>

		<link rel="shortcut icon" href="<?=geticon("murrix")?>" type="image/x-icon"/>
		<title><?=getSetting("TITLE", "Welcome to MURRiX")?></title>
		
		<?
		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";
		?>
	</head>

	<body>
		<div id="header">
			<div id="header_wrapper">
				<div id="header_name">
					RSS Feeds
				</div>
			</div>
		</div>

		<div class="clear"></div>

		<div class="rsslist">
		<?
		foreach ($args as $feed)
		{
		?>
			<a class="biglink" href="?rss&id=<?=$feed['id']?>"><?=$feed['title']?></a> - <?=$feed['description']?><br/><br/>
		<?
		}
		?>
		</div>
	</body>
</html>