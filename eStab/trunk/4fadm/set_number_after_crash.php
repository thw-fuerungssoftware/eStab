<?php
/*****************************************************************************\
   Datei: set_number_after_crash.php

   ben�tigte Dateien: config.inc.php, dbcfg.inc.php, protokoll.php


   Beschreibung:

          F�llt estab aus wird mit papier weitergearbeitet. Ist estab dann wider
          einsatzbereit muss es m�glich sein den Nachrichtenz�hler auf die n�chste
          bis dahin aufgelaufene Nachrichtennummer zu inkrementieren.
          Dekrementieren ist nicht gestattet. Diese Aktion muss geloggt werden.
          Das �ndern des Z�hlerstandes soll als weitere Option im Adminbereich
          aufgelistet werden.

          First Page: Formular anzeigen und Counter inkrementiernen

          Second Page: Neue Counter Position setzten

   (C) David Toboll FGr F/K M�nster
   mailto://d.toboll@thw-muenster.de
\******************************************************************************/

include("../4fcfg/dbcfg.inc.php");
include("../4fcfg/config.inc.php");
include("../4fach/protokoll.php");

//Parameter
$page = $_GET['page'];
if($page=='') $page = 1;
$protokoll = false;

//Datenbank Verbindung herstellen und �berpr�fen
 $db = mysql_connect($conf_4f_db ["server"],$conf_4f_db ["user"], $conf_4f_db ["password"]);
 $db_con_is_ok = mysql_ping  ($db);
 mysql_select_db($conf_4f_db ["datenbank"])  ;


//Datenbank abfrage und in Array ablegen
 $tabelle = $conf_4f_tbl['nachrichten'];



//########################################## First Page ##########################################
if($page == 1) {

//DB Einlesen
$result = mysql_query ("SELECT 04_richtung, 04_nummer FROM  $tabelle ORDER BY 00_lfd DESC");
            while($inhalt = mysql_fetch_array($result, MYSQL_ASSOC)){
            $nummer[] = $inhalt['04_nummer'];
            $richtung[] = $inhalt['04_richtung'];
            }
//Nachweisung �berpr�fen
if(Nachweisung == "gemeinsam")
         {
         ?>
<script type="text/javascript">
         function checkForm() {
         var strFehler='';
         if (document.forms[0].ea_nummer.value < <? echo $nummer[0]++; ?>)
         strFehler += "Feld Ein-/Ausgangsnummer fehlerhaft:Mindestwert <? echo $nummer[0]; ?> \n";

         if (strFehler.length>0) {
         alert("Festgestellte Probleme: \n\n"+strFehler);
         return(false);
         }
         }
</script>
<?php
         echo"<form action='' onsubmit='return checkForm()' method=get>";
         echo"<fieldset>";
         echo"<legend>Nachrichtennummer nach Systemausfall setzen</legend>";
         echo"Hinweis: Tragen Sie hier die letzte verwendete Nummer ein!";
         echo"<table border=2 cellpadding=5 cellspacing=0 bgcolor=#E0E0E0>";
         echo"<tr><td align=right><b>Ein-/Ausgangsnummer</b><br>Mindestwert: ".$nummer[0]."</td><td>";
         echo"<input name=ea_nummer type=text size=8>";
         echo"</td></tr></table>";
         echo"<input name=page type=hidden value=2 size=8>";
         echo"<input type='submit' value='Absenden'/>";
         echo"</fieldset></form>";
         }
elseif(Nachweisung == "getrennt")
         {
         //Finde letzte Eingangsnummer
                 for($i=0; $i<=count($richtung); $i++){
                 if($richtung[$i] == "E")
                         {
                         $e_nummer = $i;
                         break;
                         }
                 }
         //Finde letzte Ausgangsnummer
                 for($i=0; $i<=count($richtung); $i++){
                 if($richtung[$i] == "A")
                         {
                         $a_nummer = $i;
                         break;
                         }
                 }

?>
<script type="text/javascript">
         function checkForm() {
         var strFehler='';
         if (document.forms[0].e_nummer.value < <? echo $nummer[$e_nummer]++; ?>)
         strFehler += "Feld Eingangsnummer fehlerhaft:Mindestwert <? echo $nummer[$e_nummer]; ?> \n";

          if (document.forms[0].a_nummer.value < <? echo $nummer[$a_nummer]++; ?>)
         strFehler += "Feld Ausgangsnummer fehlerhaft:Mindestwert <? echo $nummer[$a_nummer]; ?> \n";

         if (strFehler.length>0) {
         alert("Festgestellte Probleme: \n\n"+strFehler);
         return(false);
         }
         }
</script>
<?php
         //Formular schreiben
         echo"<form action='' onsubmit='return checkForm()' method=get>";
         echo"<fieldset>";
         echo"<legend>Nachrichtennummer nach Systemausfall setzen</legend>";
         echo"Hinweis: Tragen Sie hier die letzten verwendeten Nummeren ein!";
         echo"<table border=2 cellpadding=5 cellspacing=0 bgcolor=#E0E0E0>";
         echo"<tr><td align=right><b>Eingangsnummer</b><br>Mindestwert: ".$nummer[$e_nummer]."</td><td>";
         echo"<input name=e_nummer type=text size=8>";
         echo"</td></tr>";
         echo"<tr><td align=right><b>Ausgangsnummer</b><br>Mindestwert: ".$nummer[$a_nummer]."</td><td>";
         echo"<input name=a_nummer type=text size=8>";
         echo"</td></tr></table>";
         echo"<input name=page type=hidden value=2 size=8>";
         echo"<input type='submit' value='Absenden'/>";
         echo"</fieldset></form>";
         }
}
//########################################## Second Page ##########################################
if($page == 2){
//Neue Nummern im System setzen
if(Nachweisung == "gemeinsam")
         {
         $ea_num = $_GET['ea_nummer'];
         $eintrag = "INSERT INTO $tabelle (04_richtung, 04_nummer, 12_inhalt)VALUES ('E', '$ea_num', 'eStab System Meldung')";
         $eintragen = mysql_query($eintrag);
         if($eintragen) echo "Nachweisnummer (Eingang/Ausgang) wurde gesetzt auf:".$ea_num;
         $protokoll = true;
         }
elseif(Nachweisung == "getrennt")
         {
         $e_num = $_GET['e_nummer'];
         $a_num = $_GET['a_nummer'];
         $eintrag = "INSERT INTO $tabelle (03_zeichen, 04_richtung, 04_nummer, 12_inhalt) VALUES ('xxx', 'A', '$a_num', 'eStab System Meldung')";
         $eintragen = mysql_query($eintrag);
         if($eintragen) echo "Nachweisnummer (Ausgang) wurde gesetzt auf:".$a_num."<br>";
         $eintrag = "INSERT INTO $tabelle (04_richtung, 04_nummer, 12_inhalt, 15_quitdatum, 15_quitzeichen) VALUES ('E', '$e_num', 'eStab System Meldung', now(), 'xxx')";
         $eintragen = mysql_query($eintrag);
         if($eintragen) echo "Nachweisnummer (Eingang) wurde gesetzt auf:".$e_num;
         $protokoll = true;
         }


//Protokoll eintrag
if($protokoll)
         {
         if(Nachweisung == "gemeinsam") $wert_neu = "E/A".$ea_num;
         elseif(Nachweisung == "getrennt") $wert_neu = "E".$e_num." / A".$a_num;
         $was = "Nachrichtennummer Sync";
         $daten = "Nachrichtenz&auml;hler nach Systemausfall auf ".$wert_neu." gesetzt.";
         protokolleintrag($was, $daten);
         }


}

echo "<br><br><br><a href=admin.php>Zur�ck zur Administrations �bersicht</a>";
?>