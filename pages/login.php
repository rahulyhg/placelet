<?php
@$registerbr = $_GET['registerbr'];
@$postpic = $_GET['postpic'];
@$loginattempt = $_GET['loginattempt'];
foreach($_GET as $key => $val) {
	$_GET[$key] = clean_input($val);
}
//Registrierungsfunktion
if(isset($_POST['reg_login']) && isset($_POST['reg_email']) && isset($_POST['reg_password'])  && isset($_POST['reg_password2'])){
	$user_registered = User::register($_POST, $db);
	if($user_registered === true) {
    	$js .= 'alert("Dein Account wurde erfolgreich erstellt.");';
	}else {
		$js .= 'alert("'.$user_registered.'");';
	}
}
//Bild posten Funktion aufrufen
if(isset($_POST['registerpic_submit'])) {
		$pic_registered = $statistics->registerpic($_POST['registerpic_brid'],
											 $_POST['registerpic_description'],
											 $_POST['registerpic_city'],
											 $_POST['registerpic_country'],
											 $_POST['registerpic_title'],
											 $_FILES['registerpic_file'],
											 $max_file_size);
	//Rückmeldung zu Bild-Posten anzeigen
	if(isset($pic_registered)) {
		switch ($pic_registered) {
			case 2:
				$js .= 'alert("Dieses Format wird nicht unterstützt. Wir unterstützen nur: .jpeg, .jpg, .gif und .png. Wende dich bitte an unseren Support, dass wir dein Format hinzufügen können.");';
				break;
			case 4:
				$js .= 'alert("Dieses Armband wurde noch nicht registriert.");';
				break;
			case 5:
				$js .= 'alert("Dieses Armband gibt es nicht.");';
				break;
			case 6:
				$js .= 'alert("Das erste Bild kann nur der Käufer hochladen.");';
				break;
			case 7:
				header('Location: armband?name='.urlencode($statistics->brid2name($_POST['registerpic_brid'])).'&picposted='.$pic_registered);
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
	if (isset($_POST['reg_br']) && $_POST['registerbr_submit'] == "Armband registrieren") {
			$bracelet_registered = $user->registerbr($_POST['reg_br']);
			//Rückmeldung zu Armband-registrieren anzeigen
			if(isset($bracelet_registered)) {
				switch ($bracelet_registered) {
					case 0:
						$js .= 'alert("Dieses Armband gibt es nicht.");';
						break;
					case 1:
						//$js .= 'alert("Armband erfolgreich registriert.");';
						header('Location: profil');
						break;
					case 2:
						$js .= 'alert("Dieses Armband wurde schon auf dich registriert.");';
						break;
					case 3:
						$js .= 'alert("Es ist ein Fehler aufgetreten, bitte kontaktiere den Support(support@placelet.de).");';
						break;
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
?>
			<article class="mainarticles bottom_border_green">
				<div class="green_line mainarticleheaders line_header"><h1><?php echo $pagename[$page];?></h1></div>
<?php
if(isset($loginattempt)) {
?>
				Der eingegebene Benutzername oder das Passwort waren falsch.<br><br>
				<form name="login" id="form_login" action="<?php echo $friendly_self; ?>" method="post">
					<table style="border: 1px solid black">
						<tr>
							<td><label for="login">Benutzername&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
							<td><input type="text" name="login" id="login" size="20" maxlength="30" pattern=".{4,15}" title="Min.4 - Max.15" placeholder="Benutzername" required></td>
						</tr>
						<tr>
							<td><label for="password">Passwort</label></td>
							<td><input type="password" name="password" id="password" class="password"  size="20" maxlength="30" pattern=".{6,30}" title="Min.6 - Max.30" value="!§%&$%&/%§$" required></td>
						</tr>
						<tr>
							<td><input type="submit" value="Login"></td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</form><br>
<?php
}elseif(isset($postpic)) {
?>
				<form id="registerpic" name="registerpic" class="registerpic" action="<?php echo $friendly_self; ?>" method="post" enctype="multipart/form-data">
					<span style="font-family: Verdana, Times"><strong style="color: #000;">Bild</strong> posten</span><br><br>
					
					<label for="registerpic_brid" class="label_registerpic_brid">Armband ID:</label><br>
					<input type="text" name="registerpic_brid" value="<?php if(isset($postpic)) if($postpic != 'true') echo urldecode($postpic);?>" required><br>
					
					<label for="registerpic_title" class="label_registerpic_title">Titel:</label><br>
					<input type="text" name="registerpic_title" class="registerpic_title" size="20" maxlength="35" placeholder="Titel"  value="<?php if(isset($_GET['title'])) echo urldecode($_GET['title']);?>"required><br>
					
					<label for="registerpic_city" class="label_registerpic_city">Stadt:</label><br>
					<input type="text" name="registerpic_city" class="registerpic_city" id="registerpic_city" size="20" maxlength="30" placeholder="Stadt" value="<?php if(isset($_GET['city'])) echo urldecode($_GET['city']);?>" required><br>
					
					<label for="registerpic_country" class="label_registerpic_country">Land:</label><br>
					<input type="text" name="registerpic_country" class="registerpic_country" id="registerpic_country" size="20" maxlength="30" placeholder="Land" value="<?php if(isset($_GET['country'])) echo urldecode($_GET['country']);?>" required><br>
					
					<div id="pos" style="width:800px; height:600px;">
						Deine Position wird ermittelt...
					</div>
					<input type="hidden" name="latitude" id="latitude" value="0">
					<input type="hidden" name="longitude" id="longitude" value="0">
					
					<label for="registerpic_description" class="registerpic_description">Beschreibung:</label><br>
					<textarea name="registerpic_description" class="registerpic_description" rows="8" cols="40" maxlength="1000" required><?php if(isset($_GET['descr'])) echo urldecode(str_replace("ujhztg", "\r\n", $_GET['descr']));?></textarea><br>
<?php
		//$publickey = "6LfIVekSAAAAAJddojA4s0J4TVf8P_gS2v1zv09P";
		//echo recaptcha_get_html($publickey);
?>
					<input type="file" name="registerpic_file" id="registerpic_file" maxlength="<?php echo $max_file_size; ?>" required><br>
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>">
					<input type="submit" name="registerpic_submit" value="Bild posten"><br>
					<img id="image_preview" src="./img/placeholder.png" style="background-repeat: no-repeat; background-position: center; max-height:0px">
				</form>
			</article>
<?php
}elseif(isset($registerbr)) {
	if($user->login) {
?>
				<form name="registerbr" action="<?php echo $friendly_self; ?>" method="post">
					<label for="reg_br">Armband registrieren</label>
					<input type="text" name="reg_br" id="reg_br" class="input_text" size="20" maxlength="10" placeholder="Armband ID" value="<?php if(isset($_GET["registerbr"])) {echo $_GET["registerbr"];}// else {echo "Armband ID";}?>">
					<input type="submit" name="registerbr_submit" value="Armband registrieren">
				</form>
<?php
	}else {//Wenn man eine Armband ID eingegeben hat, kann man sich einloggen
?>
				Bitte Logge dich ein oder erstelle dir einen neuen Account,<br>
				um dein Armband Nr. <span style="color: #000; font-style: italic;"><?php echo $registerbr; ?></span> registrieren:<br>
				<form name="login" id="form_login" action="<?php echo $friendly_self.'?registerbr='.$registerbr; ?>" method="post">
					<table style="border: 1px solid black">
						<tr>
							<td><label for="login">Benutzername&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
							<td><input type="text" name="login" id="login" size="20" maxlength="30" placeholder="Benutzername" pattern=".{6,30}" title="Min.4 - Max.15" required></td>
						</tr>
						<tr>
							<td><label for="password">Passwort</label></td>
							<td><input type="password" name="password" id="password" class="password"  size="20" maxlength="30" pattern=".{6,30}" title="Min.6 - Max.30" value="!§%$$%\/%§$" required></td>
						</tr>
						<tr>
							<td><input type="submit" value="Login"></td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</form><br>
<?php
	}
}elseif(!$user->login) {
?>
				<form name="reg" id="form_reg" action="<?php echo $friendly_self; ?>" method="post">
					<table style="border: 1px solid black">
						<tr>
							<td><label for="reg_login">Benutzername</label></td>
							<td><input type="text" name="reg_login" id="reg_login" class="input_text" size="20" maxlength="30" placeholder="Benutzername" pattern=".{4,15}" title="Min.4 - Max.15" required></td>
						</tr>
						<tr>
							<td><label for="reg_email">Email-Adresse</label></td>
							<td><input type="email" name="reg_email" id="reg_email" class="input_text" size="20" maxlength="30" placeholder="Email-Adresse" required></td>
						</tr>
						<tr>
							<td><label for="reg_password">Passwort</label></td>
							<td><input type="password" name="reg_password" id="reg_password" class="password"  size="20" maxlength="30" pattern=".{6,30}" title="Min.6 - Max.30" value="!§%$$%\/%§$" required></td>
						</tr>
						<tr>
							<td><label for="reg_password2">Passwort wiederholen</label></td>
							<td><input type="password" name="reg_password2" id="reg_password2" class="password" size="20" maxlength="30" pattern=".{6,30}" title="Min.6 - Max.30" value="!§%$$%\/%§$" required></td>
						</tr>
						<tr>
							<td><input type="hidden" name="new_register" value="true"><input type="submit" value="Registrieren"></td>
							<td>&nbsp;</td>
						</tr>
					</table>
				<p>Deine E-Mail-Adresse wird nicht an Dritte weitergegeben. Wir benötigen sie zum Beispiel, um dir auf Anfrage dein Passwort senden zu können.</p>
				</form>
<?php
}
?>
		</article>