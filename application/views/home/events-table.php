<?php 		
	$paging_bar = paging_bar($data['num_rows'], $data['page_size'], $data['page'], $controller_name, "ajax_alarms", get_url_navigate()); ?>
	<?php echo $paging_bar; ?>
<table cellpadding="0" cellspacing="0" width="100%" class="table-list" page_size="<?php echo $data['page_size'];?>">
	<thead>
		<tr>
			<th class="checkbox"></th>
			<th class="checkbox">
				<input type="checkbox" onclick="check_all(this.checked)"/>
			</th>			
			<th style='width:50px'>
				<a href='javascript:void(0)' sort="id"><?php echo $this->lang->line('id');?></a>
			</th>
			<th>
				<a href='javascript:void(0)' sort="name"><?php echo $this->lang->line('event');?></a>
			</th>
			<th style='width:100px'>
				<a href='javascript:void(0)' sort="event-type"><?php echo $this->lang->line('event-type');?></a>
			</th>
			<th style='width:120px'>
				<a href='javascript:void(0)' sort="device-name"><?php echo $this->lang->line('device-name');?></a>
			</th>			
			<th style="width:100px">
				<a href='javascript:void(0)' sort="created-date"><?php echo $this->lang->line('created-date');?></a>
			</th>			
			<th style="width:50px">
				<a href='javascript:void(0)' sort="status"><?php echo $this->lang->line('status');?></a>
			</th>			
			<th style="width:100px"></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$total = $data['data']->num_rows;
		$start_index = ($data['page'] - 1) * $data['page_size'] + 1;
		if ($total > 0):
			$i = 0;			
			foreach($data['data']->result() as $row): ?>
			<tr <?php echo $i % 2 == 0 ? "class='even'" : "";?>>
				<td class="checkbox"><?php echo $start_index; ?></td>
				<td class="checkbox">
					<input type="checkbox" key="<?php echo $row->id; ?>"/>
				</td>				
				<td class="checkbox">
					<?php echo $row->id; ?>
				</td>
				<td>
					<?php if ($row->event_type == 1){ ?>
						<a href="<?php echo base_url("edit-event/" . $row->id . "?" . get_url_navigate()); ?>"><?php echo htmlspecialchars($row->name); ?></a>
					<?php } else { ?>
						<a href="<?php echo base_url("edit-event-schedule/" . $row->id . "?" . get_url_navigate()); ?>"><?php echo htmlspecialchars($row->name); ?></a>
					<?php } ?>
				</td>
				<td>
					<?php if ($row->event_type == 1){ 
						echo $this->lang->line('condition');
					}
					else{
						echo $this->lang->line('time');
					}
					?>
				</td>
				<td>
					<a href="<?php echo base_url("edit-device/" . $row->device_id . "?" . get_url_navigate()); ?>"><?php echo htmlspecialchars($row->device_name); ?></a>
				</td>
				<td class="tc">
					<?php echo htmlspecialchars($row->created_date_format); ?>
				</td>
				<td class="tc">
					<?php if ($row->status == 1) { ?>
						<img src="<?php echo base_url('images/enabled.gif');?>" title="<?php echo $this->lang->line('enabled');?>"/>
					<?php } ?>
				</td>							
				<td class="tc">					
					<?php if ($row->event_type == 1){ ?>
						<a href="<?php echo base_url("edit-event/" . $row->id . "?" . get_url_navigate()); ?>"><?php echo $this->lang->line('edit');?></a>
					<?php } else { ?>
						<a href="<?php echo base_url("edit-event-schedule/" . $row->id . "?" . get_url_navigate()); ?>"><?php echo $this->lang->line('edit');?></a>
					<?php } ?> 
					| <a href="javascript:void(0)" onclick="delete_1_data('<?php echo $this->lang->line('event');?>', 'delete_events', '<?php echo $row->id; ?>')"><?php echo $this->lang->line('delete');?></a>					
				</td>
			</tr>
			<?php
			$start_index++;
			$i++;
			endforeach;
		else:
		 ?>
			<tr>
				<td colspan="10">
					<?php echo $this->lang->line("data-not-found");?>
				</td>
			</tr>
		<?php endif ?>
	</tbody>
</table>	
<?php echo $paging_bar; ?>	