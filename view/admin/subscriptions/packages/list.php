<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<span class="title">Subscriptions</span>
					<ul class="box-toolbar">
						<li>
							<a href="/admin/subscriptions/packages/add/">
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
									<th><div>Name</div></th>
									<th><div>Duration</div></th>
									<th><div>Amount</div></th>
									<th><div>Trial Occurrences</div></th>
									<th><div>Total Occurrences</div></th>
									<th><div>Status</div></th>
									<th><div>Actions</div></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$subscriptions=$tpl->getSubscriptions();
								$canDelete=$tpl->can('admin.subscription.package.delete');
								for ($i=0,$j=count($subscriptions); $i<$j; $i++):
								?>
								<tr>
									<td><a href="/admin/subscriptions/packages/edit/<?php print $subscriptions[$i]['id']; ?>"><?php print $subscriptions[$i]['name']; ?></a></td>
									<td><?php print $subscriptions[$i]['duration']; ?> Months</td>
									<td><?php print $subscriptions[$i]['amount'].' '.$subscriptions[$i]['currency']; ?></td>
									<td><?php print $subscriptions[$i]['trial_occurrences']? : "N/A"; ?></td>
									<td><?php print $subscriptions[$i]['total_occurrences']? : "N/A"; ?></td>
									<td><?php print (bool)$subscriptions[$i]['status']?'Enabled':'Disabled'; ?></td>
									<td class="center">
										<?php if ($canDelete): ?>
										<a href="/admin/subscriptions/packages/remove/<?php print $subscriptions[$i]['id']; ?>">
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