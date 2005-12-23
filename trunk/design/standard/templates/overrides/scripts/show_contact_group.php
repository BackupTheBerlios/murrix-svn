<?
function DrawObject($object)
{
	global $abspath, $wwwpath;
		
	$admin = "<a href=\"#\" onClick=\"return clickreturnvalue()\" onMouseover=\"dropdownmenu(this, event, menuItems, '150px')\" onMouseout=\"delayhidemenu()\">";
	$admin .= Img(geticon("settings"));
	//$admin .= "&nbsp;Administration";
	$admin .= "</a>";
	
	$control = "<a class=\"menubar\" href=\"javascript: SwitchAllCards(true);\">".Img(geticon("down"))." Unfold all</a>";
	$control .= "&nbsp;";
	$control .= "<a class=\"menubar\" href=\"javascript: SwitchAllCards(false);\">".Img(geticon("up"))." Fold all</a>";
	
	guiTitel2(array(Img(geticon($object->GetIcon()))." ".$object->name, $control), array($object->GetValue("description"), $admin));
	
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
										?><a class="simple" href="?action=edit&path=<?=$children[$x]->GetPath()?>"><?=Img(geticon("edit"))?></a>&nbsp;<?
									}
									
									if (HasRight("delete", $children[$x]->GetPath()))
									{
										?><a class="simple" href="?action=delete&path=<?=$children[$x]->GetPath()?>" onclick="return confirmAction('Are you sure you want to delete <?=$path?>')"><?=Img(geticon("delete"))?></a>&nbsp;<?
									}
									
									if (HasRight("edit", $children[$x]->GetPath()))
									{
										?><a class="simple" href="?action=links&path=<?=$children[$x]->GetPath()?>"><?=Img(geticon("link"))?></a>&nbsp;<?
									}
									?>
									<a href="javascript:SwitchCard(<?=$x?>)"><img align="middle" src="<?=geticon("down")?>" name="img_card<?=$x?>" id="img_card<?=$x?>" border="0"></a>
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
	
	require_once(gettpl("comments.php"));
	DrawComments($object);
}

function DrawNoRights()
{
	guiTitel("No rights", "&nbsp;");
	?>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
				You do not have enough rights to view this object.
			</td>
		</tr>
	</table>
	<?
}

?>