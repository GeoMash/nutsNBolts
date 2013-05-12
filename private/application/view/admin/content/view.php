<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header"><span class="title"><?php $tpl->tableHeaderText; ?></span></div>
				<div class="box-content">
					<div id="dataTables">
						<table cellpadding="0" cellspacing="0" border="0" class="dTable responsive">
							<thead>
								<tr>
									<th><div>Title</div></th>
									<th><div>Date Created</div></th>
									<th><div>Last Edited By</div></th>
									<th><div>Published</div></th>
								</tr>
							</thead>
							<tbody>
								<?php $tpl->records(); ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>