<?php
$page = 'start';
require_once('./init.php');
/*---------------------------------------------------------*/
//Kommentare schreiben
if(isset($_POST['comment_submit'])) {
	$write_comment = $statistics->write_comment(
		html_entity_decode($statistics->name2brid($_POST['comment_brace_name'][$_POST['comment_form']])),
		$_POST['comment_content'][$_POST['comment_form']],
		$_POST['comment_picid'][$_POST['comment_form']]
	);
}
if(isset($write_comment)) {
	if(isset($write_comment)) {
		if($write_comment === true) {
			$js .= 'alert("'.$lang->php->write_comment->wahr->$lng.'");';			
		}elseif($write_comment == 2) {
			$js .= 'alert("'.$lang->php->write_comment->f2->$lng.'");';			
		}elseif($write_comment == 3) {
			$js .= 'alert("'.$lang->php->write_comment->f3->$lng.'");';			
		}elseif($write_comment === false) {
			$js .= 'alert("'.$lang->php->write_comment->falsch->$lng.'");';			
		}
	}
}
//Kommentar löschen
if(isset($_GET['comment_deleted'])) {
	if($_GET['comment_deleted'] == 'true') {
		$js .= 'alert("'.$lang->php->manage_comment->$lng.'");';	
	}
}
//Bild löschen
if(isset($_GET['pic_deleted'])) {
	if($_GET['pic_deleted'] == 'true') {
		$js .= 'alert("'.$lang->php->manage_pic->$lng.'");';	
	}
}
$user_anz = 5;
$systemStats = $statistics->systemStats($user_anz, 3);
//hier werden die Armbänder bestimmt, die angezeigt werden
$bracelets_displayed = $systemStats['recent_brids'];
foreach($bracelets_displayed as $key => $val) {
	$stats[$key] = $statistics->bracelet_stats($val, true);
}
$recent_brid_pics = false;
/*---------------------------------------------------------*/
require_once('./index.php');
//$page zeigt die Seite an und durch das einbinden der index.php Datei wird einfach alles über die index.php Datei geregelt, die GET und POST Variablen bleiben unverändert und werden automatisch übermittelt
?>