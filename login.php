<?php
$page = 'login';
@$registerbr = $_GET['registerbr'];
@$postpic = $_GET['postpic'];
@$loginattempt = $_GET['loginattempt'];
@$unvalidated = $_GET['unvalidated'];
require_once('./init.php');
/*---------------------------------------------------------*/
if(isset($unvalidated)) {
	$userdetails = $statistics->userdetails($unvalidated);
	if($userdetails['status'] != 0) {
		unset($unvalidated);
	}
}
//Registrierungsfunktion
if(isset($_POST['reg_login']) && isset($_POST['reg_email']) && isset($_POST['reg_password'])  && isset($_POST['reg_password2'])){
	$user_registered_return = User::register($_POST, $db);
	$user_registered = $lang->php->register->{'f'.$user_registered_return}->$lng;
}
//E-Mail Bestätigung erneut senden.
if(isset($_POST['revalidate_submit'])) {
	$revalidation = $user->revalidate($_POST['revalidate_user'], $_POST['revalidate_email']);
}
	//Titel anpassen
if(isset($unvalidated) || @$user_registered === true || isset($revalidation)) $pagename[$page] = 'Bestätigung';
//Bild posten Funktion aufrufen
if(isset($_POST['registerpic_submit'])) {
		$pic_registered = $statistics->registerpic($_POST['registerpic_brid'],
											 $_POST['registerpic_description'],
											 $_POST['registerpic_city'],
											 $_POST['registerpic_country'],
											 $_POST['registerpic_state'],
											 $_POST['registerpic_latitude'],
											 $_POST['registerpic_longitude'],
											 $_POST['registerpic_title'],
											 $_POST['registerpic_date'],
											 $_FILES['registerpic_file'],
											 $max_file_size,
											 $_POST['rotation']);
	//Rückmeldung zu Bild-Posten anzeigen
	if(isset($pic_registered)) {
		switch ($pic_registered) {
			case 0:
				$js .= 'alert("'.$lang->php->pic_registered->f0->$lng.'");';
				break;
			case 1:
				$js .= 'alert("'.$lang->php->pic_registered->f1->$lng.'");';
				break;
			case 2:
				$js .= 'alert("'.$lang->php->pic_registered->f2->$lng.'");';
				break;
			case 3:
				$js .= 'alert("'.$lang->php->pic_registered->f3->$lng.'");';
				break;
			case 4:
				$js .= 'alert("'.$lang->php->pic_registered->f4->$lng.'");';
				break;
			case 5:
				$js .= 'alert("'.$lang->php->pic_registered->f5->$lng.'");';
				break;
			case 6:
				$js .= 'alert("'.$lang->php->pic_registered->f6->$lng.'");';
				break;
			case 7:
				header('Location: /'.bracename2ids($statistics->brid2name($_POST['registerpic_brid'])));
				break;
			default:
				$js .= 'alert("'.$pic_registered.'");';
		}
	}
}
//Armband registrieren Funktion aufrufen
if($user->login) {
	$userdetails = $statistics->userdetails($user->login);
	//Armband registrieren
	if (isset($_POST['reg_br']) && isset($_POST['registerbr_submit'])) {
		$bracelet_registered = $user->registerbr($_POST['reg_br']);
		//Rückmeldung zu Armband-registrieren anzeigen
		if(isset($bracelet_registered)) {
			if($bracelet_registered == 1) {
					header('Location: /profil');
			}
		}
	}
}
//Registrationsstatus von Armband aufrufen
if(isset($_GET['registerbr'])) {
	$bracelet_status = $statistics->bracelet_status($_GET['registerbr']);
}else {
	$bracelet_status = NULL;
}
/*---------------------------------------------------------*/
require_once('./index.php');
//$page zeigt die Seite an und durch das einbinden der index.php Datei wird einfach alles über die index.php Datei geregelt, die GET und POST Variablen bleiben unverändert und werden automatisch übermittelt
?>