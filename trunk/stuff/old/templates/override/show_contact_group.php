<?
function DrawTemplate($object)
{
	global $abspath, $wwwpath;
		
	$admin = "";
	
	if (HasRight("create", $path))
	{
		$admin .= Img(guiIcon("folder"));
		$admin .= "&nbsp;";
		$admin .= "<form action=\"\" method=\"get\">";
		$admin .= "<input class=\"hidden\" type=\"hidden\" name=\"path\" value=\"$path\">";
		$admin .= "<input class=\"hidden\" type=\"hidden\" name=\"action\" value=\"new\">";
		$admin .= "<select class=\"select\" name=\"class\" onchange=\"this.form.submit();\">";
		$admin .= "<option value=\"\" selected=\"selected\">New Subobject</option>";
		
		$list = mClass::GetNameList();
		for($n = 0; $n < count($list); $n++)
			$admin .= "<option value=\"".$list[$n]."\">".ucfirst($list[$n])."</option>";
				
		$admin .= "</select>";
		$admin .= "</form>";
		$admin .= "&nbsp;";
	}
	
	if (HasRight("edit", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=edit\">";
		$admin .= Img(guiIcon("edit"));
		$admin .= "&nbsp;";
		$admin .= "Edit";
		$admin .= "</a>";
		$admin .= "&nbsp;";
	}
	
	if (HasRight("delete", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=delete\" onclick=\"return confirmAction('Are you sure you want to delete $path')\">";
		$admin .= Img(guiIcon("delete"));
		$admin .= "&nbsp;";
		$admin .= "Delete";
		$admin .= "</a>";
		$admin .= "&nbsp;";
	}
	
	if (HasRight("edit", $path))
	{
		$admin .= "&#183;";
		$admin .= "&nbsp;";
		$admin .= "<a class=\"\" href=\"?path=$path&action=links\">";
		$admin .= Img(guiIcon("link"));
		$admin .= "&nbsp;";
		$admin .= "Manage Links";
		$admin .= "</a>";
	}
	
	$control = "<a class=\"menubar\" href=\"javascript: SwitchAllCards(true);\">".Img(guiIcon("down"))." Unfold all</a>";
	$control .= "&nbsp;";
	$control .= "<a class=\"menubar\" href=\"javascript: SwitchAllCards(false);\">".Img(guiIcon("up"))." Fold all</a>";
	
	guiTitel2(array(Img(guiIcon($object->GetIcon()))." ".$object->name, $control), array($object->GetValue("description"), $admin));
	
	$cols = 3;
	$hasmap = array(array("classes", array("contact_person")),
			array("type", array("sub")),
			array("side", "bottom"),
			array("sort", array("lastname", "firstname"), true));
	$children = $object->GetRelatedHash($hasmap);
	$numperrow = ceil(count($children)/$cols);
	$width = ceil(100/$cols);
	?>
	<div id="master_cards_div">
		<table cellspacing="0" width="100%">
			<tr>
			<?
			for ($i = 0; $i < count($children); $i++)
			{
				if (($i + 1) % $cols == 0)
					echo "<td class=\"invisible\" width=\"$width%\">";
				else
					echo "<td class=\"rightline\" width=\"$width%\">";
					
					if (isset($children[$i]))
					{
						$x = $i;
						?>
						<table class="simple" cellspacing="0" width="100%">
							<tr>
								<td class="simple">
									<?
									
									$parts = explode(" ", $children[$x]->name);
									$name = $parts[1].", ".$parts[0];
									//$lastnames = $children[$x]->GetValue("lastname");
									//$lastname = $lastnames[0];
									//$firstname = $children[$x]->GetValue("firstname");
									//$name = "$lastname, $firstname";
									?>
									<a class="simple" href="?path=<?=$children[$x]->GetPath()?>"><?=$name?></a>
									<br/>
									<?
									$emails = $children[$x]->GetValue("e-mail");
									if (is_array($emails))
										$email = $emails[0];
									?>
									<a href="mailto:<?=$email?>"><?=$email?></a>
								</td>
								<td class="simple" align="right">
									<?
									if (HasRight("edit", $children[$x]->GetPath()))
									{
										?><a class="simple" href="?action=edit&path=<?=$children[$x]->GetPath()?>"><?=Img(guiIcon("edit"))?></a>&nbsp;<?
									}
									
									if (HasRight("delete", $children[$x]->GetPath()))
									{
										?><a class="simple" href="?action=delete&path=<?=$children[$x]->GetPath()?>" onclick="return confirmAction('Are you sure you want to delete <?=$path?>')"><?=Img(guiIcon("delete"))?></a>&nbsp;<?
									}
									
									if (HasRight("edit", $children[$x]->GetPath()))
									{
										?><a class="simple" href="?action=links&path=<?=$children[$x]->GetPath()?>"><?=Img(guiIcon("link"))?></a>&nbsp;<?
									}
									?>
									<a href="javascript:SwitchCard(<?=$x?>)"><img align="middle" src="<?=guiIcon("down")?>" name="img_card<?=$x?>" id="img_card<?=$x?>" border="0"></a>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="simplemain">
									<?
									$homephones = $children[$x]->GetValue("homephone");
									if (is_array($homephones))
										$homephone = $homephones[0];
									
									$mobilephones = $children[$x]->GetValue("mobilephone");
									if (is_array($mobilephones))
										$mobilephone = $mobilephones[0];
									
									if (!empty($homephone))
									{
										?><b>Tel.</b> <? echo $homephone;
										
										
										if (!empty($mobilephone))
										{
											?> <b>&#183;</b> <?
										}
									}
									
									if (!empty($mobilephone))
									{
										?><b>Mob.</b> <? echo $mobilephone;
									}
									echo "<br/>";
									
									$value = $children[$x]->GetValue("address");
									if (!empty($value))
										echo $value;
									?>
									<span class="submenu" id="card<?=$x?>" name="card<?=$x?>">
									<hr size="1">
									<?
									
									$list_used = array("e-mail", "mobilephone", "address", "homephone", "middlename", "firstname", "lastname");
									
									$vars = $children[$x]->GetVars();
									foreach ($vars as $var)
									{
										if (in_array($var->name, $list_used))
										{
											$list_used = array_diff($list_used, array($var->name));
											continue;
										}
										
										$value = $var->GetValue($children[$x]->id);
										if ($var->type == "array")
										{
											if (is_array($value) && count($value) > 0)
											{
												echo "<b>".$var->GetName().":</b> <div class=\"card_margin\">";
												foreach ($value as $line)
													echo $line."<br/>";
												echo "</div>";
											}
										}
										else if (!empty($value))
										{
											echo "<b>".$var->GetName().":</b> <div class=\"card_margin\">$value</div>";
										}
									}
									
									$hasmap = array(array("classes", array("contact_person")),
											array("type", array("partner")),
											array("sort", array("name"), true));
									$partners = $children[$x]->GetRelatedHash($hasmap);
									if (is_array($partners) && count($partners) > 0)
									{
										$partner = $partners[0];
										echo "<b>Partner:</b> <div class=\"card_margin\">";
										echo "<a href=\"?path=".$partner->GetPath()."\">";
										echo $partner->name;
										echo "</a>";
										echo "</div>";
									}
									?>
									</span>
								</td>
							</tr>
						
						</table>
						<?
					}
					
				echo "</td>";
				
				if (($i + 1) % $cols == 0)
					echo "</tr><tr>";
			}
			?>
			</tr>
		</table>
	</div>
	
	<?
	
	require_once("$abspath/design/templates/comments.php");
	DrawComments($object);
}
?>