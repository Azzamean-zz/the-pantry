<?php
/**
 * Template name: Tickets Template
 * @package storefront
 */

get_header(); ?>

<?php 
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

	<?php
		
	$tz = 'America/Los_Angeles';
	$today = new DateTime('now', new DateTimeZone($tz));
	
	$thisMonth = new DateTime('now', new DateTimeZone($tz));
	$nextMonth = new DateTime('first day of +1 month', new DateTimeZone($tz));
	
	$first_day = new DateTime('first day of this month', new DateTimeZone($tz));
	$first_day = $first_day->format('Y-' . $month . '-d H:i:s');
	
	$last_day = new DateTime('last day of this month', new DateTimeZone($tz));
	$last_day = $last_day->format('Y-' . $month . '-d H:i:s');
	
	$args = array(
	    'post_type' => 'ticket-page',
	    'posts_per_page' => 5,
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
	
/*
	echo '<pre>';
	print_r($args);
	echo '</pre>';
*/
	
	$query = new WP_Query($args);
    if ( $query->have_posts() ) {
	?>
		
	<div class="clearfix"></div>	
	
	<div class="filter-boxes">
		<label><input type="checkbox" name="attendee" checked="checked"> Ticket Name</label>	
		<label><input type="checkbox" name="purchaser" checked="checked"> Available</label>	
	</div>	
	
	<table id="attendee-table" class="tablesorter">
		<thead>
			<tr>
				<th class="name">Ticket Name</th>
				<th class="available">Available</th>
			</tr>
		</thead>	
		<tbody>			
			<?php  while ( $query->have_posts() ) { $query->the_post(); ?>
			
			<?php
			$ticket_id = get_the_ID();
			$tids = tribe_get_woo_tickets_ids($ticket_id);

			$tickets_handler = tribe( 'tickets.handler' );
			$totals = Tribe__Tickets__Tickets::get_all_event_tickets( $ticket_id );
			
			echo '<pre>';
			print_r($totals);
			echo '</pre>';
			?>
				
			<tr>
				<td class="name"><?php the_title();?></td>	
				<td class="available"><?php echo $tickets_handler->get_ticket_max_purchase( $tids[0] ); ?></td>	
			</tr>			
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