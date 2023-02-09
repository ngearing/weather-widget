<?php
// $temps        = array();
// $min_temp     = $day[0]->main->temp;
// $max_temp     = $day[0]->main->temp;
// $max_temp_key = 0;
// foreach ( $day as $dkey => $forcast ) {
// 	$temps[ $dkey ] = $forcast->main->temp;
// 	if ( $forcast->main->temp < $min_temp ) {
// 		$min_temp = $forcast->main->temp;
// 	}
// 	if ( $forcast->main->temp > $max_temp ) {
// 		$max_temp     = $forcast->main->temp;
// 		$max_temp_key = $dkey;
// 	}
// }

// $day_forcast = $day[ $max_temp_key ];

// $conditions = implode( '. ', array_column( $day_forcast->weather, 'description' ) );
$codes = new WeatherCodes();
$icons = new WeatherIcons();
$week = json_decode($data);

foreach( $week->data->timelines[0]->intervals as $day ) :
?>

<li class="">

	<?php
	printf(
		'<p class="date">%s</p>',
		date( 'D, M d', strtotime( $day->startTime ) )
	);

	printf(
		'<h4 class="temp">
			<img src="%s"/>
			%d / %d<span class="icon-c">°C</span>
		</h4>',
		$icons->get($day->values->weatherCode),
		$day->values->temperatureMax,
		$day->values->temperatureMin
	);

	printf(
		'<p class="conditions">%s</p>',
		$codes->get($day->values->weatherCode)
	);
	?>

</li>

<?php
endforeach;
?>

