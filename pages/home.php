<!--CONNECT-LEISTE-->
            <marquee scrollamount="4" scrolldelay="0" style="font-family: Arial" onmouseover='this.stop()' onmouseout='this.start()'>
            <?php echo $lang->home->marqueetext->$lng; ?>
            </marquee>
            <?php /*<!--<div class="hint">
                <h1><?php if($lng == 'en') echo 'Notice'; elseif($lng == 'de') echo 'Hinweis';?></h1>
                <p><?php if($lng == 'en') echo 'Due to technical difficulties we had to temporarily disable the registration. We are intensely working on it. Sorry for the inconveniences.';
					 elseif($lng == 'de') echo 'Aufgrund technischer Schwierigkeiten musste die Registrierung temporär deaktiviert werden. Wir arbeiten an einer schnellstmöglichen Beseitigung des Problems.';?></p>
            </div>--> */ ?>
			<div id="connect_leiste">
                <div class="connect_box" id="uploads_box">
                    <h1 class="headers pseudo_link" id="header_1"><span class="header_arrow1 arrow_right"></span>&nbsp;<?php echo $lang->stats->neuesterupload[$lng."-title"]; ?></h1>
                        <div id="connectbox_1">
<?php
if($systemStats['total_posted'] > 1) {
?>
                            <div class="changepic pseudo_link float_right" onClick="return false" onMouseDown="javascript:change_pic('+', '1');"><img src="/cache.php?f=/img/next.png" alt="next"></div>	
<?php
}		$stmt = $db->prepare('SELECT id FROM pictures WHERE picid = :picid AND brid=:brid');
		$stmt->execute(array('picid' => $stats[1][$systemStats['recent_picids'][1]]['picid'], 'brid' => $bracelets_displayed[1]));
		$rowid = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$stmt = $db->prepare('SELECT id FROM pictures WHERE picid = :picid AND brid=:brid');
		$stmt->execute(array('picid' => $stats[2][$systemStats['recent_picids'][2]]['picid'], 'brid' => $bracelets_displayed[2]));
		$rowid2 = $stmt->fetch(PDO::FETCH_ASSOC);
?>
                            <div id="central_newest_pic">
                            	<div class="more_imgs">
                            	    <img class="fake_img pseudo_link" src="#" alt="-"><br>
                                    <img class="fake_img pseudo_link" src="/cache.php?f=/pictures/bracelets/thumb<?php echo '-'.$rowid['id'].'.jpg'; ?>" alt="-"><br>
                                    <img class="fake_img pseudo_link" src="/cache.php?f=/pictures/bracelets/thumb<?php echo '-'.$rowid2['id'].'.jpg'; ?>" alt="-" onMouseDown="javascript:change_pic('+', 1);">
        					   </div>
                                                           			
        						<a href="/pictures/bracelets/pic<?php echo '-'.$rowid['id'].'.'.$stats[1][$systemStats['recent_picids'][1]]['fileext']; ?>" data-lightbox="pictures" title="<?php echo $stats[1][$systemStats['recent_picids'][1]]['city'].', '.$stats[1][$systemStats['recent_picids'][1]]['country']; ?>" class="connect_thumb_link">							
        						<img src="/cache.php?f=/pictures/bracelets/thumb<?php echo '-'.$rowid['id'].'.jpg'; ?>" alt="<?php echo $stats[1][$systemStats['recent_picids'][1]]['city'].', '.$stats[1][$systemStats['recent_picids'][1]]['country']; ?>" class="connect_thumbnail" style="max-height: 175px;">
        						</a>
        							<table class="connect_pic-info">
        								<tr>
        									<th><?php echo $lang->pictures->armband->$lng; ?></th>
        									<td><strong><?php echo '<a href="/'.bracename2ids($statistics->brid2name($bracelets_displayed[1])).'">'.htmlentities($statistics->brid2name($bracelets_displayed[1])).'</a>'; ?></strong></td>
        								</tr>
        								<tr>
        									<th><?php echo $lang->pictures->datum->$lng; ?></th>
        									<td><?php echo date('d.m.Y H:i', $stats[1][$systemStats['recent_picids'][1]]['date'])." ".$lang->misc->uhr->$lng; ?></td>
        								</tr>
        								<tr>
        									<th><?php echo $lang->pictures->ort->$lng; ?></th>
        									<td><?php echo $stats[1][$systemStats['recent_picids'][1]]['city'].', '.$stats[1][$systemStats['recent_picids'][1]]['country']; ?></td>
        								</tr>
<?php
                                    				if($stats[1][$systemStats['recent_picids'][1]]['user'] != NULL) {
?>
        								<tr>
        									<th><?php echo $lang->pictures->uploader->$lng; ?></th>
        									<td><img src="/cache.php?f=<?php echo profile_pic($stats[1][$systemStats['recent_picids'][1]]['userid']); ?>" alt="profile pic" width="20" style="border: 1px #999 solid;">&nbsp;
                                                <a href="/profil?user=<?php echo $stats[1][$systemStats['recent_picids'][1]]['user']; ?>"><?php echo $stats[1][$systemStats['recent_picids'][1]]['user']; ?></a></td>
        								</tr>
<?php
                                    				 }
?>
        							</table>
    					    </div>	
    						<?php //<!--<img src="/img/loading.gif" id="loading" alt="loading..." style="display: block; margin: 0 auto; display: none; right: 25.5%;">    -->?>
    					</div>
                </div>
                <div class="connect_box" id="submit_box">
                    <h1 class="headers pseudo_link" id="header_2"><span class="header_arrow2 arrow_right"></span>&nbsp;<?php echo $lang->stats->neuesbild[$lng."-title"]; ?></h1>
                    <div id="connectbox_2">
                        <form action="/login" method="get">
                            <p>
        						<?php echo $lang->stats->neuesbild->ideingeben->$lng; ?><br>						
    							<input name="postpic" type="text" maxlength="6" size="6" pattern="\w{6}" title="<?php echo $lang->community->sixcharacters->$lng; ?>" placeholder="ID...">
    							<input type="submit" value="<?php echo $lang->stats->neuesbild->button->$lng; ?>">    						
        					</p>
    					</form>
    					
                        <hr>
                        
                        <h1><?php echo $lang->stats->neuesarmband[$lng."-title"]; ?></h1>
                        <form action="/login" method="get">
                        <p>
    						<?php echo $lang->stats->neuesarmband->ideingeben->$lng; ?><br>
    						<input name="registerbr" type="text" maxlength="6" size="6" pattern="\w{6}" title="<?php echo $lang->community->sixcharacters->$lng; ?>" placeholder="ID...">
    						<input type="submit" value="<?php echo $lang->stats->neuesarmband->button->$lng; ?>">						 
    					</p>
    					</form>
    				</div>
                </div>
            </div>

<!--ERSTER ARTIKEL-->
			<article id="reisearmband" class="mainarticles bottom_border_blue">
				<div class="mainarticleheaders line_header blue_line"><h1><?php echo $lang->home->artikel1[$lng."-title"]; ?></h1></div>
				<?php if(isset($_GET['rund'])) echo '<div class="round_image" style="margin-bottom: 0.5em; background: url(/pictures/thumb-armband3.jpg)"></div>'; else { 
				$js.='var options = { $AutoPlay: true };
				var jssor_slider1 = new $JssorSlider$("slider1_container", options);';?>
				<div id="slider1_container" style="position: relative; width: 492px; height: 272px;">
						<div data-u="slides" style="cursor: move; overflow: hidden; width: 492px; height: 272px;">
							<div><img data-u="image" src="/cache.php?f=/pictures/thumb-armband3.jpg" alt="Armband"></div>
							<div><img data-u="image" src="/cache.php?f=/pictures/shop/thumb-2.jpg" alt="Armband"></div>
						</div>
					</div><br>
				<?php } //491 1920 <a href="/pictures/armband3.jpg" data-lightbox="armbaender" title="Armband"><img src="/pictures/thumb-armband3.jpg" alt="Armband" style="width: 100%;"></a>	?>
				<p>
                    <?php echo $lang->home->artikel1->paragraph[0]->$lng; ?>
				</p>
				<p>
                    <?php echo $lang->home->artikel1->paragraph[1]->$lng; ?>
                     
				</p>
			</article>                                                                                                        
<!--ZWEITER ARTIKEL-->
			<article id="kollektion" class="mainarticles bottom_border_green">
				<div class=" mainarticleheaders line_header green_line"><h1><?php echo $lang->home->artikel2[$lng."-title"]; ?></h1></div>
				<div class="responsive_16-9" style="margin-bottom: 1em;"><iframe width="560" height="315" src="http://www.youtube.com/embed/xtnbzTK2G8I" style="border: none"></iframe></div>
<?php
for($i = 0; $i < 6; $i++){
?>
				<p>
					<span class="highlighted kollektion_numbers"><?php echo $i+1; ?></span><?php echo $lang->home->artikel2->paragraph[$i]->$lng; ?>
				</p>
				<span class="arrow highlighted">&#11015;</span>
<?php
}
?>
				<br><?php echo $lang->home->artikel2->articlefooter->$lng; ?>
			</article>
<!--SIDEBAR-->
            <aside id="map_home" class="side_container">
				Bitte aktivieren sie Javascript um die Weltkarte mit allen Orten sehen zu können.
			</aside>
			<aside class="side_container" style="margin-top: 20px;">
				<h1>facebook</h1>
				<p><?php echo $lang->home->fbnews->$lng; ?></p>
				<div id="fb_plugin" class="fb-like-box" data-href="http://www.facebook.com/Placelet" data-width="200" data-height="190" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="true"></div>
			</aside>
			<aside class="side_container" style="margin-top: 20px;">
				<?php //<!--<h1>JUNIOR</h1>-->?>
				<img src="/cache.php?f=/img/JUNIOR_Logo.png" alt="JUNIOR" style="width: 80%; height: auto;">
				<p><?php echo $lang->home->JUNIOR->$lng; ?></p>
			</aside>