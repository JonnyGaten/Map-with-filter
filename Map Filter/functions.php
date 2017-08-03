<?php 
function my_acf_init() {
	
	acf_update_setting('google_api_key', 'AIzaSyAhI86z06sBSA_7xDujhDPi9_AxqEdwL0c');
}

add_action('acf/init', 'my_acf_init');

?>