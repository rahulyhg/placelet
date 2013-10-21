<?php

include_once('../connection.php');
include_once('../user.php');

$systemStats = User::systemStats(3, $_GET['q'], $db);
//hier werden die Armbänder bestimmt, die angezeigt werden
$bracelets_displayed = $systemStats['recent_brids'];
foreach($bracelets_displayed as $key => $val) {
	$stats[$key] = array_merge(User::bracelet_stats($val, $db), User::picture_details($val, $db));
}
if(isset($stats[$_GET['q'] - 2]))
echo'<hr style="clear: both;"><!--HR über dem 1. nachgeladenen Bild-->';
			for ($i = $_GET['q'] - 2; $i <= $_GET['q']; $i++) {
				if(!isset($stats[$i])){
					break;}
?>
        	<div style="width: 100%;">
                <div style="width: 70%; float: left;">

                    <a href="pictures/bracelets/pic<?php echo '-'.$bracelets_displayed[$i].'-'.$stats[$i][0]['picid'].'.'.$stats[$i][0]['fileext']; ?>" data-lightbox="pictures" title="<?php echo $stats[$i][0]['city'].', '.$stats[$i][0]['country']; ?>" >
                        <img src="pictures/bracelets/thumb<?php echo '-'.$bracelets_displayed[$i].'-'.$stats[$i][0]['picid'].'.'.$stats[$i][0]['fileext']; ?>" alt="<?php echo $stats[$i][0]['city'].', '.$stats[$i][0]['country']; ?>" class="start-picture">
                        <img src="img/triangle.png" alt="" style="margin-top: 10px; float: left">
                    </a>
                    
                    <table class="start-info">
                    	<tr>
                            <th>Datum</th>
                            <td><?php echo date('d.m.Y H:i', $stats[$i][0]['date']); ?></td>
                        </tr>
                    	<tr>
                            <th>Ort</th>
                            <td><?php echo $stats[$i][0]['city'].', '.$stats[$i][0]['country']; ?></td>
                        </tr>
<?php
				if($stats[$i][0]['user'] != NULL) {
?>
                        <tr>
                        	<th>Uploader</th>
                            <td><?php echo $stats[$i][0]['user']; ?></td>
                        </tr>
<?php
                 }
?>
                    </table> 
                    
                    <p class="start-desc">
                        <span class="desc-header">Beschreibung:</span><br>
                        <?php echo $stats[$i][0]['description']; ?>      
                        <br><br>
                        <span class="pseudo_link toggle_comments" id="toggle_comment<?php echo $i;?>">Kommentare zeigen</span>
                    </p>
                    
                </div>
                <aside class="bracelet-props side_container">
                    <table>
                        <tr>
                            <td><strong>Armband ID</strong></td>
                            <td><strong><?php echo '<a href="armband?id='.$bracelets_displayed[$i].'">'.$bracelets_displayed[$i].'</a>'; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Käufer</td>
                            <td><?php echo $stats[$i]['owner']; ?></td>
                        </tr>
                        <tr>
                            <td>Anzahl Besitzer</td>
                            <td><?php echo $stats[$i]['owners']; ?></td>
                        </tr>
                        <tr>
                            <td>Letzter Ort</td>
							<td><?php echo $stats[$i][0]['city'].', '.$stats[$i][0]['country']; ?></td>
                        </tr>
                    	<tr>
                            <td>Station Nr.</td>
                            <td><?php echo $stats[$i][0]['picid']; ?></td>
                        </tr>
                    </table>
                </aside>
			</div>
            <div class="comments" id="comment<?php echo $i;?>">
<?php
				for ($j = 1; $j <= count($stats[$i][0])-8; $j++) {
?>
                    <strong><?php echo $stats[$i][0][$j]['user']; ?></strong>, <?php echo 'vor x Tagen ('.date('H:i d.m.Y', $stats[$i][0][$j]['date']).')'; ?>
                    <p><?php echo $stats[$i][0][$j]['comment']; ?></p> 
                    <hr style="border: 1px solid white;">  
<?php 
				}
?>   
                <form name="comment[<?php echo $i; ?>]" class="comment_form" action="<?php echo $friendly_self; ?>" method="post">
                    <span style="font-family: Verdana, Times"><strong style="color: #000;">Kommentar</strong> schreiben</span><br><br>
                    <label for="comment_user[<?php echo $i; ?>]" class="label_comment_user">Name: </label>
                    <input type="text" name="comment_user[<?php echo $i; ?>]" class="comment_user" size="20" maxlength="15"<?php if (isset($user->login)){echo ' value="'.$user->login.'" ';} ?>placeholder="Name" required><br>  
                    <label for="comment_content[<?php echo $i; ?>]" class="label_comment_content">Dein Kommentar:</label><br>
                    <textarea name="comment_content[<?php echo $i; ?>]" class="comment_content" rows="6" maxlength="1000" required></textarea><br><br>
                    <input type="hidden" name="comment_brid[<?php echo $i; ?>]" value="<?php echo $bracelets_displayed[$i];?>">
                    <input type="hidden" name="comment_picid[<?php echo $i; ?>]" value="<?php echo $stats[$i][0]['picid']; ?>">
                    <input type="hidden" name="comment_form" value="<?php echo $i; ?>">
                    <input type="submit" name ="comment_submit[<?php echo $i; ?>]" value="Kommentar abschicken" class="submit_comment">
                </form>
            </div>
                 
<?php
					if (isset($stats[$i+1])) {
?><!----HR----><hr style="clear: both;"><?php	
					}
				}
				
				
				
				
?>