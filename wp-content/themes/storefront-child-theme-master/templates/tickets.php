<?php
/**
 * Template name: Tickets Template
 * @package storefront
 */

get_header(); ?>

<?php 
$query = $_GET;

if(isset($_GET['month'])) {
	$month = $_GET['month'];
} else {
	$month = date('m');
}
?>

<style>
	body {
		background: none !important;
		padding: 30px;
	}
	table td {
		padding: 5px !important;
	}
</style>	
<div class="big-container">
	<ul class="homeLinks">
		<?php
		$query['month'] = '01';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-01" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">January</a></li>
		<?php
		$query['month'] = '02';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-02" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">February</a></li>
		<?php
		$query['month'] = '03';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-03" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">March</a></li>
		<?php
		$query['month'] = '04';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-04" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">April</a></li>
		<?php
		$query['month'] = '05';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-05" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">May</a></li>
		<?php
		$query['month'] = '06';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-06" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">June</a></li>
		<?php
		$query['month'] = '07';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-07" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">July</a></li>
		<?php
		$query['month'] = '08';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-08" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">August</a></li>
		<?php
		$query['month'] = '09';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-09" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">September</a></li>
		<?php
		$query['month'] = '10';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-10" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">October</a></li>
		<?php
		$query['month'] = '11';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-11" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">November</a></li>
		<?php
		$query['month'] = '12';
		$query_result = http_build_query($query);
		?>
		<li><a class="month-12" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">December</a></li>
	</ul>	
	<hr style="margin-bottom: 60px;">
	<ul class="homeLinks">
		<?php
		$query['type'] = 'class';
		$query_result = http_build_query($query);
		?>
		<li><a class="type-class" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">Class</a></li>
		<?php
		$query['type'] = 'to-go';
		$query_result = http_build_query($query);
		?>
		<li><a class="type-to-go" href="<?php echo $_SERVER['PHP_SELF']; ?>/tickets?<?php echo $query_result; ?>">To-Go</a></li>
	</ul>	

	<?php	
	// This is SQL to do the same thing as this page, in hopes to perform the queries faster and less taxing on teh db
	$items = array();
	
	$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish'
	AND post_type='tribe_wooticket' LIMIT 10");
	
	foreach($posts as $post) {
		$attendees[] = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN( $post->ID ) ORDER BY meta_id ASC");
	}
	?>
<!--
	<pre>
		<?php // print_r($r); ?>
	</pre>
-->	
	<?php
	
	$tz = 'America/Los_Angeles';
	$today = new DateTime('now', new DateTimeZone($tz));
	
	$year = date('Y');
	
	$query_date = $year . $month . '-01';
	
	$thisMonth = new DateTime('now', new DateTimeZone($tz));
	$nextMonth = new DateTime('first day of +1 month', new DateTimeZone($tz));
	
	$first_day = new DateTime('first day of this month', new DateTimeZone($tz));
	$first_day = $first_day->format('Y-' . $month . '-d H:i:s');
	
	$last_day = new DateTime('first day of next month', new DateTimeZone($tz));
	
	$nextmonth = $month + 1;
	
	$last_day = $last_day->format('Y-' . $nextmonth . '-d H:i:s');
	
// 	$last_day = date("Y-m-t", strtotime($first_day));
	
	$args = array(
	    'post_type' => 'ticket-page',
	    'posts_per_page' => -1,
	    'order'          => 'ASC',
	    'orderby'        => 'meta_value',
	    'meta_key'       => 'start_date',
	    'meta_type'      => 'DATETIME',
	    'meta_query' => array(
	        array(
	            'key'     => 'start_date',
	            'compare' => '>=',
	            'value'   => $first_day,
	            'type'    => 'DATETIME',
	        ),
	         array(
	            'key'     => 'start_date',
	            'compare' => '<=',
	            'value'   => $last_day,
	            'type'    => 'DATETIME',
	        )
	    ),
	);
	
	if(isset($_GET['type'])) {
		$type = $_GET['type'];
		$args['tax_query'] = array(
	        array(
	            'taxonomy' => 'ticket-page-categories',
	            'field' => 'slug',
	            'terms' => $type,
	        ),
	    );
	}

	$query = new WP_Query($args);
    if ( $query->have_posts() ) {
	?>
		
	<div class="clearfix"></div>	
	
	<div class="filter-boxes">
		<label><input type="checkbox" name="ticket-name" checked="checked"> Ticket Name</label>	
		<label><input type="checkbox" name="waitlist" checked="checked"> Waitlist</label>	
		<label><input type="checkbox" name="link" checked="checked"> Attendee List</label>	

<!-- 		<label><input type="checkbox" name="stock" checked="checked"> Stock</label>	 -->
<!-- 		<label><input type="checkbox" name="sold" checked="checked"> Sold</label>	 -->
	</div>	

	<table id="attendee-table" class="tablesorter">
		<thead>
			<tr>
				<th class="name">Ticket Name</th>
				<th class="waitlist">Waitlist</th>
				<th class="link">Attendee List</th>
<!-- 				<th class="stock">Stock</th> -->
<!-- 				<th class="sold">Sold</th> -->
			</tr>
		</thead>	
		<tbody>			
			<?php  while ( $query->have_posts() ) { $query->the_post(); ?>
			
			<?php
			$ticket_id = get_the_ID();
			$tids = tribe_get_woo_tickets_ids($ticket_id);

			// $tickets_handler = tribe( 'tickets.handler' );
			$totals = Tribe__Tickets__Tickets::get_all_event_tickets( $ticket_id );

/*
			echo '<pre>';
			print_r($totals);
			echo '<pre>';
*/
			
			foreach($totals as $total) {
				
/*
				echo '<pre>';
				print_r(tribe_tickets_get_ticket_stock_message($total));
				echo '</pre>';
*/

// 				$total_sold = ($total->capacity - $total->stock);

				$wait = xoo_wl_db()->get_waitlisted_count( $total->ID );
				
				if($wait['totalQuantity']) {
					$waitlist = $wait['totalQuantity'];
				} else {
					$waitlist = 0;
				}

			?>
				
			<tr>
				<td class="ticket-name"><a href="<?php echo get_edit_post_link(); ?>"><?php echo $total->name; ?></a></td>	
				<td class="waitlist"><?php echo $waitlist; ?></td>	
				<td class="link"><a href="<?php echo site_url();?>/attendee-list?event=<?php echo $ticket_id;?>">View Attendees</a></td>	
			</tr>			
			<?php } ?>
			<?php } ?>
		</tbody>	
	</table>
	<?php } ?>

</div>	

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript" src="<?php echo site_url(); ?>/wp-content/themes/storefront-child-theme-master/assets/js/src/plugins/jquery.tablesorter.min.js"></script>

<script>
	$(document).ready(function() {
	  $("#attendee-table").tablesorter();
	  $('.homeLinks a.month-<?php echo $month;?>').addClass('active');
	  $('.homeLinks a.type-<?php echo $type;?>').addClass('active');
	});
	
	$("input:checkbox:not(:checked)").each(function() {
	    var column = "table ." + $(this).attr("name");
	    $(column).hide();
	});
	
	$("input:checkbox").click(function(){
	    var column = "table ." + $(this).attr("name");
	    $(column).toggle();
	});
</script>	
	
<?php get_footer(); ?>