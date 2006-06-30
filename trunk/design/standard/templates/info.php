<?
$class = "sidebar";
?>
<div class="title">
	<?=ucf(i18n("information"))?>
</div>
<div class="container">
	<?=count(getActiveUsers())?> <?=i18n("logged in user(s)")?>
	<br/>
	<?=date("Y-m-d H:i:s")?>
</div>