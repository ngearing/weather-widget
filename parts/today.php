<?php
$classes = array( 'today' );
$today = json_decode($data);
$today = $today->data->timelines[0];
$values = $today->intervals[0]->values;
$codes = new WeatherCodes();
$icons = new WeatherIcons();
?>
<li class="<?php echo implode( ' ', $classes ); ?>">


	<?php
	printf(
		'<h4 class="temp">
			<img src="%s"/>
			%d<span class="icon-c">°C</span>
		</h4>',
		$icons->get($values->weatherCode, true, 'large'),
		$values->temperature
	);

	printf(
		'<p class="feels">Feels like %d<span class="icon-c">°C</span>. %s</p>',
		$values->temperatureApparent,
		$codes->get($values->weatherCode)
	);

	?>
	<p class="params">
		<?php
		$params = [
			'rainIntensity' => function( $v ) {
				return sprintf('%s %smm','icon', $v);
			},
			'snowIntensity'=> function( $v ) {
				return sprintf('%s %smm','icon', $v);
			},
			'windSpeed'=> function( $v, $values ) {
				return sprintf('%s %sm/s %s', $values->windDirection, $v, compass_direction( $values->windDirection ));
			},
			'pressureSurfaceLevel' => function( $v ) {
				return sprintf('%s %shPa','icon', $v);
			},
			'humidity'=> function( $v ) {
				return sprintf('Humidity: %s', $v);
			},
			'visibility' => function( $v ) {
				return sprintf('Visibility: %skm', $v);
			},
			'dewPoint' => function( $v ) {
				return sprintf('Dewpoint: %s°C', $v);
			},
		];

		foreach( $params as $key => $func ) {
			if ( $values->$key ) {
				echo "<span class='$key'>" . $func( $values->$key, $values) . "</span>";
			}
		}

		// 'rain' => sprintf(
		// 	'%s %smm',
		// 	$icons->get( 'assets/icon-rain.svg' ),
		// 	$values->rain
		// ),

		// $params['snow']       = $current_forecast->snow ?
		// 	sprintf(
		// 		'%s %smm',
		// 		file_get_contents( NG_WW_PATH . 'assets/icon-snow.svg' ),
		// 		$current_forecast->snow->{'3h'}
		// 	) : '';
		// $params['wind']       = $current_forecast->wind ?
		// 	sprintf(
		// 		'%s %sm/s %s',
		// 		str_replace( '180deg', $current_forecast->wind->deg . 'deg', file_get_contents( NG_WW_PATH . 'assets/icon-wind.svg' ) ),
		// 		$current_forecast->wind->speed,
		// 		compass_direction( $current_forecast->wind->deg )
		// 	) : '';
		// $params['pressure']   = $current_forecast->main->pressure ?
		// 	sprintf(
		// 		'%s %shPa',
		// 		file_get_contents( NG_WW_PATH . 'assets/icon-pressure.svg' ),
		// 		$current_forecast->main->pressure
		// 	) : '';
		// $params['humidity']   = $current_forecast->main->humidity ? 'Humidity: ' . $current_forecast->main->humidity . '%' : '';
		// $params['visibility'] = $current_forecast->visibility ? 'Visibility: ' . $current_forecast->visibility / 1000 . 'km' : '';
		// echo '<span>' . implode( '</span><span>', array_filter( $params ) ) . '</span>';
		// ?>
	</p>

</li>
