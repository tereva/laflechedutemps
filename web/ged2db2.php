<?php
###################################################################################################
##
## Version 1.0.3
## Date: 2011-09-06
## Author: Carsten Froehlich, Luventas Web Design, Troisdorf, germany
## email: carsten.froehlich@luventas-webdesign.de
## http://www.luventas-webdesign.de
## http://blog.luventas-webdesign.de
##
## gedcomToDatabase.php is a PHP script to translate your GEDCOM file to a MySQL database.
## 
## This file is created by Carsten Froehlich, Luventas Web Design and is a translation 
## from the PERL version which is also created by Carsten Froehlich
##
## This script is free for private or commerical use, but this lines must stay in the script
## and I please you for a short email, for which page this script is used.
##
## ############### IMPORTANT: #####################
## !!!The GEDCOM file must have "ISO 8859-2" format because of replacement for special characters!!
##
###################################################################################################
##
## Fix of Bug in Version 1.0.1:
## Information about Residence was inserted in Birthday information.
## Now Residence ist ignored for the first fix version
##
###################################################################################################




function gedcom2timeline($filename){

		##############################################################

		## init all needed variables
		$anfang   = 0;
		$anfangf  = 0;

		$person   = array();
		$fam      = array();
		$chil     = array();

		$birt     = 0;
		$deat     = 0;
		$chr      = 0;
		$buri     = 0;
		$occu     = 0;
		$conf     = 0;

		$indi     = null;
		$surn     = null;
		$givn     = null;
		$marn     = null;
		$sex      = null;
		$birtplac = null;
		$birtdate = null;
		$deatplac = null;
		$deatdate = null;
		$chrdate  = null;
		$chrplac  = null;
		$buridate = null;
		$buriplac = null;
		$reli     = null;
		$occu2    = null;
		$occudate = null;
		$occuplac = null;
		$confdate = null;
		$confplac = null;
		$note     = null;

		$famlist  = null;
		$marr     = 0;
		$marrdate = null;
		$marrplac = null;
		$famindi  = null;
		$husb     = null;
		$wife     = null;

		//echo "Start reading GEDCOM file<br />";

		## read file line per line
		$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		for($i = 0; $i < count($lines); $i++) {
		  if(preg_match("/0\x20\x40(I.*)\x40/", $lines[$i], $id)) { ## if line starts with 0 @I, a new person is found
			if($anfang == 1) { ## if next person entry found, write previous to array
			  array_push($person, $indi.";".$surn.";".$givn.";".$marn.";".$sex.";".$birtdate.";".$birtplac.";".$chrdate.";".$chrplac.";".$deatdate.";".$deatplac.";".$buridate.";".$buriplac.";".$occu2.";".$occudate.";".$occuplac.";".$reli.";".$confdate.";".$confplac.";".$note); # fill person entry data in array
			  ## reset variables for to read next entry
			  $deat     = 0;
			  $chr      = 0;
			  $buri     = 0;
			  $occu     = 0;
			  $conf     = 0;
			  $birt     = 0;
			  $marr     = 0;
			  
			  $indi     = null;
			  $surn     = null;
			  $givn     = null;
			  $marn     = null;
			  $sex      = null;
			  $birtplac = null;
			  $birtdate = null;
			  $deatplac = null;
			  $deatdate = null;
			  $chrdate  = null;
			  $chrplac  = null;
			  $buridate = null;
			  $buriplac = null;
			  $reli     = null;
			  $occu2    = null;
			  $occudate = null;
			  $occuplac = null;
			  $confdate = null;
			  $confplac = null;
			  $note     = null;
			} else {
			  //echo "Start reading Person data<br />";
			}
			$indi = $id[1]; ## set indi variable to new value
			$indi = str_replace("\x27", "\xB4", $indi); ## replace ' with ` because of database relevance
			$anfang = 1;
		  } else if(preg_match("/2\x20SURN\x20(.*)/", $lines[$i], $surnA)) {   ## read last name
			$surn = str_replace("\x27", "\xB4", $surnA[1]);                    ## replace ' with ` because of database relevance
		  } else if(preg_match("/2\x20GIVN\x20(.*)/", $lines[$i], $givnA)) {   ## read first name
			$givn = str_replace("\x27", "\xB4", $givnA[1]);                    ## replace ' with ` because of database relevance
		  } else if(preg_match("/1\x20NAME\x20(.*)/", $lines[$i], $nameA)) {   ## read full name
			$full = str_replace("\x27", "\xB4", $nameA[1]);                    ## replace ' with ` because of database relevance
			$tmp  = preg_split("/\//", $full);                                 ## split full name to array
			## some gedcom software do not have the GIVN and SURN fields. Then this line is usefull. If this two fields are 
			## existing, they overwrite the surn and givn variables again.
			$givn = $tmp[0];                                                   ## read first name from full name
			$surn = $tmp[1];                                                   ## read last name from full name
		  } else if(preg_match("/2\x20_MARNM\x20(.*)/", $lines[$i], $marnA)) { ## read marriage name
			$marn = str_replace("\x27", "\xB4", $marnA[1]);                    ## replace ' with ` because of database relevance
		  } else if(preg_match("/1\x20SEX\x20(.*)/", $lines[$i], $sexA)) {     ## read sex
			$sex = $sexA[1];
		  }else if(preg_match("/1\x20BIRT/", $lines[$i])) { ## start reading birth data
			$deat = 0;
			$chr  = 0;
			$buri = 0;
			$occu = 0;
			$conf = 0;
			$birt = 1;
			$marr = 0;
		  } else if(preg_match("/1\x20RESI/", $lines[$i])) { ## dummy because of residence information, at the moment it is ignored
			$deat = 0;
			$chr  = 0;
			$buri = 0;
			$occu = 0;
			$conf = 0;
			$birt = 0;
			$marr = 0;
		  } else if(preg_match("/1\x20DEAT/", $lines[$i])) { ## start reading death data
			$birt = 0;
			$chr  = 0;
			$buri = 0;
			$occu = 0;
			$conf = 0;
			$deat = 1;
			$marr = 0;
		  } else if(preg_match("/1\x20CHR/", $lines[$i])) { ## start reading christening data
			$birt = 0;
			$deat = 0;
			$chr  = 1;
			$buri = 0;
			$occu = 0;
			$conf = 0;
			$marr = 0;
		  } else if(preg_match("/1\x20BURI/", $lines[$i])) { ## start reading burial data
			$birt = 0;
			$deat = 0;
			$chr  = 0;
			$buri = 1;
			$occu = 0;
			$conf = 0;
			$marr = 0;
		   }else if(preg_match("/1\x20OCCU\x20(.*)/", $lines[$i], $occu2A)) { ## read occupation data
			$birt = 0;
			$deat = 0;
			$chr  = 0;
			$buri = 0;
			$occu = 1;
			$conf = 0;
			$marr = 0;
			$occu2 = str_replace("\x27", "\xB4", $occu2A[1]); ## replace ' with ` because of database relevance
		  } else if(preg_match("/1\x20CONF/", $lines[$i])) {  ## start reading confirmation data
			$birt = 0;
			$deat = 0;
			$chr  = 0;
			$buri = 0;
			$occu = 0;
			$conf = 1;
			$marr = 0;
		  } else if(preg_match("/1\x20MARR/", $lines[$i])) {  ## start reading marriage data
			$marr = 1;
		  } else if(preg_match("/2\x20DATE\x20(.*)/", $lines[$i], $givenDate)) {  ## read date for...
			if($birt == 1) {
			  $birtdate = $givenDate[1];                                          ## ...birth
			  $birtdate = str_replace("\x27", "\xB4", $birtdate);                 ## replace ' with ` because of database relevance
			} if($deat == 1) {
			  $deatdate = $givenDate[1];                                          ## ...death
			  $deatdate = str_replace("\x27", "\xB4", $deatdate);                 ## replace ' with ` because of database relevance
			} if($chr == 1) {
			  $chrdate = $givenDate[1];                                           ## ...christening
			  $chrdate = str_replace("\x27", "\xB4", $chrdate);                   ## replace ' with ` because of database relevance
			} if($buri == 1) {
			  $buridate = $givenDate[1];                                          ## ...burial
			  $buridate = str_replace("\x27", "\xB4", $buridate);                 ## replace ' with ` because of database relevance
			} if($occu == 1) {
			  $occudate = $givenDate[1];                                          ## ...occupation
			  $occudate = str_replace("\x27", "\xB4", $occudate);                 ## replace ' with ` because of database relevance
			} if($conf == 1) {
			  $confdate = $givenDate[1];                                          ## ...confirmation
			  $confdate = str_replace("\x27", "\xB4", $confdate);                 ## replace ' with ` because of database relevance
			} if($marr == 1) {
			  $marrdate = $givenDate[1];                                          ## ...marriage
			  $marrdate = str_replace("\x27", "\xB4", $marrdate);                 ## replace ' with ` because of database relevance
			}
		  } else if(preg_match("/2\x20PLAC\x20(.*)/", $lines[$i], $givenPlac)) {  ## read place for...
			if($birt == 1) {
			  $birtplac = $givenPlac[1];                                          ## ...birth
			  $birtplac = str_replace("\x27", "\xB4", $birtplac);                 ## replace ' with ` because of database relevance
			} if($deat == 1) {
			  $deatplac = $givenPlac[1];                                          ## ...death
			  $deatplac = str_replace("\x27", "\xB4", $deatplac);                 ## replace ' with ` because of database relevance
			} if($chr == 1) {
			  $chrplac = $givenPlac[1];                                           ## ...christening
			  $chrplac = str_replace("\x27", "\xB4", $chrplac);                   ## replace ' with ` because of database relevance
			} if($buri == 1) {
			  $buriplac = $givenPlac[1];                                          ## ...burial
			  $buriplac = str_replace("\x27", "\xB4", $buriplac);                 ## replace ' with ` because of database relevance
			} if($occu == 1) {
			  $occuplac = $givenPlac[1];                                          ## ...occupation
			  $occuplac = str_replace("\x27", "\xB4", $occuplac);                 ## replace ' with ` because of database relevance
			} if($conf == 1) {
			  $confplac = $givenPlac[1];                                          ## ...confirmation
			  $confplac = str_replace("\x27", "\xB4", $confplac);                 ## replace ' with ` because of database relevance
			} if($marr == 1) {
			  $marrplac = $givenPlac[1];                                          ## ...marriage
			  $marrplac = str_replace("\x27", "\xB4", $marrplac);                 ## replace ' with ` because of database relevance
			}
		  } else if(preg_match("/1\x20RELI\x20(.*)/", $lines[$i], $reliA)) {      ## read religion data
			$reli = str_replace("\x27", "\xB4", $reliA[1]);                       ## replace ' with ` because of database relevance
		  } else if(preg_match("/1\x20NOTE\x20(.*)/", $lines[$i], $noteA)) {      ## read note
			$note = str_replace("\x27", "\xB4", $noteA[1]);                       ## replace ' with ` because of database relevance
		  } else if(preg_match("/2\x20CONC\x20(.*)/", $lines[$i], $concA) || preg_match("/2\x20CONT\x20(.*)/", $lines[$i], $concA)) {      ## extend note
			$note .= $concA[1];
			$note = str_replace("\x27", "\xB4", $note);                           ## replace ' with ` because of database relevance
		  } else if(preg_match("/0\x20\x40(F.*)\x40/", $lines[$i], $famindiA)) {  ## a new family entry is found
			if($anfangf == 1) {                                                   ## if next family entry found, write previous to array
			  $famlist = $famindi.";".$husb.";".$wife.";".$marrdate.";".$marrplac;
			  foreach($chil as $entry) {
				$famlist .= ";".$entry;
			  }
			  array_push($fam, $famlist);                                         ## fill family entry data in array
			  
			  $famlist  = null;
			  $marr     = 0;
			  $marrdate = null;
			  $marrplac = null;
			  $famindi  = null;
			  $husb     = null;
			  $wife     = null;
			  $chil     = array();
			}
			if($anfangf == 0) {                                                   ## if first family entry found, write last person entry to array
			  //echo "Start reading family data<br />";
			  array_push($person, $indi.";".$surn.";".$givn.";".$marn.";".$sex.";".$birtdate.";".$birtplac.";".$chrdate.";".$chrplac.";".$deatdate.";".$deatplac.";".$buridate.";".$buriplac.";".$occu2.";".$occudate.";".$occuplac.";".$reli.";".$confdate.";".$confplac.";".$note);
			  ## reset all variables of person, start reading family data
			  $birt     = 0;
			  $deat     = 0;
			  $chr      = 0;
			  $buri     = 0;
			  $occu     = 0;
			  $conf     = 0;
			  
			  $indi     = null;
			  $surn     = null;
			  $givn     = null;
			  $sex      = null;
			  $birtplac = null;
			  $birtdate = null;
			  $deatplac = null;
			  $deatdate = null;
			  $chrdate  = null;
			  $chrplac  = null;
			  $buridate = null;
			  $buriplac = null;
			  $reli     = null;
			  $occu2    = null;
			  $occudate = null;
			  $occuplac = null;
			  $confdate = null;
			  $confplac = null;
			  $note     = null;
			  $anfangf  = 1;
			}
			$famindi = $famindiA[1];
		  } else if(preg_match("/1\x20HUSB\x20\x40(.*)\x40/", $lines[$i], $husbA)) { ## husband entry found
			$husb = str_replace("\x27", "\xB4", $husbA[1]);                          ## replace ' with ` because of database relevance
		  } else if(preg_match("/1\x20WIFE\x20\x40(.*)\x40/", $lines[$i], $wifeA)) { ## wife entry found
			$wife = str_replace("\x27", "\xB4", $wifeA[1]);                          ## replace ' with ` because of database relevance
		  } else if(preg_match("/1\x20CHIL\x20\x40(.*)\x40/", $lines[$i], $cA)) {    ## child entry found
			$c = str_replace("\x27", "\xB4", $cA[1]);                                ## replace ' with ` because of database relevance
			array_push($chil, $c);
		##################################################################################################################
		##################################################################################################################
		  } else if(preg_match("/1\x20CHAN/", $lines[$i])) {                         ## End of person entry found, reset controling variables
			$deat = 0;
			$chr  = 0;
			$buri = 0;
			$occu = 0;
			$conf = 0;
			$birt = 0;
			$marr = 0;
		  } else if(preg_match("/0\x20TRLR/", $lines[$i])) {                         ## End of file reached, save last family entry to array
			$famlist = $famindi.";".$husb.";".$wife.";".$marrdate.";".$marrplac;
			  foreach($chil as $entry) {
				$famlist .= ";".$entry;
			  }
			  array_push($fam, $famlist);
		  }
		}
		//echo "Finish reading GEDCOM file<br />Start inserting in database<br />";

		##################################################################################################################
		##################################################################################################################
		//echo "Fichier brut : " . $filename ."<br />";
		$file = explode('/', $filename);
		$fich_timeline = "UPLOAD/" . $file[2] . ".js";
		//echo "Fichier traite : " . $fich_timeline ."<br />";

		//$fich_timeline = array_pop($file) . ".js";
		//move_uploaded_file($_FILES['aFile']['tmp_name'], $nouveau_chemin.'monFichier.png');

		//echo "Ecriture au format timeline : " . $fich_timeline ."<br />";
				
		$fp = fopen ($fich_timeline, "w+");		
		fputs($fp, "var timeline_data2 = { \n 'dateTimeFormat': 'iso8601', \n 'wikiURL': \"http://simile.mit.edu/shelf/\", \n 'wikiSection': \"Simile Cubism Timeline\", \n 'events' : \n[");

		## fill person_st table from array
		foreach($person as $entry) {               ## read from array
			$tmp     = preg_split("/\x3B/", $entry); ## split array entry on ; sign
			$date1 = new DateTime($tmp[5]);
			$date2 = new DateTime($tmp[9]);			
			$start = $date1->format(DateTime::ISO8601);
			$end = $date2->format(DateTime::ISO8601);
			$title = $tmp[1]." ".$tmp[2];
			
			## replace special characters / a voir
			//$insert  = replaceSpecialChars($insert);
			
			fputs($fp,"\n\n\t{'start' : '" . $start . "',");
			fputs($fp,"\n\t'end' : '" . $end . "',");
			fputs($fp,"\n\t'title' : '" . $title . "',");
			fputs($fp,"\n\t'durationEvent' : true},");
		  
		  $tmp = array();
		}

		fputs($fp, "\n]\n}" );
		fclose($fp);
		return $fich_timeline;

}


##################################################################################################################
## helper functions
##################################################################################################################

function replaceSpecialChars(&$statement) {
  # replace umlauts and other special characters. Extend this list, if needed.
  $statement = str_replace("\xC3\xBC", "\xFC", $statement); #ü
  $statement = str_replace("\xC3\xB6", "\xF6", $statement); #ö
  $statement = str_replace("\xC3\x9F", "\xDF", $statement); #ß
  $statement = str_replace("\xC3\xA4", "\xE4", $statement); #ä
  $statement = str_replace("\xC3\xB3", "\xF3", $statement); #ó
  $statement = str_replace("\xC3\xA6", "\xE6", $statement); #æ
  $statement = str_replace("\xC3\xA9", "\xE9", $statement); #é
  $statement = str_replace("\xC3\x96", "\xD6", $statement); #Ö
  
  return $statement;
}

?>
