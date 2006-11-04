<br/>
<?
echo compiletpl("title/medium", array("left"=>ucf(i18n("log")), "right"=>runjs(img(geticon("delete"))."&nbsp;".ucf(i18n("clear")), "Exec('','import',Hash('action','clearlog'))")));
echo $_SESSION['murrix']['system']->createZone("zone_import_log");
?>