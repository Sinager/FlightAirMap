<?php
require_once('require/class.Connection.php');
require_once('require/class.Spotter.php');
require_once('require/class.Language.php');

if (isset($_POST['country']) && $_POST['country'] != "")
{
	header('Location: '.$globalURL.'/country/'.$_POST['country']);
} else {
	header('Location: '.$globalURL);
}
?>