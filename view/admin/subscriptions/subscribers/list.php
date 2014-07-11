<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<span class="title">Subscriptions</span>
					<ul class="box-toolbar">
						<li>
							<a href="/admin/subscriptions/subscribers/add/">
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
								<?php 
								$userSubscriptions = $tpl->getUserSubscriptions($subscribers[$i]['id']);
								if(count($userSubscriptions) > 0):
								?>
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
											$canDelete = $tpl->can('admin.content.subscription.delete');
											for ($i1 = 0, $j1 = count($userSubscriptions); $i1 < $j1; $i1++):
												?>
												<tr>
													<td>
														<a href="/admin/subscriptions/subscribers/edit/<?php print $userSubscriptions[$i1]['id']; ?>"><?php print $userSubscriptions[$i1]['name']; ?></a>
													</td>
													<td><?php print $userSubscriptions[$i1]['arb_id'] ?: "N/A"; ?></td>
													<td><?php print $userSubscriptions[$i1]['timestamp']; ?></td>
													<td><?php print $userSubscriptions[$i1]['expiry_timestamp'] ?: "N/A"; ?></td>
													<td><?php print $tpl->formatStatus($userSubscriptions[$i1]['status']); ?></td>
													<td class="center">
														<?php
														if ($canDelete):
															?>
															<a href="/admin/subscriptions/subscribers/suspend/<?php print $userSubscriptions[$i1]['id']; ?>">
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
								<?php endif; ?>

							<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>