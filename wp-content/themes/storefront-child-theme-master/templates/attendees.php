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
	
	<?php

/*
	echo '<pre>';
	print_r($attendee_list);
	echo '</pre>';
*/

	
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
	<table id="attendee-table" class="tablesorter">
		<thead>
			<tr>
				<th>Attendee</th>
				<th>Purchaser</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Ticket</th>	
				<th>Order #</th>	
				<th>Vegetarian Option</th>	
			</tr>
		</thead>	
		<tbody>
			<tr>
			
			<?php
			$veg = '';
			$phone = '';
			$emails[] = '';

			foreach($attendee_list as $attendee) {
				$name = '';
				if(isset($attendee['attendee_meta']['name'])) {
					$name = $attendee['attendee_meta']['name']['value'];
					
					if(isset($attendee['attendee_meta']['do-you-need-a-vegetarian-option']['value'])){
						$veg = "Yes";
					} else {
						$veg = " ";
					}
					if(isset($attendee['attendee_meta']['email']['value'])){
				    $email = $attendee['attendee_meta']['email']['value'];
						$emails[] = $attendee['attendee_meta']['email']['value'];
					}
					if(isset($attendee['attendee_meta']['phone']['value'])){
					    $phone = $attendee['attendee_meta']['phone']['value'];
					}
				} else {
					$email = $attendee['purchaser_email'];
					$emails[] = $attendee['purchaser_email'];
				}
				
				$purchaser = $attendee['purchaser_name'];
								
				echo '<tr>';
				echo '<td>' . $name . '</td>';
				echo '<td style="font-size:12px;">' . $purchaser . '</td>';
				echo '<td>' . $email . '</td>';
				echo '<td>' . $phone . '</td>';
				echo '<td>' . $attendee['ticket'] . '</td>';
				echo '<td><a href="' . $attendee['order_id_link_src'] . '">' . $attendee['order_id'] . '</a></td>';
				echo '<td>' . $veg . '</td>';
				echo '</tr>';
			}
			
			?>
			
			</tr>
		</tbody>	
	</table>
	
	<h2>Email List</h2>	
	
	<?php
	$result = '';
	
	$emails = array_unique($emails);
	
	foreach($emails as $email) {
		$result .= $email . ', ';
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
</script>	
	
<?php get_footer(); ?>