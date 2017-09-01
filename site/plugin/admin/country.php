<?php

use SSD\Paging;
use SSD\Helper;



if (!empty($data['rows'])) {
	
	unset($data['objUrl']->params['action']);
	unset($data['objUrl']->params['id']);
	
	$objPaging = new Paging($data['objUrl'], $data['rows'], 10);
	$countries = $objPaging->getRecords();

?>

<table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">

	<tr>
		<th>Country</th>
		<th class="col_1 ta_r">Active</th>
		<th class="col_1 ta_r">Remove</th>
	</tr>
	
	<tbody>
	
		<?php foreach($countries as $item) { ?>
		
			<tr id="row-<?php echo $item['id']; ?>">
			
				<td>
					<span class="clickHideShow" data-show="#name-<?php echo $item['id']; ?>">
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
				</td>
				<td class="ta_r">
					<a 
						href="#"
						data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'active', 'id', $item['id'])); ?>"
						class="clickReplace"
					><?php echo $item['include'] == 1 ? 'Yes' : 'No'; ?></a>
				</td>
				<td class="ta_r">
					<a 
						href="#"
						data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'remove', 'id', $item['id'])); ?>"
						class="clickAddRowConfirm"
						data-span="3"
					>Remove</a>
				</td>
			
			</tr>
		
		<?php } ?>
	
	</tbody>

</table>

<?php echo $objPaging->getPaging(); ?>

<?php } else { ?>

	<p>There are currently no records available.</p>

<?php } ?>













