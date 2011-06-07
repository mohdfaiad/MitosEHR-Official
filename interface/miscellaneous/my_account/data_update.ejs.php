<?php
//--------------------------------------------------------------------------------------------------------------------------
// data_create.ejs.php
// v0.0.2
// Under GPLv3 License
//
// Integrated by: Ernesto Rodriguez in 2011
//
// Remember, this file is called via the Framework Store, this is the AJAX thing.
//--------------------------------------------------------------------------------------------------------------------------
session_name ( "MitosEHR" );
session_start();
session_cache_limiter('private');
include_once("../../../library/dbHelper/dbHelper.inc.php");
require_once("../../../library/phpAES/AES.class.php");
//******************************************************************************
// Reset session count 10 secs = 1 Flop
//******************************************************************************
$_SESSION['site']['flops'] = 0;
//-------------------------------------------
// password to AES and validate
//-------------------------------------------
$aes = new AES($_SESSION['site']['AESkey']);
//------------------------------------------
// Database class instance
//------------------------------------------
$mitos_db = new dbHelper();
// *************************************************************************************
// Parce the data generated by EXTJS witch is JSON
// *************************************************************************************
$data = json_decode ( $_POST['row'], true );
// *************************************************************************************
// Validate and pass the POST variables to an array
// This is the moment to validate the entered values from the user
// although Sencha EXTJS make good validation, we could check again 
// just in case 
// *************************************************************************************
$row['id'] 				  = trim($data['id']);
$row['username']          = $data['username'];
$row['password']       	  = $aes->encrypt($data['password']);
$row['abook_type']        = $data['abook_type'];
$row['title']             = $data['title'];
$row['fname']             = $data['fname'];
$row['mname']             = $data['mname'];
$row['lname']             = $data['lname'];
$row['specialty']         = $data['specialty'];
$row['organization']      = $data['organization'];
$row['valedictory']       = $data['valedictory'];
$row['street']            = $data['street'];
$row['streetb']           = $data['streetb'];
$row['city']              = $data['city'];
$row['state']             = $data['state'];
$row['zip']               = $data['zip'];
$row['street2']           = $data['street2'];
$row['streetb2']          = $data['streetb2'];
$row['city2']             = $data['city2'];
$row['state2']            = $data['state2'];
$row['zip2']              = $data['zip2'];
$row['phone']             = $data['phone'];
$row['phonew1']           = $data['phonew1'];
$row['phonew2']           = $data['phonew2'];
$row['phonecell']         = $data['phonecell'];
$row['fax']               = $data['fax'];
$row['email']             = $data['email'];
$row['assistant']         = $data['assistant'];
$row['url']               = $data['url'];
$row['upin']              = $data['upin'];
$row['npi']               = $data['npi'];
$row['federaltaxid']      = $data['federaltaxid'];
$row['taxonomy']          = $data['taxonomy'];

// *************************************************************************************
// Finally that validated POST variables is inserted to the database
// This one make the JOB of two, if it has an ID key run the UPDATE statement
// if not run the INSERT statement
// *************************************************************************************
$sql = $mitos_db->sqlBind($row, "users", "U", "id='" . $row['id'] . "'");
$mitos_db->setSQL($sql);
$ret = $mitos_db->execLog();

if ( $ret == "" ){
	echo '{ success: false, errors: { reason: "'. $ret[2] .'" }}';
} else {
	echo "{ success: true }";
}

?>