<?php
include_once('./other/functions.php'); //Einbinden einer Datei, welche verschiedene PHP-Funktionen bereitstellt, wie z.B. eine Überprüfung, ob die hochgeladene Datei wirklich ein Bild ist
//Hier werden Cookies überprüft gesetzt usw.

//--//
$title = 'Placelet';  // Kann von anderen Dateien geändert werden, z.B. in "Placelet Shop"



//Ich denke, dass der Head immer gleich sein wird, auf Wunsch kann das aber geändert werden
echo'<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="Placelet shop and image service">
<meta name="keywords" content="Placelet, Placelet Shop, Global Bracelet, Travel & Connect, Global Bracelet. Travel & Connect, Travel and Connect, Global Bracelet. Travel and Connect">
<meta name="author" content="Roman S., Danial S., Julian Z.">
<link href="other/style.css" rel="stylesheet" type="text/css">
<title>'.$title.'</title>
</head>
<body>
';
include_once('./pages/'.$page.'.php');

echo'</body>
</html>';
?>