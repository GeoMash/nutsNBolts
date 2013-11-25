<?php
/**
 * Database Model for "sms"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 23/10/2013 
 */
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Sms as SmsBase;
	use DateTime;
	use DateInterval;
	class Sms extends SmsBase	
	{
		public function send($barId,$toUserId,$mobileNumber,$message)
		{
            // add the country code
            $mobileNumber.="06";
			//Send SMS
			$SMS=$this->plugin->Sms('M3Tech');
			$SMS->setMobileNumber($mobileNumber)
				->setMessage($message)
				->send();

			//Insert SMS Record
			$this->model->Sms->insertAssoc
			(
				array
				(
					'bar_id'	=>$barId,
					'user_id'	=>$toUserId,
					'number'	=>$mobileNumber,
					'message'	=>$message
				)
			);
			//Check Quota
			$quotas=$this->model->BarQuota->read(array('bar_id'=>$barId));
			for ($i=0,$j=count($quotas); $i<$j; $i++)
			{
				$package	=$this->model->Node->getWithParts(array('id'=>$quotas[$i]['package_id']));
				$quotaUsed	=0;
				if (isset($package[0]))
				{
					//Get interval.
					switch ($package[0]['quota_period'])
					{
						case 'yearly':
						{
							$interval	=new DateInterval('P1Y');
							$intervalSQL='1 YEAR';
							break;
						}
						case 'monthly':
						{
							$interval=new DateInterval('P1M');
							$intervalSQL='1 MONTH';
							break;
						}
						case 'daily':
						{
							$interval=new DateInterval('P1D');
							$intervalSQL='1 DAY';
							break;
						}
					}
					$query=<<<SQL
SELECT COUNT(id) as total
FROM sms
WHERE date_sent BETWEEN ? AND DATE_ADD(CURDATE(), INTERVAL {$intervalSQL});
SQL;
					if ($this->plugin->Db->nutsnbolts->select($query,array($quotas[$i]['date_started'])))
					{
						$result=$this->plugin->Db->nutsnbolts->result('assoc');
						if (isset($result[0]))
						{
							$quotaUsed=$result[0]['total'];
						}
						$now	=new DateTime();
						$expires=new DateTime($quotas[$i]['date_started']);
						//Do we care about this package?
						if ($now<$expires)
						{
							//Nope...
							continue;
						}
						//Yeah we do.
						else
						{
							$percentage=ceil(100*($quotaUsed/$package[0]['quota']));
							if ($percentage>=$this->config->application->smsQuotaAlert)
							{
								$userBars=$this->model->UserBar->read(array('bar_id'=>$barId));
								if (isset($userBars[0]))
								{
									for ($k=0,$l=count($userBars); $k<$l; $k++)
									{
										$bar=$this->model->Node->getWithParts(array('id'=>$userBars[$k]['bar_id']));
										if (isset($bar[0]))
										{
											$message=<<<HTML
SMS Quota Warning!
You have used %{$percentage} of your {$package[0]['quota_period']} subscription.
Please contact an administrator to purchase another subscription.
HTML;
											$this->plugin->Message->sendMessage
											(
												$userBars[$k]['user_id'],
												'SMS Quota Alert',
												$message
											);
											$message=<<<HTML
SMS Quota Warning!
The bar "{$bar[0]['title']}" have used %{$percentage} of their {$package[0]['quota_period']} subscription.
Please contact them to renew their subscription.
HTML;
											$adminUsers=$this->model->User->getUsersByRole('ADMIN');
											for ($m=0,$n=count($adminUsers); $m<$n; $m++)
											{
												$this->plugin->Message->sendMessage
												(
													$adminUsers[$i]['id'],
													'SMS Quota Alert',
													$message
												);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
?>