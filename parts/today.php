<?php
$classes = array( 'today' );
$values  = array(
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

<ul class="weather-widget-list">
<h4 class="time"><?php echo date( 'M d, h:ia' ); ?></h4>
<h3 class="location">Kyneton, AU</h3>

<?php
if ( ! $data || count( get_object_vars( $data ) ) < ( count( $values ) / 2 ) ) {
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<span> Error: Not enough data to display this widget. </span>';
	}
} else {
	?>

<li class="<?php echo implode( ' ', $classes ); ?>">

	<?php
	printf(
		'<h4 class="temp">
			%s
		</h4>',
		$data->temp
	);

	printf(
		'<p class="feels">Feels like %d<span class="icon-c">°C</span></p>',
		$data->heatIndex
	);

	?>
	<p class="params">
	<?php
	foreach ( $values as $value ) {
		if ( $data->$value ) {
			echo $data->$value;
		}
	}
	?>
	</p>

</li>
<?php } ?>
</ul>
