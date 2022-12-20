<div class="row">
{% if country_code and speed and wind_direction and wind_gust and temp and pressure and clouds and icon %}
<div class="col-md-6 col-md-offset-3">
    <h2> <img src=http://openweathermap.org/img/wn/{{icon}}@2x.png alt={{clouds}}> {{clouds}} {{temp}}&#8451 </h2>
    <table style="width:50%"; border-spacing:20px>
        <tr>  
            <td>Wind direction</td>
            <td>{{wind_direction}}&#176</td>
        </tr> 
        <tr>
            <td>Wind speed</td>
            <td>{{speed}} (gusts {{wind_gust}})</td>
        </tr>
        <tr>
            <td>Pressure</td>
            <td>{{pressure}}</td>
        </tr>
    </table>
</div>
{% endif %}
</div>
