<?php

use SSD\Paging;


if (!empty($data['rows'])) {
	
	unset($data['objUrl']->params['call']);
	
	$objPaging = new Paging($data['objUrl'], $data['rows'], 10);
	$postCodes = $objPaging->getRecords();
	
?>

<table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">

	<tr>
		<th>Post code</th>
		<th class="col_1 ta_r">Remove</th>
	</tr>
	
	<tbody>
	
		<?php foreach($postCodes as $item) { ?>
		
			<tr id="row-<?php echo $item['id']; ?>">
				<td>
					<?php echo $item['post_code']; ?>
				</td>
				<td class="ta_r">
					<a 
						href="#"
						class="clickAddRowConfirm"
						data-url="<?php echo $data['objUrl']->getCurrent(array('call', 'cid'), false, array('call', 'remove', 'cid', $item['id'])); ?>"
						data-span="2"
					>Remove</a>
				</td>
			</tr>
		
		<?php } ?>
	
	</tbody>

</table>

<?php echo $objPaging->getPaging(); ?>

<?php } else { ?>

	<p>There are currently no post codes available.</p>

<?php } ?>















