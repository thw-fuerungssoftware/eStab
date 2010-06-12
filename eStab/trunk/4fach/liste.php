<?php

include ("katego.php");
/*****************************************************************************\
   Datei: liste.php

   ben�tigte Dateien:

   Beschreibung:



   (C) Hajo Landmesser IuK Kreis Heinsberg
   mailto://hajo.landmesser@iuk-heinsberg.de
\*****************************************************************************/

class Listen extends kategorien {
/******************************************************************************\
   $welche ~= Art der Liste die Ausggeben werden soll. M�glich sind:
     FMA    - Fernmeldeausgangsliste
     STUSER - Stabbenutzer
     STSI   - Stab Sichter
     FMNWE  - Fernmelde Nachweis Eingang
     FMNWA  - Fernmelde Nachweis Ausgang
     ADMIN  - Administrative Liste
\******************************************************************************/

  var $listenart;
  var $benutzer;
  var $flt_status;
  var $flt_msg_pro_seite ;
  var $flt_start_msg;
  var $flt_gelesen ;
  var $flt_erledigt;


  // Listengestaltung

/******************************************************************************\

\******************************************************************************/


  function explodereceiver ( $empf){
    $receiver = explode (",",$empf);
    for ($i=0; $i < count( $receiver ); $i++ ) {
      $hilfeaus = explode ( "_", $receiver [$i] ) ;
      $fktcopycolor[$hilfeaus[0]] = $hilfeaus [1] ;
    }
    return $fktcopycolor;
  }



/******************************************************************************\

\******************************************************************************/
  function listen ($welche, $user){
    $this->listenart = $welche;
    $this->benutzer  = $user;

    $this->flt_status        = $_SESSION["filter_darstellung"] ;
    $this->flt_msg_pro_seite = $_SESSION["filter_anzahl"] ;
    $this->flt_start_msg     = $_SESSION["startmit"];
    $this->flt_gelesen       = $_SESSION["gelesene"] ;
    $this->flt_erledigt      = $_SESSION["erledigte"] ;

//    echo "listenart =".$this->listenart."- benutzer = ".$this->benutzer."<br>";
  }

/******************************************************************************\

\******************************************************************************/
  function darstellungs_art ( $what ){

    include ("../config.inc.php");

    switch ($this->listenart){
      /*************************************************************************\
                               FFFFF M   M  AAA
                               F     MM MM A   A
                               FFF   M M M AAAAA
                               F     M   M A   A
                               F     M   M A   A
      \*************************************************************************/
      case "FMA":           /***** F M A ****/
      break;

      /*************************************************************************\
        SSSSS  TTTTT   AAA  BBBB   l
        S        T    A   A B   B  l
        SSSSS    T    AAAAA BBBBB  l esen
            S    T    A   A B   B  l
        SSSSS    T    A   A BBBB   l
      \*************************************************************************/
      case "Stab_lesen":  // ******  S T A B    l e s e n *****
        if ( debug ) { echo "\n\n\n<!-- ANFANG file:liste.php fkt:darstellungsart -->"; }

        echo "\n<form action=\"".$conf_4f ["MainURL"]."\" method=\"get\" target=\"mainframe\">\n";
//        echo "<fieldset>\n";
        echo "<table><tbody>";
        echo "<tr>";
        echo "<td>";
        if ( !(isset ($_SESSION["filter_anzahl"])) OR
              ( $_SESSION["filter_anzahl"] == "" )
           ){$_SESSION["filter_anzahl"] == 5;}
        if ($_SESSION [filter_darstellung] == 0)  {
          echo "<input name=\"filter_darstellung\" type=\"checkbox\">\n";
          echo "filtern" ;
        } else {
          echo "<input name=\"filter_darstellung\" type=\"checkbox\" checked=\"checked\">\n";

          echo "filtern" ;
          echo "</td>";
          echo "<td>";
          echo "<big><b>".($_SESSION[filter_start]+1)."|".($_SESSION[filter_start]+$_SESSION[filter_anzahl])."|<big>".($_SESSION["filter_rescount"])."</big></b></big>";
          echo "</td>";
          echo "<td>";
          echo "Meldung/Seite:\n";
          echo "</td>";
          echo "<td>";
          echo "<select size=\"1\" name=\"filter_anzahl\">";

          if ( ( $_SESSION["filter_anzahl"] == "5"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>5</option>";

          if ( ( $_SESSION["filter_anzahl"] == "10"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>10</option>";

          if ( ( $_SESSION["filter_anzahl"] == "15"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>15</option>";

          if ( ( $_SESSION["filter_anzahl"] == "20"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>20</option>";

          if ( ( $_SESSION["filter_anzahl"] == "25"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>25</option>";
          echo "</select>";
          echo "</td>";

          echo "<td>";
          echo "&nbsp;&nbsp;&nbsp;";
          echo "<input type=\"image\" name=\"flt_start\" src=\"".$conf_design_path."/102.gif\" alt=\"anfang\">\n";
          echo "<input type=\"image\" name=\"flt_back\" src=\"".$conf_design_path."/101.gif\" alt=\"zurueck\">\n";
          echo "<input type=\"image\" name=\"flt_for\" src=\"".$conf_design_path."/104.gif\" alt=\"vor\">\n";
          echo "<input type=\"image\" name=\"flt_end\" src=\"".$conf_design_path."/103.gif\" alt=\"ende\">\n";
          echo "</td>";
          //echo "&nbsp;&nbsp;&nbsp;";
  /*
          echo "<td>";
          if ($_SESSION [filter_gelesen] == 0)  {
            echo "<input name=\"filter_gelesen\" type=\"checkbox\">\n";
          } else {
            echo "<input name=\"filter_gelesen\" type=\"checkbox\" checked=\"checked\">\n";
          }
          echo "gelesene&nbsp;\n";
          echo "</td>";
  */

          echo "<td>";

          if ($_SESSION [filter_erledigt] == 0)  {
            echo "<input name=\"filter_erledigt\" type=\"checkbox\">\n";
          } else {
            echo "<input name=\"filter_erledigt\" type=\"checkbox\" checked=\"checked\">\n";
          }
          echo "erledigte&nbsp;\n";
          echo "</td>";
        }
        echo "<td>";
        echo "<input name=\"filter_submit\" value=\"einstellen\" type=\"submit\">\n";
        echo "</td>";
        echo "</form>";
        echo "\n<form action=\"".$conf_4f ["MainURL"]."\" method=\"get\" target=\"mainframe\">\n";
        echo "<td>";
        if (isset ($_SESSION ["flt_search"]) ) { $defvalue = $_SESSION ["flt_search"] ;}
        else {$defvalue = "";}
        echo "<p>Suchbegriff: <input name=\"flt_search\" value=\"".$defvalue."\" type=\"text\" size=\"30\" maxlength=\"30\"></p>";
        echo "</td>";
        echo "<td>";
        echo "<input name=\"filter_suche\" value=\"suchen\" type=\"submit\">\n";
        echo "</td>";

        echo "<td>";
        echo "<input name=\"filter_suche_reset\" value=\"reset\" type=\"submit\">\n";
        echo "</td>";

        echo "</tr>";
        echo "</tbody></table>";
//        echo "</fieldset>\n";
      break;


      case "Stab_sichten":   /*********** S t a b   s i c h t e n ************/
      break;
      /*************************************************************************\
               SSSSS III  AAA  DDDD  M   M III N   N
               S      I  A   A D   D MM MM  I  NN  N
               SSSSS  I  AAAAA D   D M M M  I  N N N
                   S  I  A   A D   D M   M  I  N  NN
               SSSSS III A   A DDDD  M   M III N   N
      \*************************************************************************/
      case "SIADMIN":  // ***************  SICHTER ADMINISTRATOR  *********************
      case "FMADMIN":
        if ( debug ) { echo "\n\n\n<!--  210 file:liste.php fkt:darstellungsart -->"; }

        echo "\n<form action=\"".$conf_4f ["MainURL"]."\" method=\"get\" target=\"mainframe\">\n";
//        echo "<fieldset>\n";
        echo "<table><tbody>";
        echo "<tr>";
        echo "<td>";
        if ( !(isset ($_SESSION["filter_anzahl"])) OR
              ( $_SESSION["filter_anzahl"] == "" )
           ){$_SESSION["filter_anzahl"] == 5;}
        if ($_SESSION [filter_darstellung] == 0)  {
          echo "<input name=\"filter_darstellung\" type=\"checkbox\">\n";
          echo "filtern" ;
        } else {
          echo "<input name=\"filter_darstellung\" type=\"checkbox\" checked=\"checked\">\n";

          echo "filtern" ;
          echo "</td>";
          echo "<td>";
          echo "<big><b>".($_SESSION[filter_start]+1)."|".($_SESSION[filter_start]+$_SESSION[filter_anzahl])."|<big>".($_SESSION["filter_rescount"])."</big></b></big>";
          echo "</td>";
          echo "<td>";
          echo "Meldung/Seite:\n";
          echo "</td>";
          echo "<td>";
          echo "<select size=\"1\" name=\"filter_anzahl\">";

          if ( ( $_SESSION["filter_anzahl"] == "5"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>5</option>";

          if ( ( $_SESSION["filter_anzahl"] == "10"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>10</option>";

          if ( ( $_SESSION["filter_anzahl"] == "15"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>15</option>";

          if ( ( $_SESSION["filter_anzahl"] == "20"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>20</option>";

          if ( ( $_SESSION["filter_anzahl"] == "25"))
            {$sel = "selected";} else {$sel = "";}
          echo "<option $sel>25</option>";
          echo "</select>";
          echo "</td>";

          echo "<td>";
          echo "&nbsp;&nbsp;&nbsp;";
          echo "<input type=\"image\" name=\"flt_start\" src=\"".$conf_design_path."/102.gif\" alt=\"anfang\">\n";
          echo "<input type=\"image\" name=\"flt_back\" src=\"".$conf_design_path."/101.gif\" alt=\"zurueck\">\n";
          echo "<input type=\"image\" name=\"flt_for\" src=\"".$conf_design_path."/104.gif\" alt=\"vor\">\n";
          echo "<input type=\"image\" name=\"flt_end\" src=\"".$conf_design_path."/103.gif\" alt=\"ende\">\n";
          echo "</td>";
          //echo "&nbsp;&nbsp;&nbsp;";
  /*
          echo "<td>";
          if ($_SESSION [filter_gelesen] == 0)  {
            echo "<input name=\"filter_gelesen\" type=\"checkbox\">\n";
          } else {
            echo "<input name=\"filter_gelesen\" type=\"checkbox\" checked=\"checked\">\n";
          }
          echo "gelesene&nbsp;\n";
          echo "</td>";
  */

          echo "<td>";

          if ($_SESSION [filter_erledigt] == 0)  {
            echo "<input name=\"filter_erledigt\" type=\"checkbox\">\n";
          } else {
            echo "<input name=\"filter_erledigt\" type=\"checkbox\" checked=\"checked\">\n";
          }
          echo "erledigte&nbsp;\n";
          echo "</td>";
        }
        echo "<td>";
        echo "<input name=\"filter_submit\" value=\"einstellen\" type=\"submit\">\n";
        echo "</td>";
        echo "</form>";
        echo "\n<form action=\"".$conf_4f ["MainURL"]."\" method=\"get\" target=\"mainframe\">\n";
        echo "<td>";
        if (isset ($_SESSION ["flt_search"]) ) { $defvalue = $_SESSION ["flt_search"] ;}
        else {$defvalue = "";}
        echo "<p>Suchbegriff: <input name=\"flt_search\" value=\"".$defvalue."\" type=\"text\" size=\"30\" maxlength=\"30\"></p>";
        echo "</td>";
        echo "<td>";
        echo "<input name=\"filter_suche\" value=\"suchen\" type=\"submit\">\n";
        echo "</td>";

        echo "<td>";
        echo "<input name=\"filter_suche_reset\" value=\"reset\" type=\"submit\">\n";
        echo "</td>";

        echo "</tr>";
        echo "</tbody></table>";
//        echo "</fieldset>\n";

      break;


    }


    echo "</form>\n";

    if ( debug ) { echo "<!-- ENDE file:liste.php fkt:darstellungsart -->\n"; }
  }

/******************************************************************************\
  Funktion:  kategoliste
SELECT * FROM `nv_nachrichten` WHERE `00_lfd` IN

(SELECT msg FROM `nv_masterkategolink` WHERE `katego` = (

SELECT lfd FROM `nv_masterkatego` WHERE `kategorie` = "2m"));

\******************************************************************************/

  var $db_server;
  var $db_benutzer;
  var $db_passwort;
  var $db_name ;
  var $db_tablname ;
  var $db_tablnamelk;

  var $db_master_katego ;

  var $sqlquery;
  var $db_hndl ;
  var $masterresult ;
  var $userresult ;
  var $resultcount ;

  var $redcopy2 ;
  var $dbtyp ;

  var $grundkatego;

  function set_katego_para ($table){

    include ("../config.inc.php");
    include ("../dbcfg.inc.php");
    include ("../e_cfg.inc.php");
    include ("../fkt_rolle.inc.php");
    $this->redcopy2  = $redcopy2 ;

    if (!isset ($_SESSION ["vStab_funktion"])) session_start ();

    $this->stab_fkt  = $_SESSION ["vStab_funktion"] ;
    $this->dbtyp = $table;

    if ($table == "master") {
      $this->db_master_katego = $conf_4f_tbl ["masterkatego"] ;
      $this->db_tablname      = $conf_4f_tbl ["masterkatego"] ;
      $this->db_tablnamelk    = $conf_4f_tbl ["masterkategolk"];
    } else {
      $this->db_tbl = $conf_4f_tbl ["usrtblprefix"].
                    strtolower ($_SESSION["vStab_funktion"])."_".
                    strtolower ($_SESSION["vStab_kuerzel"]) ;
      $this->db_tablname   = $this->db_tbl."_katego";
      $this->db_tablnamelk = $this->db_tbl."_kategolink";
    }
// $this->db_master_katego = $conf_4f_tbl ["masterkatego"] ;

    $this->db_server   = $conf_4f_db ["server"];
    $this->db_benutzer = $conf_4f_db ["user"];
    $this->db_passwort = $conf_4f_db ["password"];
    $this->db_name     = $conf_4f_db ["datenbank"];
    $this->grundkatego = array (
          1 => array ("kategorie"    => "Alle",
                      "beschreibung" => "ohne Ber�cksichtigung der Kategorien"),
          2 => array ("kategorie"    => "ohne",
                      "beschreibung" => "Ohne Kategorie"));

    $this->db_hndl = mysql_connect($this->db_server,$this->db_benutzer, $this->db_passwort)
       or die ("[connection] katego.php 73 Konnte keine Verbindung zur Datenbank herstellen");

    $db_check = mysql_select_db ($this->db_name, $this->db_hndl)
       or die ("[read_table] Auswahl der Datenbank fehlgeschlagen");
/*
    $result = mysql_ping  ($this->db_hndl);
    return ($result);
*/
  }


  function kategoliste (){
    if ($_SESSION["filter_darstellung"] == "1" ){
      include ("../config.inc.php");

      $this->set_katego_para ("master");
      $this->sqlquery = "SELECT * FROM `".$this->db_tablname."` WHERE 1  ORDER BY `kategorie`;";
      $query_result = mysql_query ($this->sqlquery, $this->db_hndl) or
         die("[query_table] <br>$this->sqlquery<br>103-".mysql_error()." ".mysql_errno());
      $this->resultcount = mysql_num_rows($query_result);
      $this->masterresult = NULL;
      for ($i=1; $i<=$this->resultcount; $i++){
        $this->masterresult[$i] = mysql_fetch_assoc($query_result);
      }
      mysql_free_result($query_result);

  //echo "<br>RESULT:"; var_dump ($this->masterresult); echo "<br>";

      $this->set_katego_para ("user");
      $this->sqlquery = "SELECT * FROM `".$this->db_tablname."` WHERE 1  ORDER BY `kategorie`;";
      $query_result = mysql_query ($this->sqlquery, $this->db_hndl) or
         die("[query_table] <br>$this->sqlquery<br>103-".mysql_error()." ".mysql_errno());
      $this->resultcount = mysql_num_rows($query_result);
      $this->userresult = NULL ;
      for ($i=1; $i<=$this->resultcount; $i++){
        $this->userresult[$i] = mysql_fetch_assoc($query_result);
      }
      mysql_free_result($query_result);

  //echo "<br>RESULT:"; var_dump ($this->userresult); echo "<br>";

      $mastercount = COUNT($this->masterresult) ;
      $usercount   = COUNT($this->userresult) ;

      //$_GET["ktgotyp"]=>  string(6) "global" ["ktgo"]=>  string(9) "Allgemein" } #
      //5 [kategotyp] => global [katego] => Allgemein ) #

      if ( $mastercount > $usercount ) $maxi = $mastercount; else $maxi = $usercount ;
      if (isset ($_SESSION [global_katego])) {
        $kategoselected = $_SESSION [global_katego];
      }



      echo "<TABLE border=\"0\" cellpadding=\"1\">";
      echo "<TBODY>";
      if ($mastercount != 0){
        echo "<TR>";
          echo "<TD>";   //src=\"".$conf_design_path."/120.jpg\"          //
            if (!isset($_SESSION [katego])){$color = "lightred";}else{$color = "red";}
            echo"<a href=\"".$conf_4f ["MainURL"]."?ktgotyp=global&ktgo=alle\">
              <img src=\"./createbutton.php?icontext=ALLE&color=".$color."\"
                   alt=\"".$katearr1["beschreibung"]."\"
                   border=\"0\"
                   title=\"".$katearr1["beschreibung"]."\"></a>";
          echo "</TD>";

        for ($i=1; $i<= $maxi; $i++) {
          echo "<TD>";
          if ( $this->masterresult[$i]["kategorie"] == "" ) {
            echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
          } else {
            if ( ($_SESSION [katego] == $this->masterresult[$i]["kategorie"]) AND
                 ($_SESSION [kategotyp] == "global") ){$color = "lightred";}else{$color = "red";}
            echo"<a href=\"".$conf_4f ["MainURL"]."?ktgotyp=global&ktgo=".$this->masterresult[$i]["kategorie"]."\">
              <img src=\"./createbutton.php?icontext=".$this->masterresult[$i]["kategorie"]."&color=".$color."\"
                   alt=\"".$katearr1["beschreibung"]."\"
                   border=\"0\"
                   title=\"".$katearr1["beschreibung"]."\"></a>";
          }
          echo "</TD>";
        }

        echo "</TR>";
      }

      if ($usercount != 0){
        echo "<TR>";
          echo "<TD>";
//            echo"<a href=\"".$conf_4f ["MainURL"]."?ktgotyp=user&ktgo=alle\">
            echo"<img src=\"".$conf_design_path."/null.jpg\"
                   alt=\"".$katearr1["beschreibung"]."\"
                   border=\"0\"
                   title=\"".$katearr1["beschreibung"]."\"></a>";
          echo "</TD>";
        for ($i=1; $i<= $maxi; $i++) {
          echo "<TD>";
          if ( $this->userresult[$i]["kategorie"] == "" ) {
            echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
          } else {
            if ( ($_SESSION [katego] == $this->userresult[$i]["kategorie"]) AND
                 ($_SESSION [kategotyp] == "user" ) ){$color = "lightgreen";}else{$color = "green";}
            echo"<a href=\"".$conf_4f ["MainURL"]."?ktgotyp=user&ktgo=".$this->userresult[$i]["kategorie"]."\">
              <img src=\"./createbutton.php?icontext=".$this->userresult[$i]["kategorie"]."&color=".$color."\"
                   alt=\"".$katearr1["beschreibung"]."\"
                   border=\"0\"
                   title=\"".$katearr1["beschreibung"]."\"></a>";

    //        echo $this->userresult[$i]["kategorie"];

          }
          echo "</TD>";
        }

        echo "</TR>";
      }
      echo "</TBODY>";
      echo "</TABLE>";
    }
  }



/******************************************************************************\
  Funktion: get_list ()
  SESSION=Array (

     [filter_gelesen] => 1    zeige gelesene
     [filter_erledigt] => 1   zeige erledigte
     [filter_start] => 1
     [filter_position] => 1
     [filter_darstellung] => 1
     [filter_anzahl] => 5 ) #

     $katego === leer , "global" oder "user"
\******************************************************************************/
  function get_list (){
    echo "\n\n\n<!-- ANFANG file:liste.php fkt:createlist -->";
    include ("../config.inc.php");
    include ("../para.inc.php");
    include ("../dbcfg.inc.php");
    include ("../e_cfg.inc.php");

    $tblusername   = $conf_4f_tbl ["usrtblprefix"].strtolower ($_SESSION["vStab_funktion"]).
                     "_".strtolower ($_SESSION["vStab_kuerzel"]);

    $dbaccess = new db_access ($conf_4f_db ["server"], $conf_4f_db ["datenbank"],
                         $conf_4f_tbl ["benutzer"], $conf_4f_db ["user"],  $conf_4f_db ["password"] );
    $query_select_arg = $conf_4f_tbl ["nachrichten"].".`00_lfd`, ".
                        $conf_4f_tbl ["nachrichten"].".`09_vorrangstufe`, ".
                        $conf_4f_tbl ["nachrichten"].".`04_richtung`, ".
                        $conf_4f_tbl ["nachrichten"].".`04_nummer`, ".
                        $conf_4f_tbl ["nachrichten"].".`10_anschrift`, ".
                        $conf_4f_tbl ["nachrichten"].".`12_abfzeit`, ".
                        $conf_4f_tbl ["nachrichten"].".`12_inhalt`, ".
                        $conf_4f_tbl ["nachrichten"].".`13_abseinheit`, ".
                        $conf_4f_tbl ["nachrichten"].".`14_funktion`, ".
                        $conf_4f_tbl ["nachrichten"].".`16_empf`, ".
                        $conf_4f_tbl ["nachrichten"].".`X00_status`, ".
                        $conf_4f_tbl ["nachrichten"].".`x01_abschluss` ";

    $query_from_arg   = $conf_4f_tbl ["nachrichten"] ;

    if (  ($_SESSION["filter_darstellung"] == "1" ) AND
         !(isset($_SESSION[flt_search])) AND
          (isset($_SESSION[kategotyp]))){
      switch ($_SESSION [kategotyp]){
        case "global":
          $query_select_arg .= ",".$conf_4f_tbl ["masterkatego"].".`kategorie`";
          $query_from_arg .= ",".$conf_4f_tbl ["masterkatego"].",".$conf_4f_tbl ["masterkategolk"];
        break;
        case "user"  :
          $query_select_arg .= ",".$tblusername."_katego.`kategorie`";
          $query_from_arg .= ",".$tblusername."_katego, ".$tblusername."_kategolink ";
        break;
        default: ;
      }
    }

      // F�r wenn sind die Meldungen bestimmt
    $query_where_arg1 = "(( `".$conf_4f_tbl ["nachrichten"]."`.`16_empf` like \"%".$_SESSION["vStab_funktion"]."%\" ) OR
                          ( `".$conf_4f_tbl ["nachrichten"]."`.`16_empf` like \"%alle%\" ))";

    if ($_SESSION [filter_erledigt] != 1){$donewhat = " NOT ";} else {$donewhat = " ";}

    if ($_SESSION["filter_darstellung"] == "1" ){
      if ($_SESSION [filter_gelesen]  == 1){
        $query_where_arg2 = " AND (`".$conf_4f_tbl ["nachrichten"]."`.`00_lfd` ".$readwhat." IN
                              ( select `".$tblusername."_read`.`nachnum` from `".$tblusername."_read` where 1))";
      } else {
        $query_where_arg2 = "";
      }
      $query_where_arg3 = " AND (`".$conf_4f_tbl ["nachrichten"]."`.`00_lfd` ".$donewhat." IN
                          ( select `".$tblusername."_erl`.`nachnum` from `".$tblusername."_erl` where 1))";
      if (isset($_SESSION[katego])){
        switch ($_SESSION[kategotyp]){
          case "global":
            $query_where_arg4 = " AND (".$conf_4f_tbl ["nachrichten"].".`00_lfd` = `".$conf_4f_tbl ["masterkategolk"]."`.`msg`)".
                                " AND (".$conf_4f_tbl ["masterkatego"].".`lfd` = `".$conf_4f_tbl ["masterkategolk"]."`.`katego`)".
                                " AND (".$conf_4f_tbl ["masterkatego"].".`kategorie` = \"".$_SESSION["katego"]."\")";

                                //  AND (`nv_masterkatego`.`kategorie` = "Allgemein")
          break;
          case "user":
            $query_where_arg4 = " AND (`".$conf_4f_tbl ["nachrichten"]."`.`00_lfd` = `".$tblusername."_kategolink`.`msg`)".
                                " AND (`".$tblusername."_katego`.`lfd` = `".$tblusername."_kategolink`.`katego`)".
                                " AND  (`".$tblusername."_katego`.`kategorie` = \"".$_SESSION["katego"]."\")";
          break;
          default: $query_where_arg4 = "";
        }
      }
    } else {
     $query_where_arg2 = "";  $query_where_arg3 ="";   $query_where_arg4 = "";
    }

    $query_orderby_arg = "`12_abfzeit` DESC, `09_vorrangstufe` DESC ";

    if (isset ($_SESSION["flt_search"])) {
      $query_search = "(".
          "(".$conf_4f_tbl ["nachrichten"].".`04_nummer` LIKE \"%".$_SESSION["flt_search"]."%\") OR ".
          "(".$conf_4f_tbl ["nachrichten"].".`10_anschrift` LIKE \"%".$_SESSION["flt_search"]."%\") OR ".
          "(".$conf_4f_tbl ["nachrichten"].".`12_abfzeit` LIKE \"%".$_SESSION["flt_search"]."%\") OR ".
          "(".$conf_4f_tbl ["nachrichten"].".`12_inhalt` LIKE \"%".htmlentities ($_SESSION["flt_search"])."%\") OR ".
          "(".$conf_4f_tbl ["nachrichten"].".`13_abseinheit` LIKE \"%".$_SESSION["flt_search"]."%\") )";

      $querycount = "SELECT COUNT(*) FROM ".$query_from_arg." WHERE ".
               $query_where_arg1." AND ".$query_search.";" ;

      $query = "SELECT ".$query_select_arg." FROM ".$query_from_arg." WHERE ".
               $query_where_arg1." AND ".$query_search." ORDER BY ".$query_orderby_arg ;

//      unset ($_SESSION["flt_search"]);

    } else {
      $query_search = "";
      $querycount = "SELECT COUNT(*) FROM ".$query_from_arg." WHERE ".
               $query_where_arg1." ".$query_where_arg2." ".$query_where_arg3." ".$query_where_arg4.";" ;

      $query = "SELECT ".$query_select_arg." FROM ".$query_from_arg." WHERE ".
               $query_where_arg1." ".$query_where_arg2." ".$query_where_arg3." ".$query_where_arg4." ORDER BY ".$query_orderby_arg ;
    }

    if ( debug == true ){  echo "<br><br>QUERYCOUNT [get_list] =".$querycount."<br>";echo "<br><br>";}

    if ( $_SESSION["filter_darstellung"] == "1" ){
      $tmp = $dbaccess->query_table_wert ($querycount);
      $anzahl = $tmp[0];

      $_SESSION["filter_rescount"] = $anzahl ;

      if ( debug == true ){ echo "<br>ANZAHL ===".$anzahl."<br>";}

      if (isset($_SESSION[flt_navi])) {

        switch ($_SESSION[flt_navi]) {
           // ANFANG
          case "start":
                  $_SESSION["filter_start"] = 0;
          break;
           // Eine Seite zur�ck
          case "back":
                  $_SESSION["filter_start"] -= $_SESSION[filter_anzahl];
                  if ($_SESSION["filter_start"] < 0){
                    $_SESSION["filter_start"]=0;}
          break;
           // Eine Seite vor
          case "for":
                  if ($anzahl < $_SESSION[filter_anzahl]){ $_SESSION[filter_start] = 0;
                  } else {
                    $_SESSION["filter_start"] += $_SESSION[filter_anzahl];
                    if ($_SESSION["filter_start"] >= $anzahl){
                      $_SESSION["filter_start"] = $anzahl-1;}
                  }
          break;
          // Letzte Seite
          case "end":
                  if ($anzahl < $_SESSION[filter_anzahl]){ $_SESSION[filter_start] = 0;
                  } else {
                    $seiten = floor ($anzahl / $_SESSION[filter_anzahl]);
                    $_SESSION["filter_start"] = $seiten * $_SESSION["filter_anzahl"];
                  }
          break;
        }
        unset ($_SESSION [flt_navi]);
      }

      $query .= " LIMIT ".$_SESSION["filter_start"].",".$_SESSION["filter_anzahl"];
    }
      // Zun�chst holen wir alls Meldungen mit den entsprechenden Kriterien
    $query = $query_select.$query;
    if ( debug == true ){ echo "<big>"; echo "QUERY [get_list] =".$query;echo "</big><br><br>"; }

    $result = $dbaccess->query_table ($query);

//    if ( debug == true ){ echo "<big>RESULT [get_list] ="; var_dump ($result); echo "</big><br>"; }
    return ($result);

  }


/******************************************************************************\

\******************************************************************************/
  function createlist (){
    echo "\n\n\n<!-- ANFANG file:liste.php fkt:createlist -->";
    include ("../config.inc.php");
    include ("../para.inc.php");
    include ("../dbcfg.inc.php");
    include ("../e_cfg.inc.php");

    switch ($this->listenart){

      case "FMA":           /***** F M A ****/

        $dbaccess = new db_access ($conf_4f_db ["server"], $conf_4f_db ["datenbank"],
                             $conf_4f_tbl ["benutzer"], $conf_4f_db ["user"],  $conf_4f_db ["password"] );

        $query = "SELECT `00_lfd`,`07_durchspruch`, `08_befhinweis`, `08_befhinwausw`,`09_vorrangstufe`, `10_anschrift`, `12_abfzeit`, `12_inhalt` FROM `".$conf_4f_tbl ["nachrichten"]."`
                  WHERE ((`04_richtung` = \"A\") AND (`03_datum` = 0) AND (`03_zeichen` = \"\")) order by `09_vorrangstufe` DESC, `12_abfzeit` ; ";
        $result = $dbaccess->query_table ($query);

//        echo "<big><big><big>Nachrichten im Ausgang zur Bef&ouml;rderung!</big></big></big>";

        if ($result != "" ){
          echo "<table style=\"text-align: center; background-color: rgb(255, 255, 255); \" border=\"1\" cellpadding=\"10\" cellspacing=\"1\">\n<tbody>\n";
          echo "<tr style=\"background-color: rgb(0,0,0); color:#FFFFFF; font-weight:bold;\">\n";
          echo "<td>ZEIT</td>\n";
          echo "<td>Vorst</td>\n";
          echo "<td>Anschr</td>\n";
          echo "<td>Inhalt</td>\n";
          echo "</tr>";

          foreach ($result as $row){
           if ( ( $row["09_vorrangstufe"] != "" ) and ($row["09_vorrangstufe"] != "eee")){
              echo "<tr style=\"background-color: rgb(255,255,100); color:#FFFFFF; font-weight:bold;\">\n";
           }
           $abfzeit = convdatetimeto ($row["12_abfzeit"]);
           echo "<td>"; if (($row["12_abfzeit"] != "")) { echo "<a href=\"mainindex.php?fm=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$abfzeit["stak"]."</a>\n"; } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "<td>"; if (($row["09_vorrangstufe"] != "")) { echo "<a href=\"mainindex.php?fm=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["09_vorrangstufe"]."</a>\n" ; } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "<td>"; if (($row["10_anschrift"] != "")) { echo "<a href=\"mainindex.php?fm=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["10_anschrift"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "<td align=\"left\">"; if (($row["12_inhalt"] != "")) { echo "<a href=\"mainindex.php?fm=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["12_inhalt"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "</tr>";
        }
        }// if isset $result
        echo "</tbody></table>";
      break;


      case "Stab_lesen":  // ******  S T A B    l e s e n *****
        /*
          Hole die Liste der gelesenen und der erledigten Nachrichten
        */
        $result = $this->get_list ("global");

        $this->darstellungs_art ( $this->listenart );

        $dbschongelesen = list_of_readed_msg () ;
        $dbschonerledigt = list_of_done_msg () ;
//        echo "<big><big><big>"; //Nachrichten im Eingang!</big></big></big>";
        $this->kategoliste ();
//        echo "</big></big></big>";
        if  ($result != "") {
          echo "<table style=\"text-align: center; background-color: rgb(255,255,255); \" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n<tbody>\n";
          echo "<tr style=\"background-color: rgb(240,240,200); color:#000000; font-weight:bold;\">\n";
            // gelesen ?
          echo "<th align=\"center\">";
          echo "<p><img src=\"".$conf_design_path."/info.gif\" alt=\"Vorrang/gelesen\"></p>";
          echo "</th>\n";
            // erledigt
          echo "<th align=\"center\">";
          echo "<p><img src=\"".$conf_design_path."/checked.gif\" alt=\"gepr&uuml;ft/erledigt\"></p>";
          echo "</td>\n";
            // Transport
          echo "<th align=\"center\">";
          echo "<p><img src=\"".$conf_design_path."/move.gif\" alt=\"Transportstatus\"></p>";
          echo "</td>\n";

          echo "<th>Vorrang</th>\n";

          echo "<th>E/A</th>\n";

          echo "<th>Num</th>\n";

          echo "<th>Von</th>";

          echo "<th>An</th>";

          echo "<th>Abfasszeit</th>\n";

          echo "<th>Inhalt</th>\n";

          echo "</tr>";
          // zeilenweise Anzeige der Datenbankanfrage

          foreach ($result as $row){
             $hilf = $this->explodereceiver ( $row ["16_empf"] );
             $receivercolor = $hilf [ $_SESSION [vStab_funktion] ]; // Empfaenger dieser Zeile
             switch ($receivercolor){
               case "rt":  $receiverbackground = $cfg ["lbg"] ["rt"];   break;
               case "gn":  $receiverbackground = $cfg ["lbg"] ["gn"];   break;
               case "bl":  $receiverbackground = $cfg ["lbg"] ["bl"];   break;
               case "ge":  $receiverbackground = $cfg ["lbg"] ["ge"];   break;
               default:    $receiverbackground = $cfg ["lbg"] ["dflt"];
             }
             echo "<tr style=\"background-color: ".$receiverbackground."; color:#FFFFFF; font-weight:bold;\">\n";

             // Liegt eine Vorrangstufe vor!!!
             $vorrang = ( ( $row["09_vorrangstufe"] != "") and ( $row["09_vorrangstufe"] != "eee" ) );

             // 1. Spalte schon gelesen?

             $schongelesen = false;

             if ($dbschongelesen != "") {
               if ( in_array ( $row["00_lfd"], $dbschongelesen ) ) {
                 $schongelesen = true;
               }
             }


             if ( $vorrang ){
               if ( $schongelesen ){
                 echo "<td align=\"center\">";
                 echo "<a href=\"mainindex.php?action=gelesen&00_lfd=".$row["00_lfd"]."&todo=unset\" target=\"_self\">\n";
                 echo "<img src=\"".$conf_design_path."/000.gif\" alt=\"lesen\" border=\"0\"></a>";
                 echo "</td>\n";
               } else {
                 echo "<td align=\"center\">";
                 echo "<a href=\"mainindex.php?action=gelesen&00_lfd=".$row["00_lfd"]."&todo=set\" target=\"_self\">\n";
                 echo "<img src=\"".$conf_design_path."/051.gif\" alt=\"lesen\" border=\"0\"></a>";
                 echo "</td>\n";
               }
             } else {
               if ( $schongelesen ){  // ==> wurde schon gelesen
                 echo "<td align=\"center\">";
                 echo "<a href=\"mainindex.php?action=gelesen&00_lfd=".$row["00_lfd"]."&todo=unset\" target=\"_self\">\n";
                 echo "<img src=\"".$conf_design_path."/000.gif\" alt=\"lesen\" border=\"0\"></a>";
                 echo "</td>\n";
               } else {
                 echo "<td align=\"center\">";
                 echo "<a href=\"mainindex.php?action=gelesen&00_lfd=".$row["00_lfd"]."&todo=set\" target=\"_self\">\n";
                 echo "<img src=\"".$conf_design_path."/020.png\" alt=\"Neu/new\" border=\"0\"></a>";
                 echo "</td>\n";
               }
             }

             $schonerledigt = false;

             if ($dbschonerledigt != "") {
               if ( in_array ( $row["00_lfd"], $dbschonerledigt ) ) {
                 $schonerledigt = true;
               }
             }


             if ( $schonerledigt ){  // ==> wurde schon erledigt
                 echo "<td align=\"center\">";
                 echo "<a href=\"mainindex.php?action=erledigt&00_lfd=".$row["00_lfd"]."&todo=unset\" target=\"_self\">\n";
                 echo "<img src=\"".$conf_design_path."/035.png\" alt=\"erledigt\" border=\"0\"></a>";
                 echo "</td>\n";
             } else {
                 echo "<td align=\"center\">";
                 echo "<a href=\"mainindex.php?action=erledigt&00_lfd=".$row["00_lfd"]."&todo=set\" target=\"_self\">\n";
                 echo "<p><img src=\"".$conf_design_path."/034.gif\" alt=\"NICHT erledigt\" border=\"0\"></p>";
                 echo "</td>\n";
             }


               // Status f�r ausgehende Nachrichten
             echo "<td align=\"center\">";
             if ( ( $row["04_richtung"] == "A") ){
               switch ( $row["X00_status"] ) {
                 case 2: // liegt vor dem Fernmelder ==> rot
                   echo "<p><img src=\"".$conf_design_path."/025.gif\" alt=\"liegt vorm Fernmelder\"></p>";
                 break;
                 case 4: // liegt vor dem Sichter ==> gelb
                   echo "<p><img src=\"".$conf_design_path."/026.gif\" alt=\"liegt vorm Sichter\"></p>";
                 break;
                 case 8: // fertig == gruen
                   echo "<p><img src=\"".$conf_design_path."/027.gif\" alt=\"Transport abgeschlossen!\"></p>";
                 break;
                 default:
                   echo "<p><img src=\"".$conf_design_path."/null.png\" alt=\"fremd\"></p>";
                 break;
               }
             } else { // ist ein Eingang, damit eine NULL nummer
               echo "<p><img src=\"".$conf_design_path."/null.gif\" alt=\"fremd\"></p>";
             }
             echo "</td>\n";


             echo "<td>";
              // Vorrangstufe
             if ( ( $row["09_vorrangstufe"] != "") and ( $row["09_vorrangstufe"] != "eee" ) ) {
               echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["09_vorrangstufe"]."</a>\n" ;
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";}
             echo "</td>\n";
              // Eingang / Ausgang
             echo "<td>"; if (($row["04_richtung"] != "")) { echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["04_richtung"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
              // Nachweisnummer
             echo "<td>"; if (($row["04_nummer"] != "")) { echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["04_nummer"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";

/**********************************/
              // Muss der Absender oder die Absendende Einheit unter von / an
             if ($row["04_richtung"] == "A" ) { // von = 14_funktion an=10_anschrift
                // Ausgang VON
               echo "<td>";
               if (($row["14_funktion"] != "")) {
                 echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["14_funktion"]."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";}
               echo "</td>\n";

                // Ausgang AN
               echo "<td>";
               if (($row["10_anschrift"] != "")) {
                 echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["10_anschrift"]."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";}
               echo "</td>\n";

             }

             if ($row["04_richtung"] == "E" ) {  // von = 13_abseinheit/14_funktion an=10_anschrift
               echo "<td>";
               if ( ($row["13_abseinheit"] != "") ) {
                 echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\"><big>".$row["13_abseinheit"]."</big></a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";


               echo "<td>";
               if (($row["10_anschrift"] != "")) {
                 echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\"><big>".$row["10_anschrift"]."</big></a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";
             }

/**********************************/
             echo "<td>";
             if (($row["12_abfzeit"] != "")) {
               $arr    = convdatetimeto ($row["12_abfzeit"]);
               $abzeit = $arr ["stak"];
               echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\"><big>".$abzeit."</big></a>\n";
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
             }
             echo "</td>\n";

             echo "<td align=\"left\">";
             if (($row["12_inhalt"] != "")) {
               echo "<a href=\"mainindex.php?stab=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\"><big>".$row["12_inhalt"]."</big></a>\n";
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
             }
             echo "</td>\n";

             echo "</tr>";

          }  // foreach $result
        }
        echo "</tbody></table>";
      break;


      case "Stab_sichten":   /*********** S t a b   s i c h t e n ************/
        if (debug){echo "<big><b>---Stab_sichten---878</b></big><br>";}
        $dbaccess = new db_access ($conf_4f_db ["server"], $conf_4f_db ["datenbank"],
                             $conf_4f_tbl ["benutzer"], $conf_4f_db ["user"],  $conf_4f_db ["password"] );
        $query = "SELECT `00_lfd`,`07_durchspruch`,
                         `08_befhinweis`,
                         `08_befhinwausw`,
                         `09_vorrangstufe`,
                         `10_anschrift`,
                         `12_abfzeit`,
                         `12_inhalt` FROM `".$conf_4f_tbl ["nachrichten"]."`
                  WHERE ( (  `15_quitdatum`    = 0 ) AND
                          (  `15_quitzeichen`  = 0 ) )  AND

                        ( (  `04_richtung`     =\"E\") OR
                          (  `03_datum`       != 0 ) AND
                          (  `03_zeichen`     != \"\" ) )
                  order by `09_vorrangstufe` DESC, `12_abfzeit`; ";

        $result = $dbaccess->query_table ($query);


//        echo "<big><big><big>Nachrichten zur Sichtung!</big></big></big>";
        if ($result != "" ){
          echo "<table style=\"text-align: center; background-color: rgb(255, 255, 255); \" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n<tbody>\n";
          echo "<tr style=\"background-color: rgb(240,240,200); color:#000000; font-weight:bold;\">\n";
          echo "<td>ZEIT</td>\n";
          echo "<td>Vorst</td>\n";
          echo "<td>Anschrift</td>\n";
          echo "<td>Inhalt / Text</td>\n";
          echo "</tr>";
          foreach ($result as $row){
           if ( ( $row["09_vorrangstufe"] != "" ) and ($row["09_vorrangstufe"] != "eee")){
              echo "<tr style=\"background-color: rgb(220,0,0); color:#FFFFFF; font-weight:bold;\">\n";
           }
           $abfzeit = convdatetimeto ($row["12_abfzeit"]);
           echo "<td>"; if (($row["12_abfzeit"] != "")) { echo "<a href=\"mainindex.php?sichter=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$abfzeit[stak]."</a>\n"; } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "<td>"; if (($row["09_vorrangstufe"] != "")) { echo "<a href=\"mainindex.php?sichter=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["09_vorrangstufe"]."</a>\n" ; } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "<td>"; if (($row["10_anschrift"] != "")) { echo "<a href=\"mainindex.php?sichter=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["10_anschrift"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "<td align=\"left\">"; if (($row["12_inhalt"] != "")) { echo "<a href=\"mainindex.php?sichter=meldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["12_inhalt"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
           echo "</tr>";
          } // foreach result row
        }// if isset $result
        echo "</tbody></table>";
      break;

      /*************************************************************************\
               SSSSS III  AAA  DDDD  M   M III N   N
               S      I  A   A D   D MM MM  I  NN  N
               SSSSS  I  AAAAA D   D M M M  I  N N N
                   S  I  A   A D   D M   M  I  N  NN
               SSSSS III A   A DDDD  M   M III N   N
      \*************************************************************************/
      case "SIADMIN":  // ***************  SICHTER ADMINISTRATOR  *********************
      case "FMADMIN":
        if (debug){echo "<big><b>---SIADMIN/FMADMIN---924</b></big><br>";}
        $this->darstellungs_art ( $this->listenart );

        include ("../fkt_rolle.inc.php");

        $dbaccess = new db_access ($conf_4f_db ["server"], $conf_4f_db ["datenbank"],
                             $conf_4f_tbl ["benutzer"], $conf_4f_db ["user"],  $conf_4f_db ["password"] );
        $query = "SELECT `00_lfd`,
                         `04_richtung`,
                         `04_nummer`,
                         `09_vorrangstufe`,
                         `10_anschrift`,
                         `12_abfzeit`,
                         `13_abseinheit`,
                         `12_inhalt`,
                         `16_empf`
                   FROM `".$conf_4f_tbl ["nachrichten"]."`
                               WHERE 1 order by 12_abfzeit DESC, 09_vorrangstufe DESC ; ";          //

        $result = $dbaccess->query_table ($query);

//        echo "<big><big><big>Alle Nachrichten! </big></big></big>";

        if  ($result != ""){
          echo "<table style=\"text-align: center; background-color: rgb(250,250, 250); \" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n<tbody>\n";
          echo "<tr style=\"background-color: rgb(250,250,0); color:fm=meldung&0000FF; font-weight:bold;\">\n";
          echo "<td>Vorst</td>\n";
          echo "<td>E/A</td>\n";
          echo "<td>Nw-Nr.</td>\n";
          echo "<td>Von/An</td>";
          echo "<td>Abfasszeit</td>\n";
          // Funktionen und Farben
          for ( $i=1; $i<= count ($conf_empf); $i++ ) {
            if ( ( $conf_empf [$i]["fkt"] != "Si" ) and ( $conf_empf [$i]["fkt"] != "A/W" ) ) {
              echo "<td>";
              echo $conf_empf [$i]["fkt"];
              echo "</td>\n";
            }
          }
          echo "<td>Inhalt</td>\n";
          echo "</tr>";

          foreach ($result as $row){
//            echo "row ==> ";   var_dump ($row); echo "<br><br>";

             // VORRANGSTUFE
             if ( ( $row["09_vorrangstufe"] != "") and ( $row["09_vorrangstufe"] != "eee" ) ){
               echo "<tr style=\"background-color: rgb(255,255,0); color:fm=meldung&FFFFFF; font-weight:bold;\">\n";
             }
             echo "<td>";
             if ( ( $row["09_vorrangstufe"] != "") and ( $row["09_vorrangstufe"] != "eee" ) ) {
               echo "<a href=\"mainindex.php?fm=SI-Adminmeldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["09_vorrangstufe"]."</a>\n" ;
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
             }
             echo "</td>\n";

             // RICHTUNG Eingang / Ausgang
             echo "<td>";
             if (($row["04_richtung"] != "")) {
               echo "<a href=\"mainindex.php?fm=SI-Adminmeldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["04_richtung"]."</a>\n";
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
             }
             echo "</td>\n";

             // N a c h w e i s n u m m e r
             echo "<td>";
             if (($row["04_richtung"] != "")) {
               echo "<a href=\"mainindex.php?fm=SI-Adminmeldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["04_nummer"]."</a>\n";
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
             }
             echo "</td>\n";

             if ($row["04_richtung"] == "A" ) {
               echo "<td>";
               if (($row["10_anschrift"] != "")) {
                 echo "<a href=\"mainindex.php?fm=SI-Adminmeldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["10_anschrift"]."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";
             } else {
               echo "<td>";

             // Absender / Einheit / Stelle / ...
             if (($row["13_abseinheit"] != "")) {
               echo "<a href=\"mainindex.php?fm=SI-Adminmeldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$row["13_abseinheit"]."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";
             }
             echo "<td>";
             // Abfassungs Z E I T
             if (($row["12_abfzeit"] != "")) {
               $abfzeit = convdatetimeto ($row["12_abfzeit"]);
               echo "<a href=\"mainindex.php?fm=SI-Adminmeldung&00_lfd=".$row["00_lfd"]."\" target=\"_self\">".$abfzeit[stak]."</a>\n";
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
             }
             echo "</td>\n";

             // Funktionen und Farben
             $empfcolor = extraiereempfaenger ( $row ["16_empf"] ) ;
             for ( $i=1; $i<= count ($conf_empf); $i++ ) {
               if ( ( $conf_empf [$i]["fkt"] != "Si" ) and ( $conf_empf [$i]["fkt"] != "A/W" ) ) {
                 switch ($empfcolor [$conf_empf [$i][fkt]]) {
                   case "rt":
                     echo "<td style=\"text-align: center; background-color: rgb(255, 0, 0); \">";
                   break;
                   case "gn":
                     echo "<td style=\"text-align: center; background-color: rgb(0, 255, 0); \">";
                   break;
                   case "bl":
                     echo "<td style=\"text-align: center; background-color: rgb(0, 0, 255); \">";
                   break;
                   default:
                     echo "<td style=\"text-align: center; background-color: rgb(250, 250, 250); \">";
                     echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
                 }
                 echo "</td>";
               }
             }

             // I N H A L T !
             echo "<td align=\"left\">";
             if (($row["12_inhalt"] != "")) {
               echo "<a href=\"mainindex.php?fm=SI-Adminmeldung&00_lfd=".
                        $row["00_lfd"]."\" target=\"_self\">".
                        substr($row["12_inhalt"], 0, $conf_4f_liste ["inhalt"])." ..."."</a>\n";
             } else {
               echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
             }
             echo "</td>\n";
             echo "</tr>";
          }
        }
        echo "</tbody></table>";
      break; // case SIADMIN



      case "FmNwE":  // *****  F M N W E ingang ******

        $dbaccess = new db_access ($conf_4f_db ["server"], $conf_4f_db ["datenbank"],
                             $conf_4f_tbl ["benutzer"], $conf_4f_db ["user"],  $conf_4f_db ["password"] );
        $query = "SELECT `00_lfd`,`09_vorrangstufe`,`04_richtung`, `04_nummer`, `10_anschrift`,
                         `12_abfzeit`, `12_inhalt`, `13_abseinheit`, `x01_abschluss`
                  FROM `".$conf_4f_tbl ["nachrichten"]."`
                  WHERE 04_richtung = \"E\" order by 04_nummer ASC ; ";
// echo "query=".$query."<br>";
        $result = $dbaccess->query_table ($query);
/*
        pre_html ("N","Nachweis E ".$conf_4f ["NameVersion"]); // Normaler Seitenaufbau ohne Auffrischung

        echo "<style type=\"text/css\">";
        echo "body { font-family:Arial,sans-serif; }";

        echo "a:link { color:#EE0000; text-decoration:none; font-weight:bold; }";
        echo "a:visited { color:#EEAAAA; text-decoration:none; font-weight:bold; }";
        echo "a:hover { color:#EE0000; text-decoration:none; background-color:#FFFF99; font-weight:normal; }";
        echo "a:active { color:#0000EE; background-color:#FFFF99; font-weight:normal; }";
        echo "a:focus { color:#00AA00; background-color:#FFFF77; font-weight:normal; }";
        echo "</style>";
*/
        echo "<p align=\"center\"><big><big><big><b>Nachweisung Eingang</b></big></big></big></p>";

        if ( $result != "" ){

          echo "<table style=\"text-align: center; background-color: rgb(255,255,255); \" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n<tbody>\n";
          echo "<tr style=\"background-color: rgb(240,240,200); color:#000000; font-weight:bold;\">\n";
          echo "<td>Vorrang</td>\n";
          echo "<td>E/A</td>\n";
          echo "<td>Num</td>\n";
          echo "<td>Von/An</td>";
          echo "<td>Abfasszeit</td>\n";
          echo "<td>Inhalt</td>\n";
          echo "</tr>";
          if  ( $result != "" ) {
            foreach ($result as $row){
               echo "<td>";

               if ( ( $row["09_vorrangstufe"] != "") and
                    ( $row["09_vorrangstufe"] != "eee" ) ) {
                 echo "<a>".$row["09_vorrangstufe"]."</a>\n" ;
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";
               echo "<td>"; if (($row["04_richtung"] != "")) { echo "<a>".$row["04_richtung"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               echo "<td>"; if (($row["04_nummer"] != "")) { echo "<a>".$row["04_nummer"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               if ($row["04_richtung"] == "A" ) {
                 echo "<td>";
                 if (($row["10_anschrift"] != "")) {
                   echo "<a>".$row["10_anschrift"]."</a>\n"; } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               } else {
                 echo "<td>";
                 if (($row["13_abseinheit"] != "")) {
                   echo "<a>".$row["13_abseinheit"]."</a>\n";
                 } else {
                   echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               }
               echo "<td>";
               if (($row["12_abfzeit"] != "")) {
                 $arr    = convdatetimeto ($row["12_abfzeit"]);
                 $abzeit = $arr [stak];
                 echo "<a>".$abzeit."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";
               echo "<td align=\"left\">"; if (($row["12_inhalt"] != "")) { echo "<a>".$row["12_inhalt"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               echo "</tr>";
            }  // foreach $result
          } // if 2. result == ""
          echo "</tbody></table>";
        } else { // Result ist leer
          echo "<big><big><big>Keine Daten vorhanden!</big></big></big>";
        }
      break;

      case "FmNwA":  // *****  F M N W A usgang ******

        $dbaccess = new db_access ($conf_4f_db ["server"], $conf_4f_db ["datenbank"],
                             $conf_4f_tbl ["benutzer"], $conf_4f_db ["user"],  $conf_4f_db ["password"] );
        $query = "SELECT `00_lfd`,`09_vorrangstufe`,`04_richtung`, `04_nummer`, `10_anschrift`,
                         `12_abfzeit`, `12_inhalt`, `13_abseinheit`, `x01_abschluss`
                  FROM `".$conf_4f_tbl ["nachrichten"]."`
                  WHERE 04_richtung = \"A\" order by 04_nummer ASC ; ";
// echo "query=".$query."<br>";
        $result = $dbaccess->query_table ($query);

/*
        pre_html ("N","Nachweis A".$conf_4f ["NameVersion"]); // Normaler Seitenaufbau mit Auffrischung

        echo "<style type=\"text/css\">";
        echo "body { font-family:Arial,sans-serif; }";

        echo "a:link { color:#EE0000; text-decoration:none; font-weight:bold; }";
        echo "a:visited { color:#EEAAAA; text-decoration:none; font-weight:bold; }";
        echo "a:hover { color:#EE0000; text-decoration:none; background-color:#FFFF99; font-weight:normal; }";
        echo "a:active { color:#0000EE; background-color:#FFFF99; font-weight:normal; }";
        echo "a:focus { color:#00AA00; background-color:#FFFF77; font-weight:normal; }";
        echo "</style>";
*/
        echo "<p align=\"center\"><big><big><big><b>Nachweisung Ausgang</b></big></big></big></p>";

        if ( $result != "" ){
          echo "<table style=\"text-align: center; background-color: rgb(255,255,255); \" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n<tbody>\n";
          echo "<tr style=\"background-color: rgb(240,240,200); color:#000000; font-weight:bold;\">\n";
          echo "<td>Vorrang</td>\n";
          echo "<td>E/A</td>\n";
          echo "<td>Num</td>\n";
          echo "<td>Von/An</td>";
          echo "<td>Abfasszeit</td>\n";
          echo "<td>Inhalt</td>\n";
          echo "</tr>";
          if  ($result != "") {
            foreach ($result as $row){
               echo "<td>";
               if ( ( $row["09_vorrangstufe"] != "") and ( $row["09_vorrangstufe"] != "eee" ) ) {
                 echo "<a>".$row["09_vorrangstufe"]."</a>\n" ;
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";}
               echo "</td>\n";
               echo "<td>"; if (($row["04_richtung"] != "")) { echo "<a>".$row["04_richtung"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               echo "<td>"; if (($row["04_nummer"] != "")) { echo "<a>".$row["04_nummer"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               if ($row["04_richtung"] == "A" ) {
                 echo "<td>";
                 if (($row["10_anschrift"] != "")) {
                   echo "<a>".$row["10_anschrift"]."</a>\n"; } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               } else {
                 echo "<td>";
                 if (($row["13_abseinheit"] != "")) {
                   echo "<a>".$row["13_abseinheit"]."</a>\n";
                 } else {
                   echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               }
               echo "<td>";
               if (($row["12_abfzeit"] != "")) {
                 $arr    = convdatetimeto ($row["12_abfzeit"]);
                 $abzeit = $arr [stak];
                 echo "<a>".$abzeit."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";
               echo "<td align=\"left\">"; if (($row["12_inhalt"] != "")) { echo "<a>".$row["12_inhalt"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               echo "</tr>";
            }  // foreach $result
          }
          echo "</tbody></table>";
        } else { // Result ist leer
          echo "<big><big><big>Keine Daten vorhanden!</big></big></big>";
        }
      break;

      case "FmNw":  // *****  F M N W  ******

        $dbaccess = new db_access ($conf_4f_db ["server"], $conf_4f_db ["datenbank"],
                             $conf_4f_tbl ["benutzer"], $conf_4f_db ["user"],  $conf_4f_db ["password"] );
        $query = "SELECT `00_lfd`,`01_datum`, `02_zeit`, `03_datum`, `09_vorrangstufe`,`04_richtung`,
                         `04_nummer`, `10_anschrift`, `12_inhalt`, `13_abseinheit`, `x01_abschluss`
                  FROM `".$conf_4f_tbl ["nachrichten"]."`
                  WHERE 1 order by 04_nummer ASC ; ";
// echo "query=".$query."<br>";
        $result = $dbaccess->query_table ($query);

/*
        pre_html ("N","Nachweis A ".$conf_4f ["Titelkurz"]." ".$conf_4f ["Version"]); // Normaler Seitenaufbau mit Auffrischung

        echo "<style type=\"text/css\">";
        echo "body { font-family:Arial,sans-serif; }";

        echo "a:link { color:#EE0000; text-decoration:none; font-weight:bold; }";
        echo "a:visited { color:#EEAAAA; text-decoration:none; font-weight:bold; }";
        echo "a:hover { color:#EE0000; text-decoration:none; background-color:#FFFF99; font-weight:normal; }";
        echo "a:active { color:#0000EE; background-color:#FFFF99; font-weight:normal; }";
        echo "a:focus { color:#00AA00; background-color:#FFFF77; font-weight:normal; }";
        echo "</style>";
*/
        echo "<p align=\"center\"><big><big><big><b>Nachweisung Eingang / Ausgang</b></big></big></big></p>";

        if ( $result != "" ){
          echo "<table style=\"text-align: center; background-color: rgb(255,255,255); \" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n<tbody>\n";
          echo "<tr style=\"background-color: rgb(240,240,200); color:#000000; font-weight:bold;\">\n";
          echo "<td>Vorrang</td>\n";
          echo "<td>E/A</td>\n";
          echo "<td>Num</td>\n";
          echo "<td>Von/An</td>";
          echo "<td>Aufnahme</td>\n";
          echo "<td>Annahme</td>\n";
          echo "<td>Bef&ouml;rder</td>\n";
          echo "<td>Inhalt</td>\n";
          echo "</tr>";
          if  ($result != "") {
            foreach ($result as $row){
               echo "<td>";
               if ( ( $row["09_vorrangstufe"] != "") and ( $row["09_vorrangstufe"] != "eee" ) ) {
                 echo "<a>".$row["09_vorrangstufe"]."</a>\n" ;
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";}
               echo "</td>\n";
               echo "<td>"; if (($row["04_richtung"] != "")) { echo "<a>".$row["04_richtung"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               echo "<td>"; if (($row["04_nummer"] != "")) { echo "<a>".$row["04_nummer"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               if ($row["04_richtung"] == "A" ) {
                 echo "<td>";
                 if (($row["10_anschrift"] != "")) {
                   echo "<a>".$row["10_anschrift"]."</a>\n"; } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               } else {
                 echo "<td>";
                 if (($row["13_abseinheit"] != "")) {
                   echo "<a>".$row["13_abseinheit"]."</a>\n";
                 } else {
                   echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               }


               echo "<td>";
               if (($row["01_datum"] != "") and ( $row["01_datum"] != "0000-00-00 00:00:00" )) {
                 $arr    = convdatetimeto ($row["01_datum"]);
                 $abzeit = $arr [stak];
                 echo "<a>".$abzeit."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>";

               echo "<td>";
               if (($row["02_zeit"] != "") and ( $row["02_zeit"] != "0000-00-00 00:00:00" )) {
                 $arr    = convdatetimeto ($row["02_zeit"]);
                 $abzeit = $arr [stak];
                 echo "<a>".$abzeit."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>";

               echo "<td>";
               if (($row["03_datum"] != "") and ( $row["03_datum"] != "0000-00-00 00:00:00" )) {
                 $arr    = convdatetimeto ($row["03_datum"]);
                 $abzeit = $arr [stak];
                 echo "<a>".$abzeit."</a>\n";
               } else {
                 echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";
               }
               echo "</td>\n";

               echo "<td align=\"left\">"; if (($row["12_inhalt"] != "")) { echo "<a>".$row["12_inhalt"]."</a>\n";  } else { echo "<p><img src=\"null.gif\" alt=\"leer\"></p>";} echo "</td>\n";
               echo "</tr>";
            }  // foreach $result
          }
          echo "</tbody></table>";
        } else { // Result ist leer
          echo "<big><big><big>Keine Daten vorhanden!</big></big></big>";
        }
      break;


    } // switch
  echo "<!-- ENDE file:liste.php fkt:createlist -->";
  }


} // class

?>
