# Map-with-filter
ACF Google Map with filter of taxonomies.

# How to setup
* First, make sure you have ACF plugin installed.
* Create a new CPT, in this example it will be named 'event-map'.
* In ACF - create a new group. Add a field for Google Maps named 'location' and for a marker image named 'Marker'. Make sure markers image type is URL. Make this edited on the post type of event.
* Set up ACF google maps- look here  https://www.advancedcustomfields.com/resources/google-map/
* Get an API Key, this will need to replace **your_api_key_here** throughout.
* Add the following to your functions.php
```
<?php 
function my_acf_init() {
	
	acf_update_setting('google_api_key', 'your_api_key_here');
}

add_action('acf/init', 'my_acf_init');

?>
```
* In the JS supplied from ACF, change 
```
  // create marker
  var marker = new google.maps.Marker({
      position : latlng,
      map	: map
  });
```
to the follwing
```
  // create marker
  var icon = $marker.attr('data-icon');

  var marker = new google.maps.Marker({
      position: latlng,
      map: map,
      icon: icon
  });

```
This will allow the image 'Marker' to be used as the marker icon on the map. The JS code is included in this project for reference with the change made.

* Use the .php file supplied to create a new page template, with filtering system.
* Create your new page in WordPress, select your page template.

## Note
* Make sure you create posts in Event post type.
* If an image is not selected for the marker, a default is set on the ```<div class="marker">``` as shown here ```data-icon="<?php if (empty($marker)) { echo 'URL_TO_FALLBACK_IMAGE_HERE'; } else{ echo $marker; } ?>">```
