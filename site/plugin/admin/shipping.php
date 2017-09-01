<?php

use SSD\Helper;

if (!empty($data['rows'])) {
?>
	
	<table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
	
		<tr>
			<th class="col_1">+</th>
			<th>Type</th>
			<th class="col_1">Rates</th>
			<th class="col_1">Active</th>
			<th class="col_1">Default</th>
			<th class="col_1">Duplicate</th>
			<th class="col_1 ta_r">Remove</th>
		</tr>
		
		<tbody id="rowsLocal" class="sortRows" data-url="<?php echo $data['urlSort']; ?>">
		
			<?php foreach($data['rows'] as $item) { ?>
			
				<tr id="row-<?php echo $item['id']; ?>">
				
					<td>+</td>
					<td>
						<span class="clickHideShow" data-show="#name-<?php echo $item['id']; ?>">
							<?php echo Helper::encodeHtml($item['name']); ?>
						</span>
						<input
							type="text"
							name="name-<?php echo $item['id']; ?>"
							id="name-<?php echo $item['id']; ?>"
							class="fld fldList blurUpdateHideShow dn"
							data-id="<?php echo $item['id']; ?>"
							value="<?php echo $item['name']; ?>"
						/>
					</td>
					<td>
						<select 
							name="rate-<?php echo $item['id']; ?>"
							id="rate-<?php echo $item['id']; ?>"
							class="fld fldSmall selectRedirect"
						>
							<option value="">Edit rates</option>
							<?php if (!empty($data['countries'])) { ?>
								
								<?php foreach($data['countries'] as $crow) { ?>
								
									<option 
										value="<?php echo $crow['id']; ?>"
										data-url="<?php echo $data['objUrl']->getCurrent('action', false, array('action', 'rates', 'id', $item['id'], 'zid', $crow['id'])); ?>"
									>
										<?php echo $crow['name']; ?>
									</option>
								
								<?php } ?>
								
							<?php } else if (!empty($data['zones'])) { ?>
							
								<?php foreach($data['zones'] as $zrow) { ?>
								
									<option 
										value="<?php echo $zrow['id']; ?>"
										data-url="<?php echo $data['objUrl']->getCurrent('action', false, array('action', 'rates', 'id', $item['id'], 'zid', $zrow['id'])); ?>"
									>
										<?php echo $zrow['name']; ?>
									</option>
								
								<?php } ?>
							
							<?php } ?>
						</select>
					</td>
					<td>
						<a
							href="#"
							data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'active', 'id', $item['id'])); ?>"
							class="clickReplace"
						><?php echo $item['active'] == 1 ? 'Yes' : 'No'; ?></a>
					</td>
					<td>
						<a
							href="#"
							data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'default', 'id', $item['id'])); ?>"
							data-group="clickDefault<?php echo $item['local']; ?>"
							data-value="<?php echo $item['default']; ?>"
							class="clickYesNoSingle"
						><?php echo $item['default'] == 1 ? 'Yes' : 'No'; ?></a>
					</td>
					<td>
						<a
							href="#"
							data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'duplicate', 'id', $item['id'])); ?>"
							class="clickCallReload"
						>Duplicate</a>
					</td>
					<td>
						<a
							href="#"
							data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'remove', 'id', $item['id'])); ?>"
							class="clickAddRowConfirm"
							data-span="7"
						>Remove</a>
					</td>
				</tr>
			
			<?php } ?>
		
		</tbody>
	
	</table>
	
<?php } else { ?>

	<p>There are currently no records.</p>

<?php } ?>








