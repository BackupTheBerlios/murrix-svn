<?
$class = "sidebar";
?>
<div class="title">
	<?=ucf(i18n("information"))?>
</div>
<div class="container">
	<?=count(getActiveUsers())?> <?=i18n("active users")?>
	<br/>
	<?=date("Y-m-d H:i:s")?>
</div>