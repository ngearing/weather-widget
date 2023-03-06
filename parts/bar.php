<?php
use WeatherWidget\Codes;
use WeatherWidget\Icons;

$today            = $data;
$today            = $today->observations[0];
$values           = $today->metric;
$values->winddir  = $today->winddir;
$values->humidity = $today->humidity;
$values->uv       = $today->uv;

$codes = new Codes();
$icons = new Icons();

$params = array(
	'temp'       => function( $v ) {
		return sprintf( '%s°C', $v );
	},
	'precipRate' => function( $v ) use ( $icons ) {
		return sprintf( '%s %smm', $icons->getSVG( 'rain' ), $v );
	},
	'snowRate'   => function( $v ) use ( $icons ) {
		return sprintf( '%s %smm', $icons->getSVG( 'snow' ), $v );
	},
	'windSpeed'  => function( $v, $values ) use ( $icons ) {
		return sprintf(
			'%s %01.1f / %01.1f knots %s',
			str_replace( '180deg', ( $values->winddir + 180 ) . 'deg', $icons->getSVG( 'wind' ) ),
			$v / 1.852, // kph to knots.
			$values->windGust / 1.852, // kph to knots.
			compass_direction( $values->winddir )
		);
	},
	'pressure'   => function( $v ) use ( $icons ) {
		return sprintf( '%s %dhPa', $icons->getSVG( 'pressure' ), $v );
	},
	'humidity'   => function( $v ) {
		return sprintf( 'Humidity: %s%%', $v );
	},
	'visibility' => function( $v ) {
		return sprintf( 'Visibility: %skm', $v );
	},
	'dewpt'      => function( $v ) {
		return sprintf( 'Dew Point: %d°C', $v );
	},
	'uv'         => function( $v ) {
		return sprintf( 'UV: %s', $v );
	},
);


?>


<div class="weather-widget-list bar">
<p class="params">
	<?php
	foreach ( $params as $key => $func ) {
		if ( $values->$key ) {
			echo "<span class='$key'>" . $func( $values->$key, $values ) . '</span>';
		}
	}
	?>
</p>
</div>
