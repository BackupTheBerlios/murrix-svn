<?
function DrawObject($object)
{
	global $abspath, $wwwpath;
	
	$admin = "<a href=\"#\" onClick=\"return clickreturnvalue()\" onMouseover=\"dropdownmenu(this, event, menuItems, '150px')\" onMouseout=\"delayhidemenu()\">";
	$admin .= Img(geticon("settings"));
	//$admin .= "&nbsp;Administration";
	$admin .= "</a>";
	
	guiTitel2(array(Img(geticon($object->GetIcon()))." ".$object->name, ""), array("", $admin));
	
	$hasmap = array(array("classes", array("event")),
			array("type", array("sub")),
			array("side", "bottom"),
			array("sort", array("date")));
	$events = mObject::GetRelatedWithRights($object->GetRelatedHash($hasmap));
	?>
	
	<div id="contact_person">
		<table cellspacing="0">
			<tr>
				<td id="left">
					<div id="frame">
						<div id="image">
						<?
							$thumbnail_id = $object->GetValue("thumbnail");
							
							if ($thumbnail_id > 0)
							{
								$thumbnail = new mThumbnail($thumbnail_id);
								$thumbnail->Show();
								echo "<br/>";
							}
							
							$gender = $object->GetValue("gender", true);
							echo ucfirst($gender);
						?>
						</div>
						
						
						<b>Name(s):</b>
						<div id="data">
						<?
							$lastnames = $object->GetValue("lastname");
							foreach ($lastnames as $lastname)
							{
								$middlename = "";
								$middlenames = $object->GetValue("middlename");
								for ($n = 0; $n < count($middlenames); $n++)
									$middlename .= $middlenames[$n]." ";
									
								echo $object->GetValue("firstname")." $middlename$lastname<br/>";
							}
						?>
						</div>
						<br/>
						<?
						
						$item = $object->GetValue("nickname");
						if (!empty($item))
						{
							?>
							<b>Nickname:</b>
							<div id="data">
								<?=$item?>
							</div>
							<br/>
						<?
						}
						
						$birthevent = null;
						$deathevent = null;
						
						for ($n = 0; $n < count($events); $n++)
						{
							if (strtolower($events[$n]->name) == "birth")
								$birthevent = $events[$n];
							else if (strtolower($events[$n]->name) == "death")
								$deathevent = $events[$n];
						}
						
						if ($birthevent != null)
						{
							$age = GetAge($birthevent, $deathevent);
							?>
							<b>Age:</b>
							<div id="data"><?=$age?></div>
							<br/>
							<?
						}
						
						$items = $object->GetValue("e-mail");
						if (!empty($items))
						{
							?>
							<b>E-Mail(s):</b>
							<div id="data">
							<?
								foreach ($items as $item)
									echo "<a href=\"mailto:$item\">$item</a><br/>";
							?>
							</div>
							<br/>
						<?
						}
						
						$items = $object->GetValue("homephone");
						if (!empty($items))
						{
							?>
							<b>Homephone(s):</b>
							<div id="data">
							<?
								foreach ($items as $item)
									echo "$item<br/>";
							?>
							</div>
							<br/>
						<?
						}
						
						$items = $object->GetValue("mobilephone");
						if (!empty($items))
						{
							?>
							<b>Mobilephone(s):</b>
							<div id="data">
							<?
								foreach ($items as $item)
									echo "$item<br/>";
							?>
							</div>
							<br/>
						<?
						}
						
						
						$items = $object->GetValue("workphone");
						if (!empty($items))
						{
							?>
							<b>Workphone(s):</b>
							<div id="data">
							<?
								foreach ($items as $item)
									echo "$item<br/>";
							?>
							</div>
							<br/>
						<?
						}
						
						$item = $object->GetValue("address");
						if (!empty($item))
						{
							?>
							<b>Address:</b>
							<div id="data">
								<?=$item?>
							</div>
							<br/>
						<?
						}
						
						$icq = $object->GetValue("icq");
						$msn = $object->GetValue("msn");
						if (!empty($icq) || !empty($msn))
						{
							?>
							<b>Instant Messaging:</b>
							<div id="data">
							<?
								if (!empty($icq))
									echo "<i>ICQ:</i> $icq<br/>";
									//<img align=\"middle\" src=\"http://status.icq.com/online.gif?icq=$icq&img=5\">&nbsp;
								if (!empty($msn))
									echo "<i>MSN:</i> $msn<br/>";
							?>
							</div>
							<br/>
						<?
						}
						
						$item = $object->GetValue("allergy");
						if (!empty($item))
						{
							?>
							<b>Allergy(s):</b>
							<div id="data">
								<?=$item?>
							</div>
							<br/>
						<?
						}
						
						$item = $object->GetValue("comment");
						if (!empty($item))
						{
							?>
							<b>Comment(s):</b>
							<div id="data">
								<?=$item?>
							</div>
							<br/>
						<?
						}
						?>
					</div>
				</td>
				<td id="right">
					<?
					$hasmap = array(array("classes", array("contact_person")),
							array("type", array("child")),
							array("side", "top"),
							array("sort", array("gender")));
					$parents = mObject::GetRelatedWithRights($object->GetRelatedHash($hasmap));
					
					$hasmap = array(array("classes", array("contact_person")),
							array("type", array("child")),
							array("side", "bottom"),
							array("sort", array("birth")));
					$children = mObject::GetRelatedWithRights($object->GetRelatedHash($hasmap));
					
					if (!empty($parents) || !empty($children))
					{
					?>
						<div id="frame">
							<div id="title">
								Family Tree
							</div>
							<div id="main">
							<?
								$mother = null;
								$father = null;
								
								foreach ($parents as $parent)
								{
									$gender = $parent->GetValue("gender", true);
									if ($gender == "f")
										$mother = $parent;
									else if ($gender == "m")
										$father = $parent;
								}
								?>
								<table width="100%">
									<tr>
										<td id="left" width="50%">
											<?=CreateTreePerson($mother)?>
										</td>
										<td id="right" width="50%">
											<?=CreateTreePerson($father)?>
										</td>
		
									</tr>
									<tr>
										<td colspan="2" id="left" align="center">
											<br/>
											<?=$object->name?>
										</td>
									</tr>
									<?
									foreach ($children as $child)
									{
									?>
										<tr>
											<td colspan="2" id="left" align="center">
												<br/>
												<?=CreateTreePerson($child)?>
											</td>
										</tr>
									<?
									}
									?>
								</table>
							</div>
						</div>
					<?
					}
					
					if (count($events) > 0)
					{
					?>
						<div id="frame">
							<div id="title">
								Timeline
							</div>
							<div id="master_cards_div">
							<?
								$x = 0;
								foreach ($events as $event)
								{
									$description = $event->GetValue("description");
									?>
									<table id="event" cellspacing="0">
										<tr>
											<td id="left">
												<div id="main">
													<a class="title" href="?path=<?=$event->GetPath()?>"><?=Img(geticon($event->GetIcon()))?>&nbsp;<?=$event->name?></a>
													
													<?
													$comment = $event->GetValue("comment");
													if (!empty($comment))
														echo " $comment";
													?>
												</div>
											</td>
											<td id="right">
												<div id="main">
													<?=$event->GetValue("date")?>
												</div>
											</td>
										</tr>
										<tr>
											<td id="left">
												<div id="main">
													<?
													
													$hasmap = array(array("classes", array("contact_person")),
															array("type", array("sub")),
															array("side", "top"),
															array("sort", array("name")));
													$persons = mObject::GetRelatedWithRights($event->GetRelatedHash($hasmap));
													if (count($persons) > 0)
													{
														foreach ($persons as $person)
														{
															if ($person->id == $object->id)
																continue;
																
															?>
															<a href="?path=<?=$person->GetPath()?>"><?=$person->name?></a>
															<?
														}
													}
													?>
												</div>
											</td>
											<td id="right">
												<div id="main">
													<?
													if (!empty($description))
													{
													?>
														<a href="javascript:SwitchCard(<?=$x?>)"><img align="middle" src="<?=geticon("down")?>" name="img_card<?=$x?>" id="img_card<?=$x?>" border="0"></a>
													<?
													}
													?>
												</div>
											</td>
										</tr>
										<?
										if (!empty($description))
										{
										?>
										<tr>
											<td colspan="2" id="left">
												<span class="submenu" id="card<?=$x?>" name="card<?=$x?>">
													<hr size="1" color="#ADADFF">
													<?=$description?>
												</span>
											</td>
										</tr>
										<?
										}
										?>
									</table>
									<?
									$x++;
								}
							?>
							</div>
						</div>
					<?
					}
					?>
				</td>
			</tr>
		</table>
	</div>
	<?
	
	require_once(gettpl("comments.php"));
	DrawComments($object);
}

function GetAge($birth, $death = null)
{
	$birthdate = $birth->GetValue("date");
	$birthyear = substr($birthdate, 0, 4);
	$birthmonth = substr($birthdate, 5, 2);
	$birthday = substr($birthdate, 8, 10);
	
	if ($death != null)
	{
		$deathdate = $death->GetValue("date");
		$year = substr($deathdate, 0, 4);
		$month = substr($deathdate, 5, 2);
		$day = substr($deathdate, 8, 10);
	}
	else
	{
		$year = date("Y", strtotime("now"));
		$month = date("m", strtotime("now"));
		$day = date("d", strtotime("now"));
	}
	
	$age = $year - $birthyear;
	
	if ($month < $birthmonth || ($month == $birthmonth && $day <= $birthday))
		$age--;

	return $age;
}

function CreateTreePerson($person)
{
	$html = "<table class=\"image-border\" width=\"100%\">";
	$html .= "	<tr>";
	$html .= "		<td id=\"left\" align=\"center\">";
	if ($person != null)
		$html .= "			<a href=\"?path=".$person->GetPath()."\">$person->name</a>";
	else
		$html .= "			Unknown";
	$html .= "		</td>";
	$html .= "	</tr>";
	$html .= "	<tr>";
	$html .= "		<td id=\"left\" align=\"center\">";
	$html .= "			<b>b. </b> birthdate";
	$html .= "		</td>";
	$html .= "	</tr>";
	$html .= "	<tr>";
	$html .= "		<td id=\"left\" align=\"center\">";
	$html .= "			<b>d. </b> deathdate";
	$html .= "		</td>";
	$html .= "	</tr>";
	$html .= "</table>";
	return $html;
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