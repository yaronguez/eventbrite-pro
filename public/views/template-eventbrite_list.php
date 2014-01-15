
<div class="eb-container">
	<?php if($calendar):?>
	<div class="eb-event-calendar">
		<?php echo $event_calendar ?>
	</div>
	<?php endif;?>
	<div class="eb-event-list">
		<?php foreach($events as $event):?>
			<div class='eb_event_list_item' id='evnt_div_<?php echo $event->id ?>'>
				<span class='eb_event_list_date'><?php echo strftime('%a, %B %e', $event->time);?></span>
				<span class='eb_event_list_time'><?php echo strftime('%l:%M %P', $event->time); ?></span>
				<div class='eb_event_list_title'>
					<a href='<?php echo $event->url;?>' title='<?php echo $event->title;?>'><?php echo $event->title;?></a>
				</div>
				<span class='eb_event_list_location'><?php echo $event->venue_name; ?></span>
			</div>
		<?php endforeach;?>
		<?php echo $event_rows; ?>
	</div>
</div>


<!-- This file is used to markup the public facing aspect of the plugin. -->