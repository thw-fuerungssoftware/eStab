<?php 
/******************************************************************************\ 
              Definitionen fuer den Datenbankzugriff                              
\******************************************************************************/ 
$conf_4f_db ["server"]        = "localhost"; 
$conf_4f_db ["user"]          = "root"; 
$conf_4f_db ["password"]      = "bastian"; 
$conf_4f_db ["datenbank"]     = "estab_3mai2008"; 
$conf_4f_tbl ["prefix"]       = "nv_" ; 
$conf_4f_tbl ["benutzer"]     = "nv_benutzer"; 
$conf_4f_tbl ["nachrichten"]  = "nv_nachrichten"; 
$conf_4f_tbl ["protokoll"]    = "nv_protokoll"; 
$conf_4f_tbl ["anhang"]       = "nv_anhang"; 
$conf_4f_tbl ["usrtblprefix"] = "usr_"; 
$conf_tbl    ["bhp50"]        = "nv_bhp50"; 
$conf_tbl    ["komplan"]      = "nv_komplan"; 
$conf_tbl    ["etb"]          = "nv_etb"; 
$conf_tbl    ["tbb"]          = "nv_tbb"; 
$conf_4f     ["anschrift"]    = "EL Stab HS"; 
$conf_4f     ["hoheit"]       = "HS"; 
$usertablename11 = $conf_4f_tbl ["usrtblprefix"].strtolower ($_GET ["funktion"])."_".strtolower ( $_GET ["kuerzel"]);  $tblusername11   = $conf_4f_tbl ["usrtblprefix"].strtolower ($_SESSION["vStab_funktion"])."_".strtolower ($_SESSION["vStab_kuerzel"]);


?>