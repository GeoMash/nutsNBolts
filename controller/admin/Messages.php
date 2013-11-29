<?php
namespace application\nutsNBolts\controller\admin
{
    use application\nutsNBolts\base\AdminController;
    use nutshell\helper\ObjectHelper;

    class Messages extends AdminController
    {
        public function index()
        {
            $this->setContentView('admin/messages');
            $this->view->getContext()
                ->registerCallback
                (
                    'allMessages',
                    function()
                    {
                        print $this->generateMessageRows();
                    }
                );
            $this->addBreadcrumb('Messages','icon-inbox','messages');
            $this->view->render();
        }

        public function view($messageId)
        {
            if($record=$this->model->Message->read(array('id'=>$messageId)))
            {
                $this->model->Message->update(array('status'=>1),$search);
            }
            $this->view->setVar('record',$record);
            $this->setContentView('admin/viewMessage');
            $this->addBreadcrumb('Messages','icon-inbox','messages');
            $this->view->render();
        }

        private function generateMessageRows()
        {
            $records=$this->model->Message->read(array('to_user_id'=>$this->plugin->UserAuth->getUserId() ),array(),' ORDER BY id DESC');
            $html	=array();
            for ($i=0,$j=count($records); $i<$j; $i++)
            {
                if($records[$i]['status']==0)
                {
                    $html[]=<<<HTML
<tr>
	<td><b><a href="/admin/messages/view/{$records[$i]['id']}">{$records[$i]['subject']}</a></b></td>
	<td><b>{$records[$i]['body']}</b></td>
</tr>
HTML;
                }
                else
                {
                    $html[]=<<<HTML
<tr>
	<td><a href="/admin/messages/view/{$records[$i]['id']}">{$records[$i]['subject']}</a></td>
	<td>{$records[$i]['body']}</td>
</tr>
HTML;
                }
            }
            return implode('',$html);
        }
    }
}
?>