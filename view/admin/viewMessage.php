<?php $message=$tpl->get('record')[0];?>
<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="box padded span5">
			<h4><?php echo $message['subject']; ?></h4>
			<p><?php echo $message['body']; ?></p>
			<a href="/admin/messages"><button class="btn btn-red" type="button">Back</button></a>
		</div>
		
	</div>
</div>
