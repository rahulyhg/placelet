<?php
if ($braceName != NULL) {
	if($stats['name'] !== false) {
		$eecho = '';
		$data = getlnlt($stats['name']);
		$central = '0, 0';
		$max = array(false, false, false, false, 0);
		$i=0;
		foreach($data as $pos){
		
			if($pos['latitude'] < $max[0] || $max[0] == false)
				$max[0] = $pos['latitude'];
			if($pos['latitude'] > $max[1] || $max[1] == false)
				$max[1] = $pos['latitude'];
			if($pos['longitude'] < $max[2] || $max[2] == false)
				$max[2] = $pos['longitude'];
			if($pos['longitude'] > $max[3] || $max[3] == false)
				$max[3] = $pos['longitude'];
				
			$js.= '
			var latlng'.$i.' = new google.maps.LatLng('.$pos['latitude'].', '.$pos['longitude'].');';
			$eecho .= '
			var marker'.$i.' = new google.maps.Marker({
				position: latlng'.$i.',
				map: map
			});';
			$i++;
		}
		
		$central = ($max[0]+($max[1]-$max[0])/2) . ', ' . ($max[2]+($max[3]-$max[2])/2);
		$max[4] = ($max[1]-$max[0]);
		if(($max[3]-$max[2]) > $max[4])
			$max[4] = ($max[3]-$max[2]);
			
		$zoom = 1;
		if($max[4] < 0.02)
			$zoom = 14;
		else if($max[4] < 0.0625)
			$zoom = 12;
		else if($max[4] < 0.125)
			$zoom = 11;
		else if($max[4] < 0.25)
			$zoom = 10;
		else if($max[4] < 0.5)
			$zoom = 9;
		else if($max[4] < 1)
			$zoom = 8;
		else if($max[4] < 2)
			$zoom = 7;
		else if($max[4] < 5)
			$zoom = 6;
		else if($max[4] < 6.5)
			$zoom = 5;
		else if($max[4] < 18)
			$zoom = 4;
		else if($max[4] < 40)
			$zoom = 3;
		else if($max[4] < 80)
			$zoom = 2;
					
		 $js.='
		function initialize() {
		  var mapOptions = {
			zoom: '.$zoom.',
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: new google.maps.LatLng('.$central.')
		  }
		var map = new google.maps.Map(document.getElementById("map_home"), mapOptions);
		'.$eecho.'
		}
		google.maps.event.addDomListener(window, "load", initialize);';
?>
			<article id="armband" class="mainarticles bottom_border_green">
				<div class="green_line mainarticleheaders line_header"><h1><?php echo $lang->pictures->armband->$lng; ?> <?php echo htmlentities($braceName); ?></h1></div>
				<?php if(!$user_subscribed) echo '<span class="pseudo_link float_right" id="show_sub">'.$lang->armband->abonnieren->$lng.'</span>'; ?>
				<a href="<?php echo 'login?postpic'; if($user->admin == true || $user->login == @$stats[$stats['owners'] - 1]['user'] || @$user->login == $stats['owner']) echo '='.$braceID.'" title="'.$braceID.'"';?>"><?php echo $lang->armband->bildposten->$lng; ?></a>
<?php
		if(!$user_subscribed) {
?>
				<form method="get" action="armband">
					<input type="submit" name="sub_submit" value="<?php echo $lang->pictures->armband->$lng; ?>" class="float_right sub_inputs" style="display: none;">
					<input name="sub_code" type="email"  size="20" maxlength="254" placeholder="<?php echo $lang->form->email->$lng; ?>" class="float_right sub_inputs" style="display: none;" required>
					<input type="hidden" name="sub" value="email">
					<input type="hidden" name="name" value="<?php echo urlencode($braceName); ?>" id="bracelet_name">
				</form>
<?php
		}
		for ($i = 0; $i < count($stats) - 4 && $i < 3; $i++) {
			if($i == 0) $last_pic = 'last';
				else $last_pic = 'middle';
?>
				<div style="width: 100%; overflow: auto;">
				<a href="armband?name=<?php echo urlencode($braceName); ?>&picid=<?php echo $stats[$i]['picid']; ?>&last_pic=<?php echo $last_pic; ?>&delete_pic=true" class="delete_button float_right" style="margin-top: 2em;" data-bracelet="<?php echo $braceName; ?>" title="<?php echo $lang->pictures->deletepic->$lng; ?>" onclick="confirmDelete('dasBild', this); return false;">X</a>
					<h3><?php echo $stats[$i]['city'].', '.$stats[$i]['country']; ?></h3>
					<a href="pictures/bracelets/pic<?php echo '-'.$braceID.'-'.$stats[$i]['picid'].'.'.$stats[$i]['fileext']; ?>" data-lightbox="pictures" title="<?php echo $stats[$i]['city'].', '.$stats[$i]['country']; ?>" class="thumb_link">
						<img src="img/triangle.png" alt="" class="thumb_triangle">
						<img src="pictures/bracelets/thumb<?php echo '-'.$braceID.'-'.$stats[$i]['picid'].'.jpg'; ?>" alt="<?php echo $stats[$i]['city'].', '.$stats[$i]['country']; ?>" class="thumbnail">
					</a>
					<table class="pic-info">
						<tr>
							<th><?php echo $lang->pictures->datum->$lng; ?></th>
							<td><?php echo date('d.m.Y H:i', $stats[$i]['date']); ?> Uhr</td>
						</tr>
<?php
			if($stats[$i]['user'] != NULL) {
?>
						<tr>
							<th><?php echo $lang->pictures->uploader->$lng; ?></th>
							<td><a href="profil?user=<?php echo urlencode(html_entity_decode(($stats[$i]['user']))); ?>"><?php echo $stats[$i]['user']; ?></a></td>
						</tr>
<?php
			 }
?>
					</table>
						
					<p class="pic-desc">
						<span class="desc-header"><?php echo $stats[$i]['title']; ?></span><br>
						<?php echo $stats[$i]['description']; ?>      
						<br><br>
						<span class="pseudo_link toggle_comments" id="toggle_comment<?php echo $i;?>" data-counts="<?php echo count($stats[$i])-11 ?>"><?php echo $lang->misc->comments->showcomment->$lng; ?> (<?php echo count($stats[$i])-11; ?>)</span>
					</p>
                    
					<div class="comments" id="comment<?php echo $i;?>">
<?php
			for ($j = 1; $j <= count($stats[$i])-11; $j++) {
				//Vergangene Zeit seit dem Kommentar berechnen
				$x_days_ago = days_since($stats[$i][$j]['date']);
				//Überprüfen, ob das Kommentar, was man löschen will das letzte ist.
				if(isset($stats[$i][$j + 1]['commid'])) {
					$last_comment = 'middle';
				}else {
					$last_comment = 'last';
				}
?>
							<a href="armband?name=<?php echo urlencode($braceName); ?>&last_comment=<?php echo $last_comment; ?>&commid=<?php echo $stats[$i][$j]['commid']; ?>&picid=<?php echo $stats[$i][$j]['picid']; ?>&delete_comm=true" class="delete_button float_right" data-bracelet="<?php echo $braceName; ?>" title="<?php echo $lang->pictures->deletecomment->$lng; ?>" onclick="confirmDelete('denKommentar', this); return false;">X</a>
                            <strong><?php echo $stats[$i][$j]['user']; ?></strong>, <?php echo $x_days_ago.' ('.date('H:i d.m.Y', $stats[$i][$j]['date']).')'; ?>
                            <p><?php echo $stats[$i][$j]['comment']; ?></p> 
                            <hr style="border: 1px solid white;">  
<?php 
			}
?>   
						<form name="comment[<?php echo $i; ?>]" class="comment_form" action="armband?name=<?php echo urlencode($braceName); ?>" method="post">
							<?php echo $lang->misc->comments->kommentarschreiben->$lng; ?><br>
							<label <?php if($user->login) echo 'style="display: none; " ';?>for="comment_user[<?php echo $i; ?>]" class="label_comment_user"><?php echo $lang->misc->comments->name->$lng; ?>: </label>
							<input <?php if($user->login) echo 'type="hidden" '; else echo 'type="text" ';?>name="comment_user[<?php echo $i; ?>]" <?php if($user->login == true) echo ' value="'.$user->login.'" ';?>class="comment_user" size="20" maxlength="15" placeholder="Name" pattern=".{4,15}" title="Min.4 - Max.15" required><?php if(!$user->login) echo '<br>'; ?>
							<label for="comment_content[<?php echo $i; ?>]" class="label_comment_content"><?php echo $lang->misc->comments->deinkommentar->$lng; ?>:</label><br>
							<textarea name="comment_content[<?php echo $i; ?>]" class="comment_content" rows="6" maxlength="1000" required></textarea><br><br>
							<input type="hidden" name="comment_brid[<?php echo $i; ?>]" value="<?php echo $braceID;?>">
							<input type="hidden" name="comment_picid[<?php echo $i; ?>]" value="<?php echo $stats[$i]['picid']; ?>">
							<input type="hidden" name="comment_form" value="<?php echo $i; ?>">
							<input type="submit" name="comment_submit[<?php echo $i; ?>]" value="<?php echo $lang->misc->comments->comment_button->$lng; ?>" class="submit_comment">
						</form>
					</div>
				</div>
<?php
			if ($i < count($stats) - 5 && $i < 2) {
?>
<!--~~~HR~~~~--><hr style="border-style: solid; height: 0px; border-bottom: 0; clear: both;">
<?php	
			}
			if(true) {
				
			}
		}
?>
<?php
		if ($bracelet_stats['owners'] == 0 ) {
			echo '<p>'.$lang->armband->nopics->$lng.'</p>';
		}
?>
			</article>
			<aside class="side_container" id="bracelet_props">
				<h1><?php echo $lang->armband->statistik->$lng; ?></h1>
				<table style="width: 100%;">
					<tr>
						<th><?php echo $lang->misc->comments->name->$lng; ?></th>
						<td><strong><?php echo htmlentities($stats['name']); if($owner) {?> </strong> <img src="img/edit.png" id="edit_name" class="pseudo_link"></td><?php } ?>
					</tr>
<?php
		if($owner) {
?> 
					<form method="post" action="armband?name=<?php echo urlencode($braceName); ?>">
						<tr>
							<td><input type="text" name="edit_name" placeholder="<?php echo $lang->armband->neuername->$lng; ?>" class="name_inputs" style="display: none;" size="20" maxlength="18" pattern=".{4,18}" title="Min.4 - Max.18" required></td>
							<td><input type="submit" value="<?php echo $lang->armband->aendern->$lng; ?>" class="name_inputs" name="edit_submit" style="display: none;"></td>
						</tr>
					</form>
<?php
		}
?>
					<tr>
						<td><?php echo $lang->pictures->kaeufer->$lng; ?></td>
						<td><a href="profil?user=<?php echo urlencode(htmlentities($stats['owner'])); ?>"><?php echo htmlentities($stats['owner']); ?></a></td>
					</tr>
					<tr>
						<td><?php echo $lang->armband->registriert_am->$lng; ?></td>
						<td><?php echo date('d.m.Y', $stats['date']); ?></td>
					</tr>
					<tr>
						<td><?php echo $lang->armband->besitzeranzahl->$lng; ?></td>
						<td><?php echo $stats['owners']; ?></td>
					</tr>
<?php
		if($bracelet_stats['owners'] != 0) {
?>
					<tr>
						<td><?php echo $lang->pictures->letzterort->$lng; ?></td>
						<td><?php echo $stats[0]['city']; ?>,</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo $stats[0]['country']; ?></td>
					</tr>
<?php
		}
?>
				</table>
			</aside>
<?php
		if($bracelet_stats['owners'] != 0) {
?>
			<aside id="map_home" class="side_container" style="margin-top: 20px;"><?php echo $lang->misc->activate_js->$lng; ?></aside>
<?php
		}
	}else {
?>
			<article id="armband" class="mainarticles bottom_border_green" style="width: 100%;">
				<div class="green_line mainarticleheaders line_header"><h1><?php echo $lang->armband->falschesarmband->$lng; ?></h1></div>
				<p>
					<?php echo $lang->armband->gibtsnicht->$lng; ?>
					<form action="armband">
						<input type="search" name="name" placeholder="<?php echo $lang->armband->armbandname->$lng; ?>" size="20" maxlength="18" pattern=".{4,18}" title="Min.4 - Max.18" value="<?php if(isset($_GET['name'])) echo $_GET['name']?>" required>
					</form>
					<br><?php echo $lang->armband->odersuchen->$lng; ?>:
					<form action="search" method="get">
						<input name="squery" type="search" placeholder="<?php echo $lang->form->suchen->$lng; ?>..." size="20" maxlength="18" value="<?php if(isset($_GET['name'])) echo $_GET['name']?>" required>
					</form>
				</p>
			</article>
<?php
	}
}else {
?>
			<article id="armband" class="mainarticles bottom_border_green" style="width: 100%;">
				<div class="green_line mainarticleheaders line_header"><h1>Falsche Seite</h1></div>
				<p>Du solltest nicht hier sein. Gehe einfach eine Seite <span class="pseudo_link" onclick="history.back(-1)">zurück.</span></p>
			</article>
<?php
}
?>