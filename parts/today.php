<?php
$classes = array( 'today' );
$today = $data;
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
			'rainIntensity' => function( $v ) use ($icons) {
				return sprintf('%s %smm',$icons->getSVG('rain'), $v);
			},
			'snowIntensity'=> function( $v ) use ($icons) {
				return sprintf('%s %smm',$icons->getSVG('snow'), $v);
			},
			'windSpeed'=> function( $v, $values ) use ($icons) {
				return sprintf(
					'%s %sm/s %s', 
					str_replace('180deg', $values->windDirection.'deg', $icons->getSVG('wind')),
					$v, 
					compass_direction( $values->windDirection )
				);
			},
			'pressureSurfaceLevel' => function( $v ) use ($icons) {
				return sprintf('%s %shPa',$icons->getSVG('pressure'), $v);
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
	?>
	</p>

</li>
