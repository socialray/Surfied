<div class="wrap">
   <h2>WP SimpleWeather</h2>

		<script type="text/javascript">
			function start() 
			{
				if (document.getElementById("display_region").checked == true || document.getElementById("display_country").checked == true)
				{
					document.getElementById("display_city").disabled = true;
				}
				if (document.getElementById("weather_rising").checked == true)
				{
					document.getElementById("weather_pressure").disabled = true;
				}
				if (document.getElementById("unitc").checked == true)
				{
					document.getElementById("wind_speed_meters").disabled = false;
				}
				else
				{
					document.getElementById("wind_speed_meters").disabled = true;
					document.getElementById("wind_speed_meters").checked = false;
				}
			}
			
			onload = start;
		</script>

		<script type="text/javascript">
			function check_parent(obj)
			{
				if (document.getElementById("display_region").checked == true || document.getElementById("display_country").checked == true)
				{
					document.getElementById("display_city").checked = true;
					document.getElementById("display_city").disabled = true;
				}
				else
				{
					document.getElementById("display_city").disabled = false;
				}
				
				if (document.getElementById("weather_rising").checked == true)
				{
					document.getElementById("weather_pressure").checked = true;
					document.getElementById("weather_pressure").disabled = true;
				}
				else
				{
					document.getElementById("weather_pressure").disabled = false;
				}
				if (document.getElementById("unitc").checked == true)
				{
					document.getElementById("wind_speed_meters").disabled = false;
				}
				else
				{
					document.getElementById("wind_speed_meters").disabled = true;
					document.getElementById("wind_speed_meters").checked = false;
				}
			}
		</script>



		<script type="text/javascript">
			function enable_all()
			{
				document.getElementById("display_city").disabled = false;
				document.getElementById("weather_pressure").disabled = false;
			}
		</script>
		
   <form method="post" action="options.php" style="width: 750px;" id="simpleweather_form">
   <?php settings_fields( 'simpleweather_option_group' ); ?>
   <?php $options = get_option('simpleweather_options'); ?>


   <div>
      <h3>Required Settings</h3>
      <p>You must provide either a zip code (US only) or a location. Postal codes outside the US are not 
         supported by the Yahoo Weather API so you must use a location for international locations. 
         For example if you want to display London weather you would set the location to 'london, united kingdom'. 
         The Yahoo Weather API will not recognize 'london, uk', so you must put the complete country.</p>

      <table class="form-table">
        <tr valign="top">
        <th scope="row">Location:</th>
        <td><input type="text" name="simpleweather_options[location]" size="50" value="<?php echo $options['location']; ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Unit of Temperature:</th>
        <td><input type="radio" name="simpleweather_options[unit]" id="unitf" value="f" onClick="javascript:check_parent(this);" checked <?php checked('f', $options['unit']); ?> /><label for="unitf">Fahrenheit</label><br />
            <input type="radio" name="simpleweather_options[unit]" id="unitc" value="c" onClick="javascript:check_parent(this);" <?php checked('c', $options['unit']); ?> /><label for="unitc">Celsius</label></td>
        </tr>
      </table>

      <h3>Display Options</h3>
      <p>You can choose to display any of the below options. Of course, should you choose to display none of them,
         then you won't see anything appear in your SimpleWeather widget.</p>
      <table class="form-table">
        <tr valign="top">
        <th scope="row">Location:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[display_city]" id="display_city" value="1" <?php checked('1', $options['display_city']); ?> />
            <label for="display_city">City</label><br />
            <input type="checkbox" name="simpleweather_options[display_region]" id="display_region" onClick="javascript:check_parent(this);" value="1" <?php checked('1', $options['display_region']); ?> />
            <label for="display_region">Region</label> <span style="font-style: italic;">(in the US, this would be the state. In some countries, no region will display.)</span><br />
            <input type="checkbox" name="simpleweather_options[display_country]" id="display_country" onClick="javascript:check_parent(this);" value="1" <?php checked('1', $options['display_country']); ?> />
            <label for="display_country">Country</label>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Current Weather:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[current_temperature]" id="current_temperature" value="1" checked <?php checked('1', $options['current_temperature']); ?> />
            <label for="current_temperature">Current Temperature</label><br />
            <input type="checkbox" name="simpleweather_options[current_weather]" id="current_weather" value="1" checked <?php checked('1', $options['current_weather']); ?>  />
            <label for="current_weather">Current Weather (Fair, Sunny, Rainy, etc.)</label><br />
            <input type="checkbox" name="simpleweather_options[current_weather_image]" id="current_weather_image" value="1" checked <?php checked('1', $options['current_weather_image']); ?>  />
            <label for="current_weather_image">Image of the Current Weather</label>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Wind:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[wind_direction]" id="wind_direction" value="1" <?php checked('1', $options['wind_direction']); ?> />
            <label for="wind_direction">Wind Direction &amp; Speed</label>             
            <input type="checkbox" name="simpleweather_options[wind_speed_meters]" id="wind_speed_meters" value="1" <?php checked('1', $options['wind_speed_meters']); ?> />
            <label for="wind_speed_meters">Use m/s rather than km/h (Celsius setting only)</label><br />
            <input type="checkbox" name="simpleweather_options[wind_chill]" id="wind_chill" value="1" <?php checked('1', $options['wind_chill']); ?> />
            <label for="wind_chill">Wind Chill</label>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Air Pressure &amp; Humidity:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[weather_pressure]" id="weather_pressure" value="1" <?php checked('1', $options['weather_pressure']); ?> />
            <label for="weather_pressure">Air Pressure</label><br />
            <input type="checkbox" name="simpleweather_options[weather_rising]" id="weather_rising" onClick="javascript:check_parent(this);" value="1" <?php checked('1', $options['weather_rising']); ?> />
            <label for="weather_rising">Pressure Rising or Falling</label><br />
            <input type="checkbox" name="simpleweather_options[weather_humidity]" id="weather_humidity" value="1" <?php checked('1', $options['weather_humidity']); ?> />
            <label for="weather_humidity">Humidity</label><br />
            <input type="checkbox" name="simpleweather_options[weather_visibility]" id="weather_visibility" value="1" <?php checked('1', $options['weather_visibility']); ?> />
            <label for="weather_visibility">Visibility</label>
        </td>
        </tr>

	    <tr valign="top">
        <th scope="row">Sunrise & Sunset:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[weather_sunrise]" id="weather_sunrise" value="1" <?php checked('1', $options['weather_sunrise']); ?> />
            <label for="weather_sunrise">Time of Sunrise</label><br />
            <input type="checkbox" name="simpleweather_options[weather_sunset]" id="weather_sunset" value="1" <?php checked('1', $options['weather_sunset']); ?> />
            <label for="weather_sunset">Time of Sunset</label>
        </td>
        </tr>	
		
        <tr valign="top">
        <th scope="row">Today's Forecast:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[high_temperature]" id="high_temperature" value="1" <?php checked('1', $options['high_temperature']); ?> />
            <label for="high_temperature">Today's High Temperature</label><br />
            <input type="checkbox" name="simpleweather_options[low_temperature]" id="low_temperature" value="1" <?php checked('1', $options['low_temperature']); ?> />
            <label for="low_temperature">Today's Low Temperature</label><br />
            <input type="checkbox" name="simpleweather_options[weather_forecast]" id="weather_forecast" value="1" <?php checked('1', $options['weather_forecast']); ?> />
            <label for="weather_forecast">Today's Forecast (Fair, Sunny, Rainy, etc.)</label><br />
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Tomorrow's Forecast:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[tomorrow_date]" id="tomorrow_date" value="1" <?php checked('1', $options['tomorrow_date']); ?> />
            <label for="tomorrow_date">Tomorrow's Date</label><br />
            <input type="checkbox" name="simpleweather_options[tomorrow_high_temperature]" id="tomorrow_high_temperature" value="1" <?php checked('1', $options['tomorrow_high_temperature']); ?> />
            <label for="tomorrow_high_temperature">Tomorrow's High Temperature</label><br />
            <input type="checkbox" name="simpleweather_options[tomorrow_low_temperature]" id="tomorrow_low_temperature" value="1" <?php checked('1', $options['tomorrow_low_temperature']); ?> />
            <label for="tomorrow_low_temperature">Tomorrow's Low Temperature</label><br />
            <input type="checkbox" name="simpleweather_options[tomorrow_weather_forecast]" id="tomorrow_weather_forecast" value="1" <?php checked('1', $options['tomorrow_weather_forecast']); ?> />
            <label for="tomorrow_weather_forecast">Tomorrow's Forecast (Fair, Sunny, Rainy, etc.)</label><br />
            <input type="checkbox" name="simpleweather_options[tomorrow_weather_image]" id="tomorrow_weather_image" value="1" <?php checked('1', $options['tomorrow_weather_image']); ?> />
            <label for="tomorrow_weather_image">Image of Tomorrow's Weather</label>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Other Options:</th>
        <td>
            <input type="checkbox" name="simpleweather_options[last_updated]" id="last_updated" value="1" <?php checked('1', $options['last_updated']); ?> />
            <label for="last_updated">Time the Widget was Last Updated</label><br />
            <input type="checkbox" name="simpleweather_options[weather_link]" id="weather_link" value="1" <?php checked('1', $options['weather_link']); ?> />
            <label for="weather_link">Link to Forecast at Yahoo! Weather</label>
        </td>
        </tr>
		
		
        <tr valign="top">
        <th scope="row">Image Themes:</th>
        <td>
			<select name="simpleweather_options[image_theme]">
			   <option value="yahoo-weather" selected <?php selected('yahoo-weather', $options['image_theme']); ?>>Yahoo! Weather</option>
			   <option value="sketchy-weather" <?php selected('sketchy-weather', $options['image_theme']); ?>>Sketchy Weather</option>
			   <option value="weather-images-shiny" <?php selected('weather-images-shiny', $options['image_theme']); ?>>Weather Images Shiny</option>
			</select>
			<p>Yahoo! Weather theme images are were created by Yahoo! Weather. Sketchy Weather theme images were created by <a href="http://azuresol.deviantart.com/" target=_blank">Azure_sol</a>.
			   Weather Images Shiny theme images were created by <a href="http://jyrik.deviantart.com" target="_blank">Jyrik</a>.</p>
			<p>All theme images are the property of their respective owners.</p>
        </td>
        </tr>



      </table>


   <p class="submit">
      <input type="submit" class="button-primary" onClick="javascript:enable_all();" value="<?php _e('Save Changes') ?>" />
   </p>
   </form>
</div>