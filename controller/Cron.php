<?php
namespace application\nutsNBolts\controller
{
    use application\nutsNBolts\base\Controller;
    use nutshell\helper\ObjectHelper;

    class Cron extends Controller
    {
        public function expiry()
        {
            $contentTypeId=	$this->plugin->Mvc->model->ContentType->read(array('ref'=>'BOTTLE_EXPIRY'));
            $nodeId=$this->plugin->Mvc->model->Node->read(array('content_type_id'=>$contentTypeId[0]['id']));
            $expiryDetails=		$this->plugin->Mvc->model->NodePart->read(array('node_id'=>$nodeId[0]['id']));

            // set to 7 days
            $expireIn=$expiryDetails[1]['value'];
            // set to 1 month
            $duration=$expiryDetails[0]['value'];
            // get all of the bottles
            $allBottles=$this->model->OpenBottle->cronGetAllBottles($duration,$expireIn,'expiry');

            if(count($allBottles))
            {
                for($i=0;$i<count($allBottles);$i++)
                {
                    $thisBottleOwner=$this->model->User->read(array('id'=>$allBottles[$i]['user_id']))[0];
                    $thisBottleBar=$this->model->Node->getWithParts(array('id'=>$allBottles[$i]['bar_id']))[0];
                    $thisBottleDetails=$this->model->Node->getWithParts(array('id'=>$allBottles[$i]['bottle_id']))[0];

                    $this->model->Sms->send(
                        $allBottles[$i]['bar_id'],
                        $allBottles[$i]['user_id'],
                        $thisBottleOwner['phone'],
                        "Your ".$thisBottleDetails['title']." bottle will expire in ".$expireIn." days. Please visit ".$thisBottleBar['title']." to redeem"
                    );

                }
            }
        }

        public function index()
        {
            $contentTypeId=	$this->plugin->Mvc->model->ContentType->read(array('ref'=>'BOTTLE_EXPIRY'));
            $nodeId=$this->plugin->Mvc->model->Node->read(array('content_type_id'=>$contentTypeId[0]['id']));
            $expiryDetails=		$this->plugin->Mvc->model->NodePart->read(array('node_id'=>$nodeId[0]['id']));

            // set to 7 days
            $expireIn=$expiryDetails[1]['value'];
            // set to 1 month
            $duration=$expiryDetails[0]['value'];
            // get all of the bottles
            $allBottles=$this->model->OpenBottle->cronGetAllBottles($duration,$expireIn,'expired');

            if(count($allBottles))
            {
                for($i=0;$i<count($allBottles);$i++)
                {
                    $thisBottleOwner=$this->model->User->read(array('id'=>$allBottles[$i]['user_id']))[0];
                    $thisBottleBar=$this->model->Node->getWithParts(array('id'=>$allBottles[$i]['bar_id']))[0];
                    $thisBottleDetails=$this->model->Node->getWithParts(array('id'=>$allBottles[$i]['bottle_id']))[0];

                    $this->model->Sms->send(
                        $allBottles[$i]['bar_id'],
                        $allBottles[$i]['user_id'],
                        $thisBottleOwner['phone'],
                        "Your ".$thisBottleDetails['title']." bottle in ".$thisBottleBar['title']." expired today."
                    );

                }
            }
        }
    }
}