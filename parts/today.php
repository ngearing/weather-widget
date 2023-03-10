<?php
$classes          = array( 'today' );
$values = [
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
];
?>

<ul class="weather-widget-list">
<h4 class="time"><?php echo date( 'M d, h:ia' ); ?></h4>
<h3 class="location">Kyneton, AU</h3>

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
            echo "<span class='$value'>" . $data->$value . '</span>';
        }
    }    
    ?>
	</p>

</li>

</ul>
