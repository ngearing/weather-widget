<?php
$values = array(
	'temp',
	'precipRate',
	'snowRate',
	'windSpeed',
	'pressure',
	'humidity',
	'visibility',
	'dewpt',
	'uv',
	'civil_twilight_begin',
	'civil_twilight_end',
);

foreach ( $data as $key => $value ) {
	if ( ! in_array( $key, $values ) ) {
		unset( $data->$key );
	}
}
?>


<div class="weather-widget-list bar">
<p class="params">
	<?php
	if ( ! $data || count( get_object_vars( $data ) ) < ( count( $values ) / 2 ) ) {
		if ( current_user_can( 'edit_posts' ) ) {
			echo '<span> Error: Not enough data to display this widget. </span>';
		}
	} else {
		foreach ( $values as $value ) {
			if ( $data->$value ) {
				echo __( $data->$value, 'ng_ww' );
			}
		}
	}
	?>
</p>
</div>
