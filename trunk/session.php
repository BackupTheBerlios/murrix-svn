<?
session_name("MURRiX");

// 1400 minutes is 24 hours 

session_cache_expire(1440);
session_set_cookie_params(60*1440);
session_start();
?>