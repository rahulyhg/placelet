 <article id="kontakt" class="mainarticles bottom_border_green">
<?php
foreach($_GET as $key => $val) {
	$_GET[$key] = clean_input($val);
}
if($user->login) {
	$username = $user->login;
}
//Userdetails ändern
if(isset($_POST['submit'])) {
	if($_POST['submit'] == 'Änderungen speichern' && $user->login) {
		$change_details = $user->change_details($_POST['change_firstname'], $_POST['change_lastname'], $_POST['change_email'], $_POST['change_old_pwd'], $_POST['change_new_pwd'], $user->login);
		echo '<script type="text/javascript">
				//$(document).ready(function(){
					alert("'.$change_details.'");
				//});
			  </script>';
	}
}
//Userdetails abrufen
if(isset($username) && $statistics->userexists($username)) {
	$userdetails = $statistics->userdetails($username);
	$armbaender = profile_stats($userdetails);
}
if ($user->login) {
?>
            <div class="green_line mainarticleheaders line_header"><h1>Deine Accounteinstellungen, <?php echo $user->login ?></h1></div>
            <div>
            	Dein Accountdetails:
				<form name="change" action="<?php echo $friendly_self; ?>" method="post">
					<table border="0">
						<tr>
							<th>Vorname:</th>
							<td><?php echo $userdetails['first_name']; ?></td>
							<td><input type="text" name="change_firstname" placeholder="Vorname"></td>
						</tr>
						<tr>
							<th>Nachname:</th>
							<td><?php echo $userdetails['last_name']; ?></td>
							<td><input type="text" name="change_lastname" placeholder="Nachname"></td>
						</tr>
						<tr>
							<th>E-Mail Adresse</th>
							<td><?php echo $userdetails['email']; ?></td>
							<td><input type="text" name="change_email" placeholder="E-Mail Adresse"></td>
						</tr>
						<tr>
							<th>Passwort</th>
							<td>&nbsp;</td>
							<td><input type="password" name="change_old_pwd" placeholder="altes Password"></td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<td>&nbsp;</td>
							<td><input type="password" name="change_new_pwd" placeholder="neues Passwort"></td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit" value="Änderungen speichern"></td>
						</tr>
					</table>
				</form>
            </div>
<?php 
} else {
?>
            <div class="green_line mainarticleheaders line_header"><h1>Profil</h1></div>
			<div style="float: left; margin-right: 2em;">
				Du kannst deine Accounteinstellungen nur ändern, wenn du eingeloggt bist.<br>
				Bitte logge dich ein:
				<form name="login" action="<?php echo $friendly_self; ?>" method="post">
					<table style="border: 1px solid black">
						<tr>
							<td><label for="login">Benutzername&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
							<td><input type="text" name="login" id="login" size="20" maxlength="15" placeholder="Benutzername" pattern=".{4,15}" title="Min.4 - Max.15" required></td>
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
				</form>
			</div>
			<div>
				Oder suche nach dem Profil von jemand anderem:
				<form action="<?php echo $friendly_self; ?>" method="get">
					<table style="border: 1px solid black; overflow: auto;">
						<tr>
							<td><input type="text" name="user" size="20" maxlength="15" placeholder="Benutzername" pattern=".{4,15}" title="Min.4 - Max.15" required></td>
							<td><input type="submit" value="Suchen"></td>
						</tr>
					</table>
				</form>
			</div>
            <div style="clear: both;">
            	&nbsp;
            </div>
<?php
}
?>
        </article>