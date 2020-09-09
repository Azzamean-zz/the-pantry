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
</style>	
<div class="big-container">
	<?php 
	$event_id = $_GET['event'];
	$attendee_list = Tribe__Tickets__Tickets::get_event_attendees($event_id); ?>
	<?php // echo count($attendee_list); ?>
	<table>
	<tr>
		<th>Attendee</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Ticket</th>	
		<th>Order #</th>	
	</tr>
	<tr>
	
	<?php
	foreach($attendee_list as $attendee) {
				
		$name = $attendee['attendee_meta']['name']['value'];
		
		if(isset($attendee['attendee_meta']['email']['value'])){
		    $email = $attendee['attendee_meta']['email']['value'];
			$emails[] = $attendee['attendee_meta']['email']['value'];
		}
		
		if(isset($attendee['attendee_meta']['phone']['value'])){
		    $phone = $attendee['attendee_meta']['phone']['value'];
		}
						
		echo '<tr>';
		echo '<td>' . $name . '</td>';
		echo '<td>' . $email . '</td>';
		echo '<td>' . $phone . '</td>';
		echo '<td>' . $attendee['ticket'] . '</td>';
		echo '<td><a href="' . $attendee['order_id_link_src'] . '">' . $attendee['order_id'] . '</a></td>';
		echo '</tr>';
	}
	
	?>
	
	</tr>
	</table>
	
	<h2>Email List</h2>	
	
	<?php
	$result = '';
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
<?php get_footer(); ?>