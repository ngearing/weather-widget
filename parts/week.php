<?php
use WeatherWidget\Codes;
use WeatherWidget\Icons;

$codes = new Codes();
$icons = new Icons();
$week  = $data;

foreach ( $week->data->timelines[0]->intervals as $day ) :
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
		$icons->get( $day->values->weatherCode ),
		$day->values->temperatureMax,
		$day->values->temperatureMin
	);

	printf(
		'<p class="conditions">%s</p>',
		$codes->get( $day->values->weatherCode )
	);
	?>

</li>

	<?php
endforeach;
?>
