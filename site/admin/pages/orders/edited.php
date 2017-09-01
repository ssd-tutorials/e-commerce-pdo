<?php
$url = $this->objUrl->getCurrent(array('action', 'id'));
require_once('_header.php');
?>
<h1>Orders :: View</h1>
<p>The record has been updated successfully.<br />
<a href="<?php echo $url; ?>">Go back to the list of orders.</a></p>
<?php require_once('_footer.php'); ?>