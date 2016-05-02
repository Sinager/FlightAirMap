<?php
require_once('require/class.Connection.php');
require_once('require/class.Spotter.php');
require_once('require/class.Language.php');
$Spotter = new Spotter();
$sort = filter_input(INPUT_GET,'sort',FILTER_SANITIZE_STRING);
if (isset($_GET['date'])) $spotter_array = $Spotter->getSpotterDataByDate($_GET['date'],"0,1", $sort);
else $spotter_array = array();

if (!empty($spotter_array))
{
	$title = sprintf(_("Most Common Aircraft on %s"),date("l F j, Y", strtotime($spotter_array[0]['date_iso_8601'])));
	require_once('header.php');
	print '<div class="select-item">';
	print '<form action="'.$globalURL.'/date" method="post">';
	print '<label for="date">Select a Date</label>';
	print '<input type="text" id="date" name="date" value="'.$_GET['date'].'" size="8" readonly="readonly" class="custom" />';
	print '<button type="submit"><i class="fa fa-angle-double-right"></i></button>';
	print '</form>';
	print '</div>';

	print '<div class="info column">';
	print '<h1>'.sprintf(_("Flights from %s"),date("l F j, Y", strtotime($spotter_array[0]['date_iso_8601']))).'</h1>';
	print '</div>';

	include('date-sub-menu.php');
	print '<div class="column">';
	print '<h2>'._("Most Common Aircraft").'</h2>';
	print '<p>'.sprintf(_("The statistic below shows the most common aircrafts of flights on <strong>%s</strong>."),date("l F j, Y", strtotime($spotter_array[0]['date_iso_8601']))).'</p>';

	$aircraft_array = $Spotter->countAllAircraftTypesByDate($_GET['date']);
	if (!empty($aircraft_array))
	{
		print '<div class="table-responsive">';
		print '<table class="common-type table-striped">';
		print '<thead>';
		print '<th></th>';
		print '<th>'._("Aircraft Type").'</th>';
		print '<th>'._("# of times").'</th>';
		print '<th></th>';
		print '</thead>';
		print '<tbody>';
		$i = 1;
		foreach($aircraft_array as $aircraft_item)
		{
			print '<tr>';
			print '<td><strong>'.$i.'</strong></td>';
			print '<td>';
			print '<a href="'.$globalURL.'/aircraft/'.$aircraft_item['aircraft_icao'].'">'.$aircraft_item['aircraft_name'].' ('.$aircraft_item['aircraft_icao'].')</a>';
			print '</td>';
			print '<td>';
			print $aircraft_item['aircraft_icao_count'];
			print '</td>';
			print '<td><a href="'.$globalURL.'/search?aircraft='.$aircraft_item['aircraft_icao'].'&start_date='.$_GET['date'].'+00:00&end_date='.$_GET['date'].'+23:59">'._("Search flights").'</a></td>';
			print '</tr>';
			$i++;
		}
		print '<tbody>';
		print '</table>';
		print '</div>';
	}
	print '</div>';
} else {
	$title = _("Unknown Date");
	require_once('header.php');
	print '<h1>'._("Error").'</h1>';
	print '<p>'._("Sorry, this date does not exist in this database. :(").'</p>'; 
}

require_once('footer.php');
?>