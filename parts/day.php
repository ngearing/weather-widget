<?php
$temps        = array();
$min_temp     = $day[0]->main->temp;
$max_temp     = $day[0]->main->temp;
$max_temp_key = 0;
foreach ( $day as $dkey => $forcast ) {
	$temps[ $dkey ] = $forcast->main->temp;
	if ( $forcast->main->temp < $min_temp ) {
		$min_temp = $forcast->main->temp;
	}
	if ( $forcast->main->temp > $max_temp ) {
		$max_temp     = $forcast->main->temp;
		$max_temp_key = $dkey;
	}
}

$day_forcast = $day[ $max_temp_key ];

$conditions = implode( '. ', array_column( $day_forcast->weather, 'description' ) );

$classes = array();
?>
<li class="<?php echo implode( ' ', $classes ); ?>">

	<p class="date">
		<?php echo date( 'D, M d', $day_forcast->dt ); ?>
	</p>

	<h4 class="temp">
		<?php
		if ( count( $day ) < 4 ) {
			printf(
				"<img src='%s'/>
                %d<span class='icon-c'>°C</span>",
				"http://openweathermap.org/img/wn/{$day_forcast->weather[0]->icon}@2x.png",
				$max_temp
			);
		} else {
			printf(
				"<img src='%s'/>
                %d / %d<span class='icon-c'>°C</span>",
				"http://openweathermap.org/img/wn/{$day_forcast->weather[0]->icon}@2x.png",
				$max_temp,
				$min_temp
			);
		}
		?>
	</h4>

	<p class="conditions">
		<?php echo $conditions; ?>
	</p>

</li>
