<?php
require_once('require/class.Connection.php');
require_once('require/class.Spotter.php');;
require_once('require/class.Language.php');

if (!isset($_GET['date'])){
	header('Location: '.$globalURL.'');
} else {
	$Spotter = new Spotter();
	
	//calculuation for the pagination
	if(!isset($_GET['limit']))
	{
		$limit_start = 0;
		$limit_end = 25;
		$absolute_difference = 25;
	}  else {
		$limit_explode = explode(",", $_GET['limit']);
		$limit_start = $limit_explode[0];
		$limit_end = $limit_explode[1];
		if (!ctype_digit(strval($limit_start)) || !ctype_digit(strval($limit_end))) {
			$limit_start = 0;
			$limit_end = 25;
		}
	}
	$absolute_difference = abs($limit_start - $limit_end);
	$limit_next = $limit_end + $absolute_difference;
	$limit_previous_1 = $limit_start - $absolute_difference;
	$limit_previous_2 = $limit_end - $absolute_difference;
	
	$page_url = $globalURL.'/date/'.$_GET['date'];
	
	$sort = filter_input(INPUT_GET,'sort',FILTER_SANITIZE_STRING);
	
	if (isset($_GET['sort'])) 
	{
		$spotter_array = $Spotter->getSpotterDataByDate($_GET['date'],$limit_start.",".$absolute_difference, $sort);
	} else {
		$spotter_array = $Spotter->getSpotterDataByDate($_GET['date'],$limit_start.",".$absolute_difference);
	}
	
	
	if (!empty($spotter_array))
	{
		date_default_timezone_set($globalTimezone);
		$title = sprintf(_("Detailed View for flights from %s"),date("l F j, Y", strtotime($spotter_array[0]['date_iso_8601'])));

		require_once('header.php');
		print '<div class="select-item">';
		print '<form action="'.$globalURL.'/date" method="post">';
		print '<label for="date">'._("Select a Date").'</label>';
		print '<input type="text" id="date" name="date" value="'.$_GET['date'].'" size="8" readonly="readonly" class="custom" />';
		print '<button type="submit"><i class="fa fa-angle-double-right"></i></button>';
		print '</form>';
		print '</div>';

		print '<div class="info column">';
		print '<h1>'.sprintf(_("Flights from %s"),date("l F j, Y", strtotime($spotter_array[0]['date_iso_8601']))).'</h1>';
		print '</div>';

		include('date-sub-menu.php');
		print '<div class="table column">';
		print '<p>'.sprintf(_("The table below shows the detailed information of all flights on <strong>%s</strong>."),date("l M j, Y", strtotime($spotter_array[0]['date_iso_8601']))).'</p>';
 
		include('table-output.php');
		print '<div class="pagination">';
		if ($limit_previous_1 >= 0)
		{
			print '<a href="'.$page_url.'/'.$limit_previous_1.','.$limit_previous_2.'/'.$_GET['sort'].'">&laquo;'._("Previous Page").'</a>';
		}
		if ($spotter_array[0]['query_number_rows'] == $absolute_difference)
		{
			print '<a href="'.$page_url.'/'.$limit_end.','.$limit_next.'/'.$_GET['sort'].'">'._("Next Page").'&raquo;</a>';
		}
		print '</div>';
		print '</div>';
	} else {
		$title = _("Unknown Date");
		require_once('header.php');
		print '<h1>'._("Error").'</h1>';
		print '<p>'._("Sorry, this date does not exist in this database. :(").'</p>'; 
	}
}

require_once('footer.php');
?>