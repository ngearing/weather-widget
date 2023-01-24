<?php
$current_forcast = $day[0];
$classes         = array( 'current' );
$conditions      = implode( '. ', array_column( $current_forcast->weather, 'description' ) );
?>
<li class="<?php echo implode( ' ', $classes ); ?>">

	<h4 class="temp">
		<?php
		printf(
			"<img src='%s'/>
            %d<span class='icon-c'>°C</span>",
			"http://openweathermap.org/img/wn/{$current_forcast->weather[0]->icon}@2x.png",
			$current_forcast->main->temp
		);
		?>
	</h4>
	<p class="feels">
		<?php
		printf(
			'Feels like %d<span class="icon-c">°C</span>. %s',
			$current_forcast->main->feels_like,
			$conditions
		);
		?>
	</p>

	<p class="params">
		<?php
		$params               = array();
		$params['rain']       = $current_forcast->rain ?
			sprintf(
				'%s %smm',
				file_get_contents( NG_WW_PATH . 'assets/icon-rain.svg' ),
				$current_forcast->rain->{'3h'}
			) : '';
		$params['snow']       = $current_forcast->snow ?
			sprintf(
				'%s %smm',
				file_get_contents( NG_WW_PATH . 'assets/icon-snow.svg' ),
				$current_forcast->snow->{'3h'}
			) : '';
		$params['wind']       = $current_forcast->wind ?
			sprintf(
				'%s %sm/s %s',
				str_replace( '180deg', $current_forcast->wind->deg . 'deg', file_get_contents( NG_WW_PATH . 'assets/icon-wind.svg' ) ),
				$current_forcast->wind->speed,
				compass_direction( $current_forcast->wind->deg )
			) : '';
		$params['pressure']   = $current_forcast->main->pressure ?
			sprintf(
				'%s %shPa',
				file_get_contents( NG_WW_PATH . 'assets/icon-pressure.svg' ),
				$current_forcast->main->pressure
			) : '';
		$params['humidity']   = $current_forcast->main->humidity ? 'Humidity: ' . $current_forcast->main->humidity . '%' : '';
		$params['visibility'] = $current_forcast->visibility ? 'Visibility: ' . $current_forcast->visibility / 1000 . 'km' : '';
		echo '<span>' . implode( '</span><span>', array_filter( $params ) ) . '</span>';
		?>
	</p>

</li>
