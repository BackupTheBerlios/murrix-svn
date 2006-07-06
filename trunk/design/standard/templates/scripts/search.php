<?
echo compiletpl("title/big", array("left"=>img(geticon("search"))."&nbsp;".ucf(i18n("search"))." - $query_string"));
?>
<div class="main">
	<div class="container">
		<center>
			<form id="bigSearch" name="bigSearch" action="javascript:void(null);" onsubmit="Post('search','bigSearch')">
				<br/>
				<?=ucf(i18n("querystring"))?>
				<br/>
				<input style="width: 60%" name="query" class="form" type="text" value="<?=$query_string?>"/>
				<br/>
				<br/>
				<?=ucf(i18n("class"))?>
				<br/>
				<select class="form" name="class_name">
				<?
					echo "<option value=\"\">".ucf(i18n("all classes"))."</option>";
					$classlist = getClassList();
					foreach ($classlist as $class_name)
					{
						$selected = $class_name == $class ? "selected" : "";
						echo "<option $selected value=\"$class_name\">".ucw(str_replace("_", " ", $class_name))."</option>";
					}
				?>
				</select>
				<br/>
				<br/>
				<input class="submit" type="submit" value="<?=ucf(i18n("search"))?>"/>
			</form>
		</center>
	</div>
</div>
<?
for ($n = 0; $n < count($args['objects']); $n++)
	echo compiletpl("scripts/show/line", array(), $args['objects'][$n]);
?>