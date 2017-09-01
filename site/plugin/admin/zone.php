<?php

use SSD\Helper;

if (!empty($data['rows'])) {
?>
	
	<table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
	
		<tr>
			<th>Zone</th>
			<th class="col_1 ta_r">Post codes</th>
			<th class="col_1 ta_r">Remove</th>
		</tr>
		
		<tbody>
		
			<?php foreach($data['rows'] as $item) { ?>
			
				<tr id="row-<?php echo $item['id']; ?>">
					<td>
						<span 
							class="clickHideShow" 
							data-show="#name-<?php echo $item['id']; ?>"
						>
							<?php echo Helper::encodeHtml($item['name']); ?>
						</span>
						<input 
							type="text"
							name="name-<?php echo $item['id']; ?>"
							id="name-<?php echo $item['id']; ?>"
							class="fld blurUpdateHideShow dn"
							data-id="<?php echo $item['id']; ?>"
							value="<?php echo $item['name']; ?>"
						/>
						<div class="textSmall"><?php echo $item['post_codes']; ?></div>	
					</td>
					<td class="ta_r">
						<a 
							href="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'codes', 'id', $item['id'])); ?>"
						>Post codes</a>
					</td>
					<td class="ta_r">
						<a 
							href="#"
							class="clickAddRowConfirm"
							data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'remove', 'id', $item['id'])); ?>"
							data-span="3"
						>Remove</a>
					</td>
				</tr>
			
			<?php } ?>
		
		</tbody>
	
	</table>
	
<?php } else { ?>

	<p>There are currently no records.</p>

<?php } ?>



