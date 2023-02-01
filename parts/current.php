<?php
$current_forecast = $day[0];
$classes          = array( 'current' );
$conditions       = implode( '. ', array_column( $current_forecast->weather, 'description' ) );
?>
<li class="<?php echo implode( ' ', $classes ); ?>">

	<h4 class="temp">
		<?php
		printf(
			"<img src='%s'/>
            %d<span class='icon-c'>°C</span>",
			"http://openweathermap.org/img/wn/{$current_forecast->weather[0]->icon}@2x.png",
			$current_forecast->main->temp
		);
		?>
	</h4>
	<p class="feels">
		<?php
		printf(
			'Feels like %d<span class="icon-c">°C</span>. %s',
			$current_forecast->main->feels_like,
			$conditions
		);
		?>
	</p>

	<p class="params">
		<?php
		$params               = array();
		$params['rain']       = $current_forecast->rain ?
			sprintf(
				'%s %smm',
				file_get_contents( NG_WW_PATH . 'assets/icon-rain.svg' ),
				$current_forecast->rain->{'3h'}
			) : '';
		$params['snow']       = $current_forecast->snow ?
			sprintf(
				'%s %smm',
				file_get_contents( NG_WW_PATH . 'assets/icon-snow.svg' ),
				$current_forecast->snow->{'3h'}
			) : '';
		$params['wind']       = $current_forecast->wind ?
			sprintf(
				'%s %sm/s %s',
				str_replace( '180deg', $current_forecast->wind->deg . 'deg', file_get_contents( NG_WW_PATH . 'assets/icon-wind.svg' ) ),
				$current_forecast->wind->speed,
				compass_direction( $current_forecast->wind->deg )
			) : '';
		$params['pressure']   = $current_forecast->main->pressure ?
			sprintf(
				'%s %shPa',
				file_get_contents( NG_WW_PATH . 'assets/icon-pressure.svg' ),
				$current_forecast->main->pressure
			) : '';
		$params['humidity']   = $current_forecast->main->humidity ? 'Humidity: ' . $current_forecast->main->humidity . '%' : '';
		$params['visibility'] = $current_forecast->visibility ? 'Visibility: ' . $current_forecast->visibility / 1000 . 'km' : '';
		echo '<span>' . implode( '</span><span>', array_filter( $params ) ) . '</span>';
		?>
	</p>

</li>
