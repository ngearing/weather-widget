<?php
$values = [
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
];
?>


<div class="weather-widget-list bar">
<p class="params">
    <?php
    foreach ( $values as $value ) {
        if ( $data->$value ) {
            echo $data->$value;
        }
    }    
    ?>
</p>
</div>
