<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<span class="title">Subscriptions</span>
					<ul class="box-toolbar">
						<li>
							<a href="/admin/configurecontent/subscriptions/add/">
								<span class="triangle-button green"><i class="icon-plus"></i></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="box-content">
					<div id="dataTables">
						<table cellpadding="0" cellspacing="0" border="0" class="dTable responsive">
							<thead>
								<tr>
									<th><div>First Name</div></th>
									<th><div>Last Name</div></th>
									<th><div>Email</div></th>
									<th><div>Actions</div></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$subscribers=$tpl->getSubscribers();
								$canDelete=$tpl->can('admin.content.subscription.delete');
								for ($i=0,$j=count($subscribers); $i<$j; $i++):
								?>
								<tr>
									<td><a href="/admin/subscriptions/subscribers/edit/<?php print $subscribers[$i]['id']; ?>"><?php print $subscribers[$i]['name_first']; ?></a></td>
									<td><?php print $subscribers[$i]['name_last']; ?> Months</td>
									<td><?php print $subscribers[$i]['email']; ?></td>
									<td class="center">
										<?php
										if ($canDelete):
										?>
										<a href="/admin/subscriptions/subscribers/remove/<?php print $subscribers[$i]['id']; ?>">
											<button title="Remove Subscription" class="btn btn-mini btn-red" data-toggle="tooltip">
												<i class="icon-remove"></i>
											</button>
										</a>
										<?php else: ?>
										<a href="javascript:{}">
											<button title="Remove Subscription" class="btn btn-mini btn-red disabled" data-toggle="tooltip">
												<i class="icon-remove"></i>
											</button>
										</a>
										<?php endif; ?>
									</td>
								</tr>
								<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>