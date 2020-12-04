<?php
/**
 * Template name: Attendees Template
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
	if(isset($_GET['event'])) {
		$event_id = $_GET['event'];
	} else {
		$event_id = '18520';
	}
	$attendee_list = Tribe__Tickets__Tickets::get_event_attendees($event_id); ?>

<!--
	<pre>
		<?php print_r($attendee_list); ?> 
	</pre>	
-->

	<?php
		getAttendee($event_id);
	
		foreach($attendee_list as $attendee) {
			$class[] = $attendee['ticket'];
		}
		$classcounts = array_count_values($class);
		
		foreach($classcounts as $classkey => $classcount) {
		?>
			<div class="count-col text-center">
				<h1><?php echo $classcount;?></h1>	
				<h3><?php echo $classkey;?></h3>	
			</div>	
		<?php } ?>
	
	<?php // echo count($attendee_list); ?>
	<div class="clearfix"></div>	
	
	<div class="filter-boxes">
		<label><input type="checkbox" name="attendee" checked="checked"> Attendee</label>	
		<label><input type="checkbox" name="purchaser" checked="checked"> Purchaser</label>	
		<label><input type="checkbox" name="email" checked="checked"> Email</label>	
		<label><input type="checkbox" name="phone" checked="checked"> Phone</label>	
		<label><input type="checkbox" name="ticket" checked="checked"> Ticket</label>	
		<label><input type="checkbox" name="order_no" checked="checked"> Order #</label>	
		<label><input type="checkbox" name="veg" checked="checked"> Vegetarian Option</label>	
		<label><input type="checkbox" name="wine" checked="checked"> Wine</label>	
	</div>	
	
	<table id="attendee-table" class="tablesorter">
		<thead>
			<tr>
				<th class="attendee">Attendee</th>
				<th class="purchaser">Purchaser</th>
				<th class="email">Email</th>
				<th class="phone">Phone</th>
				<th class="ticket">Ticket</th>	
				<th class="order_no">Order #</th>	
				<th class="veg">Vegetarian Option</th>	
				<th class="wine">Wine</th>	
			</tr>
		</thead>	
		<tbody>
			<tr>
			
			<?php
			$veg = '';
			$phone = '';
			$email = '';
			$emails[] = '';

			foreach($attendee_list as $attendee) {
				$name = '';
				$wine = '';
				if(isset($attendee['attendee_meta']['name'])) {
					$name = $attendee['attendee_meta']['name']['value'];
					$names[] = $attendee['attendee_meta']['name']['value'];
					
					if(isset($attendee['attendee_meta']['do-you-need-a-vegetarian-option']['value'])){
						$veg = "Yes";
					} else {
						$veg = " ";
					}
					if(isset($attendee['attendee_meta']['email']['value'])){
				    	$email = $attendee['attendee_meta']['email']['value'];
						$emails[] = $attendee['attendee_meta']['email']['value'];
					} else {
						$email = '';
					}
					if(isset($attendee['attendee_meta']['phone']['value'])){
					    $phone = $attendee['attendee_meta']['phone']['value'];
					}
					
				} else {
					$email = $attendee['purchaser_email'];
					$emails[] = $attendee['purchaser_email'];
				}
				if(isset($attendee['attendee_meta']['please-select-your-wine']['value'])) {
						$wine = $attendee['attendee_meta']['please-select-your-wine']['value'];
					}
				$purchaser = $attendee['purchaser_name'];
								
				echo '<tr>';
				echo '<td class="attendee">' . $name . '</td>';
				echo '<td style="font-size:12px;" class="purchaser">' . $purchaser . '</td>';
				echo '<td class="email">' . $email . '</td>';
				echo '<td class="phone">' . $phone . '</td>';
				echo '<td class="ticket">' . $attendee['ticket'] . '</td>';
				echo '<td class="order_no"><a href="' . $attendee['order_id_link_src'] . '">' . $attendee['order_id'] . '</a></td>';
				echo '<td class="veg">' . $veg . '</td>';
				echo '<td class="wine">' . $wine . '</td>';
				echo '</tr>';
			}
			
			?>
			
			</tr>
		</tbody>	
	</table>
	
	<h2>Attendee Email List</h2>	
	
	<?php
	$result = '';
	
	$emails = array_unique($emails);
	
	foreach($emails as $email) {
		$result .= $email . ', ';
	}

	$result = rtrim($result,', ');
	echo $result;
	?>
	
	<h2>Attendee Name List</h2>	
	
	<?php
	$result = '';
	
	$names = array_unique($names);
	
	foreach($names as $name) {
		$result .= $name . ', ';
	}

	$result = rtrim($result,', ');
	echo $result;
	?>
	
	<?php
	//echo '<pre>';
	//print_r($attendee_list);
	//echo '</pre>';
	?>
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