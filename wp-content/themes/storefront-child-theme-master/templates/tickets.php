<?php
/**
 * Template name: Tickets Template
 * @package storefront
 */

get_header(); ?>
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
	$args = array(
	    'post_type' => 'ticket-page',
	    'posts_per_page' => 10,
	    'meta_key' => 'start_date',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
	);
	
	$query = new WP_Query($args);
    if ( $query->have_posts() ) {
	?>
	
	<div class="clearfix"></div>	
	
	<div class="filter-boxes">
		<label><input type="checkbox" name="attendee" checked="checked"> Ticket Name</label>	
		<label><input type="checkbox" name="purchaser" checked="checked"> Attendees</label>	
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