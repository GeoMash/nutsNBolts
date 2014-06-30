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
					<div class="jquery_accordion" id="dataTables">
						<table cellpadding="0" cellspacing="0" border="0" class="dTable responsive">
							<thead>
							<tr>
								<th>
									<div>First Name</div>
								</th>
								<th>
									<div>Last Name</div>
								</th>
								<th>
									<div>Email</div>
								</th>
								<th>
									<div>Actions</div>
								</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$subscribers = $tpl->getSubscribers();
							$canDelete = $tpl->can('admin.content.subscription.delete');
							for ($i = 0, $j = count($subscribers); $i < $j; $i++):
								?>

								<tr class="jquery_accordion_header">
									<td>
										<a>
											<?php print $subscribers[$i]['name_first']; ?>
										</a>
									</td>
									<td><?php print $subscribers[$i]['name_last']; ?></td>
									<td><?php print $subscribers[$i]['email']; ?></td>
									<td class="center">

									</td>
								</tr>

<!--								Generating the sub-table that shows user subscriptions-->

								<tr>
									<td colspan="4">
										<table style="width: 100%" cellpadding="0" cellspacing="0" border="0"
											   class="dTable responsive">
											<thead>
											<tr>
												<th>
													<div>Package Name</div>
												</th>
												<th>
													<div>ARB ID.</div>
												</th>
												<th>
													<div>Creation Date</div>
												</th>
												<th>
													<div>Expiry Date</div>
												</th>
												<th>
													<div>Status</div>
												</th>
												<th>
													<div>Actions</div>
												</th>
											</tr>
											</thead>
											<tbody>
											<?php
											$userSubscriptions = $tpl->getUserSubscriptions($subscribers[$i]['id']);
											$canDelete = $tpl->can('admin.content.subscription.delete');
											for ($i = 0, $j = count($userSubscriptions); $i < $j; $i++):
												?>
												<tr>
													<td>
														<a href="/admin/subscriptions/subscribers/edit/<?php print $userSubscriptions[$i]['id']; ?>"><?php print $userSubscriptions[$i]['name']; ?></a>
													</td>
													<td><?php print $userSubscriptions[$i]['arb_id'] ?: "N/A"; ?></td>
													<td><?php print $userSubscriptions[$i]['timestamp']; ?></td>
													<td><?php print $userSubscriptions[$i]['expiry_timestamp'] ?: "N/A"; ?></td>
													<td><?php
														switch ($userSubscriptions[$i]['status']) {
															case \application\nutsNBolts\plugin\subscription\Subscription::STATUS_ACTIVE:
																print "Active";
																break;
															case \application\nutsNBolts\plugin\subscription\Subscription::STATUS_CANCELLED_AUTO:
																print "Cancelled Automatically";
																break;
															case \application\nutsNBolts\plugin\subscription\Subscription::STATUS_CANCELLED_MANUAL:
																print "Cancelled Manually";
																break;
															case \application\nutsNBolts\plugin\subscription\Subscription::STATUS_PENDING:
																print "Pending";
																break;
														}
														?></td>
													<td class="center">
														<?php
														if ($canDelete):
															?>
															<a href="/admin/subscriptions/subscribers/suspend/<?php print $userSubscriptions[$i]['id']; ?>">
																<button title="Suspend Subscription"
																		class="btn btn-mini btn-red"
																		data-toggle="tooltip">
																	<i class="icon-remove"></i>
																</button>
															</a>
														<?php else: ?>
															<a href="javascript:{}">
																<button title="Suspend Subscription"
																		class="btn btn-mini btn-red disabled"
																		data-toggle="tooltip">
																	<i class="icon-remove"></i>
																</button>
															</a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endfor; ?>
											</tbody>
										</table>
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