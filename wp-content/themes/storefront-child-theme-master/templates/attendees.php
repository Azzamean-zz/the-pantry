	<?php $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($event_id); ?>
	<?php echo count($attendee_list); ?>
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
		echo '<tr>';
		echo '<td>' . $attendee['attendee_meta']['name']['value'] . '</td>';
		echo '<td>' . $attendee['attendee_meta']['email']['value'] . '</td>';
		echo '<td>' . $attendee['attendee_meta']['phone']['value'] . '</td>';
		echo '<td>' . $attendee['ticket'] . '</td>';
		echo '<td><a href="' . $attendee['order_id_link_src'] . '">' . $attendee['order_id'] . '</a></td>';
		echo '</tr>';
	}
	
	foreach($attendee_list as $type => $list) {
		echo $type." has ".count($list). " elements\n";
		echo '<br>';
	}
	?>
	
	</tr>
	</table>
	
	<?php
		
		echo '<pre>';
		print_r($attendee_list);
		echo '</pre>';
	?>
