<?php
    /**
    * Template Name: Events Map
    */
    ?>
    <?php get_header(); ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=your_api_key_here"></script>


    <div class="container">

        <div class="row">

            <div class="col-md-12 breadcrumb">

                <span><a href="/">Home</a> <span class="red"><?php the_title();?></span></span>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <h2>
                    <?php the_title();?>
                </h2>

            </div>

        </div>

        <div class="row margin-top-20">
            <!-- Set Var for the 'event_type and event_location. Will be "" on initial visit -->
            <?php $eventtype        =       $_POST['events_type_dd'];?>
            <?php $eventlocation    =       $_POST['events_location_dd'];?>
            <?php $eventdate        =       $_POST['events_date_dd'];?>


               
            <?php
                //Get Taxonomies of 'Event_Type and Event_locations' 
                $terms          =       get_terms('event_type');
                $termslocation  =       get_terms('event_locations');
                $termsdate      =       get_terms('event_date');?>

                <!-- Create form, which will submit when a dropdown is selected -->
                <form name="frm" method="post" action="">

                    <!-- Echo the result -->
                    <?php //echo $eventtype;?>

                    <div class="col-md-4">
                        <!-- On change, refresh form, use name as the $_POST -->
                        <select class="form-control" onchange="document.frm.submit()" name="events_type_dd" id="events_type_dd">

                            <option value="">EVENT TYPE</option>
                            <!-- For each taxonomy -->
                            <?php foreach ( $terms as $term ) { ?>
                                <!-- If the taxonomy name equals the $eventtype (If when the page re-loads, it has a taxonomy selected, add selected to dropdown)-->
                                <option <?php if ($term->name == $eventtype){ echo 'selected'; } ?> value="<?php echo $term->name;?>"><?php echo $term->name;?></option>
                            <?php }?>
                        </select>
                    </div>
                    

                    <!-- Echo the result -->
                    <?php //echo $eventlocation;?>

                    <div class="col-md-4">
                        <!-- On change, refresh form, use name as the $_POST -->
                        <select class="form-control" onchange="document.frm.submit()" name="events_location_dd" id="events_location_dd">

                            <option value="">AREA</option>

                            <!-- For each taxonomy -->
                            <?php foreach ( $termslocation as $termlocation ) { ?>
                                <!-- If the taxonomy name equals the $eventtype (If when the page re-loads, it has a taxonomy selected, add selected to dropdown)-->
                                <option <?php if ($termlocation->name == $eventlocation){ echo 'selected'; } ?> value="<?php echo $termlocation->name;?>"><?php echo $termlocation->name;?></option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <!-- On change, refresh form, use name as the $_POST -->
                        <select class="form-control" onchange="document.frm.submit()" name="events_date_dd" id="events_date_dd">

                            <option value="">DATE</option>

                            <!-- For each taxonomy -->
                            <?php foreach ( $termsdate as $termdate ) { ?>
                                <!-- If the taxonomy name equals the $eventtype (If when the page re-loads, it has a taxonomy selected, add selected to dropdown)-->
                                <option <?php if ($termdate->name == $eventdate){ echo 'selected'; } ?> value="<?php echo $termdate->name;?>"><?php echo $termdate->name;?></option>
                            <?php }?>
                        </select>
                    </div>


                </form>
                
            </div>
        

        </div>

        <div class="row margin-top-20">

            <div class="col-md-12">

                 <div class="acf-map">

                    <!-- If both select fields are empty (Selected to All or 'select')-->
                    <?php if ($_REQUEST['events_type_dd'] == '' && $_REQUEST['events_location_dd'] == '' && $_REQUEST['events_date_dd'] == '') {

                        // Get all posts of the post type event-map
                        $query = new WP_Query(array(
                            'post_type' => 'event-map',
                            'post_status' => 'publish',
                        ));

                    }

                    // Else                    
                    else {

                        // Create count var and tax_query array
                        $count = 0;
                        $tax_query = array();

                        // If Event type select dropdown is not empty or all
                        if ($_REQUEST['events_type_dd'] !== '') {

                            // Add +1 to count
                            $count++;

                            // Set the array to get all posts where the taxonomy is event_type
                            $tax_query[]   =   array(
                                'taxonomy' => 'event_type',
                                'field'     => 'slug',
                                'terms' => $_REQUEST['events_type_dd'],
                            );

                        }

                        // If Event location select dropdown is not empty or all
                        if ($_REQUEST['events_location_dd'] !== '') {

                            // Add +1 to count
                            $count++;

                            // Set the array to get all posts where the taxonomy is event_locations
                            $tax_query[]   =   array(
                                'taxonomy' => 'event_locations',
                                'field'     => 'slug',
                                'terms' => $_REQUEST['events_location_dd'],
                            );

                        }

                        // If Date select dropdown is not empty or all
                        if ($_REQUEST['events_date_dd'] !== '') {

                            // Add +1 to count
                            $count++;

                            // Set the array to get all posts where the taxonomy is event_date
                            $tax_query[]   =   array(
                                'taxonomy' => 'event_date',
                                'field'     => 'slug',
                                'terms' => $_REQUEST['events_date_dd'],
                            );

                        }

                        // If count is more than 1 (if  dropdowns are selected)
                        if ( $count > 1 ) {
                            //Add a relation, array_unshift will add to beginning of array
                            array_unshift( $tax_query, array('relation' => 'OR'));

                        }

                        // Run query, the above will affet the outcome
                        $query = new WP_Query(array(
                            'post_type' => 'event-map',
                            'post_status' => 'publish',
                            'tax_query' => $tax_query,
                        ));
                    
                    } // End of Else

                    // While we have posts
                    while ($query->have_posts()) {
                        $query->the_post();

                        // Get location field from the looped post (ACF MAP)
                        $location = get_field('location', $post->ID);
                        $marker = get_field('marker', $post->ID); ?>

                        
                        <!-- Add ACF Marker, use locations lat and long (Look at ACF-MAP for more help) -->
                        <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>" data-icon="<?php if (empty($marker)) { echo 'URL_TO_FALLBACK_IMAGE_HERE'; } else{ echo $marker; } ?>"><?php the_title();?></div>


                    <?php }

                    wp_reset_query(); ?>

                </div>

           </div>

        </div>

    </div>

    <?php get_footer();?>