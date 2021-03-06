<?php
session_start(); //Session starten
include_once('../connection.php');
include_once('../functions.php');
function tprofile_pic($userid) {
	if(file_exists('../../pictures/profiles/'.$userid.'.jpg')) return '/pictures/profiles/'.$userid.'.jpg';
		else return '/img/profil_pic_small.png';
}
require_once('../user.php');
$lang = simplexml_load_file('../../text/translations.xml');
if(isset($_POST['eng'])) $lng = $_POST['eng'];
if(isset($_SESSION['user'])){
	$user = new User($_SESSION['user'], $db);
	$checklogin = $user->logged;
}else{
	$user = new User(false, $db);
	$checklogin = false;
}
$statistics = new Statistics($db, $user);
$cv = $_POST['contentVar'];
$startVal = $_POST['startVal'];
switch($cv){
	case '+':
		$startVal++;
		break;
	case '-':
		$startVal--;
		break;
}
$systemStats = $statistics->systemStats(0, $startVal+3);
$bracelets_displayed = $systemStats['recent_brids'];
foreach($bracelets_displayed as $key => $val) {
	$stats[$key] = $statistics->bracelet_stats($val, true);
}
$i = $startVal;
if($i != 1) {
?>
						<div class="pseudo_link float_left" onClick="return false" onMouseDown="javascript:change_pic('-', <?php echo $startVal; ?>);"><img src="/cache.php?f=/img/prev.png" alt="prev"></div>
<?php
}
if(isset( $stats[$i+1][$systemStats['recent_picids'][$i+1]]['picid'])) {
?>
					    <div class="pseudo_link float_right" onClick="return false" onMouseDown="javascript:change_pic('+', <?php echo $startVal; ?>);"><img src="/cache.php?f=/img/next.png" alt="next"></div> 
<?php
}		$stmt = $db->prepare('SELECT id FROM pictures WHERE picid = :picid AND brid = :brid');
		$stmt->execute(array('picid' => $stats[$i-1][$systemStats['recent_picids'][$i-1]]['picid'], 'brid' => $bracelets_displayed[$i-1]));
		$rowid = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$stmt = $db->prepare('SELECT id FROM pictures WHERE picid = :picid AND brid = :brid');
		$stmt->execute(array('picid' => $stats[$i][$systemStats['recent_picids'][$i]]['picid'], 'brid' => $bracelets_displayed[$i]));
		$rowid2 = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$stmt = $db->prepare('SELECT id FROM pictures WHERE picid = :picid AND brid = :brid');
		$stmt->execute(array('picid' => $stats[$i+1][$systemStats['recent_picids'][$i+1]]['picid'], 'brid' => $bracelets_displayed[$i+1]));
		$rowid3 = $stmt->fetch(PDO::FETCH_ASSOC);
?>
						<div id="central_newest_pic">
                            <div class="more_imgs">
                                    <?php if(isset($stats[$i-1][$systemStats['recent_picids'][$i-1]]['picid'])){?><img class="fake_img pseudo_link" src="/cache.php?f=/pictures/bracelets/thumb<?php echo '-'.$rowid['id'].'.jpg'; ?>" alt="-" onMouseDown="javascript:change_pic('-', <?php echo $startVal;?>);"><?php }else { ?><img class="fake_img pseudo_link" src="#" alt="-"><?php } ?><br>
                                    <?php if(isset($stats[$i][$systemStats['recent_picids'][$i]]['picid'])){?><img class="fake_img pseudo_link" src="/cache.php?f=/pictures/bracelets/thumb<?php echo '-'.$rowid2['id'].'.jpg'; ?>" alt="-"><?php } ?><br>
                                    <?php if(isset($stats[$i+1][$systemStats['recent_picids'][$i+1]]['picid'])){?><img class="fake_img pseudo_link" src="/cache.php?f=/pictures/bracelets/thumb<?php echo '-'.$rowid3['id'].'.jpg'; ?>" alt="-" onMouseDown="javascript:change_pic('+', <?php echo $startVal;?>);"><?php } ?>
    					    </div>
                            <a href="/pictures/bracelets/pic<?php echo '-'.$rowid2['id'].'.'.$stats[$i][$systemStats['recent_picids'][$i]]['fileext']; ?>" data-lightbox="pictures" title="<?php echo $stats[$i][$systemStats['recent_picids'][$i]]['city'].', '.$stats[$i][$systemStats['recent_picids'][$i]]['contry']; ?>" class="connect_thumb_link">
    							<img src="/cache.php?f=/pictures/bracelets/thumb<?php echo '-'.$rowid2['id'].'.jpg'; ?>" alt="<?php echo $stats[$i][$systemStats['recent_picids'][$i]]['city'].', '.$stats[$i][$systemStats['recent_picids'][$i]]['country']; ?>" class="connect_thumbnail">
    						</a>
    						
							<table class="connect_pic-info">
								<tr>
									<th>Armband</th>
									<td><strong><?php echo '<a href="/'.bracename2ids($statistics->brid2name($bracelets_displayed[$i])).'">'.htmlentities($statistics->brid2name($bracelets_displayed[$i])).'</a>'; ?></strong></td>
								</tr>
								<tr>
									<th>Datum</th>
									<td><?php echo date('d.m.Y H:i', $stats[$i][$systemStats['recent_picids'][$i]]['date']); ?> Uhr</td>
								</tr>
								<tr>
									<th>Ort</th>
									<td><?php echo $stats[$i][$systemStats['recent_picids'][$i]]['city'].', '.$stats[$i][$systemStats['recent_picids'][$i]]['country']; ?></td>
								</tr>
	<?php
				if($stats[$i][$systemStats['recent_picids'][$i]]['user'] != NULL) {
	?>
								<tr>
									<th>Uploader</th>
									<td><img src="/cache.php?f=<?php echo tprofile_pic($stats[$i][$systemStats['recent_picids'][$i]]['userid']); ?> " width="20" class="border999">&nbsp;
                                        <a href="/profil?user=<?php echo urlencode(html_entity_decode($stats[$i][$systemStats['recent_picids'][$i]]['user'])); ?>"><?php echo $stats[$i][$systemStats['recent_picids'][$i]]['user']; ?></a></td>
								</tr>
	<?php
				 }
	?>
							</table> 
							<!--<p class="pic-desc">
								<span class="desc-header"><?php echo $stats[$i][$systemStats['recent_picids'][$i]]['title']; ?></span><br>
								<?php echo $stats[$i][$systemStats['recent_picids'][$i]]['description']; ?>
							</p>              -->
						</div>
						
						<!--<img src="/img/loading.gif" id="loading" alt="loading..." style="display: block; margin: 0 auto; display: none; position: relative; right: 25.5%;">     -->