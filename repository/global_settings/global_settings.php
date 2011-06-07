<?php
session_name ( "MitosEHR" );
session_start();
session_cache_limiter('private');

include_once("../../library/dbHelper/dbHelper.inc.php");
//--------------------------------------------------------------------------------------
// Database class instance
//--------------------------------------------------------------------------------------
$mitos_db = new dbHelper();
//--------------------------------------------------------------------------------------
// Lets pull the data from globals table and settings the $_SESSION['global_settings'] values
//--------------------------------------------------------------------------------------
$mitos_db->setSQL("SELECT gl_name, gl_value FROM globals");
foreach($mitos_db->execStatement() as $setting){
    $_SESSION['global_settings'][$setting['gl_name']] = $setting['gl_value'];
}
?>