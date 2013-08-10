<?php
namespace application\nutsnbolts\plugin\notification
{
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;
	use nutshell\core\exception\NutshellException;

	class Notification extends Plugin implements Singleton
	{
		private $session	=null;
		
		private $template=<<<HTML
<div class="alert alert-{type}">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>{title}!</h4>
	{messages}
</div>
HTML;
		public function init()
		{
			$this->session=$this->plugin->Session();
			
			if (!is_array($this->session->successes))
			{
				$this->session->successes=array();
			}
			if (!is_array($this->session->infos))
			{
				$this->session->infos=array();
			}
			if (!is_array($this->session->warnings))
			{
				$this->session->warnings=array();
			}
			if (!is_array($this->session->errors))
			{
				$this->session->errors=array();
			}
			
			
		}
		
		public function setSuccess($message)
		{
			$successes=$this->session->successes;
			array_push($successes,$message);
			$this->session->successes=$successes;
			return $this;
		}
		
		public function setInfo($message)
		{
			$infos=$this->session->infos;
			array_push($infos,$message);
			$this->session->infos=$infos;
			return $this;
		}
		
		public function setWarning($message)
		{
			$warnings=$this->session->warnings;
			array_push($warnings,$message);
			$this->session->warnings=$warnings;
			return $this;
		}
		
		public function setError($message)
		{
			$errors=$this->session->errors;
			array_push($errors,$message);
			$this->session->errors=$errors;
			return $this;
		}
		
		public function getSuccesses()
		{
			return $this->session->successes;
		}
		
		public function getNotices()
		{
			return $this->session->notices;
		}
		
		public function getWarnings()
		{
			return $this->session->warnings;
		}
		
		public function getErrors()
		{
			return $this->session->errors;
		}
		
		public function clearAll()
		{
			$this->session->successes	=array();
			$this->session->infos		=array();
			$this->session->warnings	=array();
			$this->session->errors		=array();
		}
		
		public function getSucessesHTML()
		{
			if (count($this->session->successes))
			{
				return $this->parseTemplate
				(
					'success',
					'Success!',
					$this->arrayToList($this->session->successes)
				);
			}
			return '';
		}
		
		public function getInfosHTML()
		{
			if (count($this->session->infos))
			{
				return $this->parseTemplate
				(
					'info',
					'Hey!',
					$this->arrayToList($this->session->infos)
				);
			}
			return '';
		}
		
		public function getWarningsHTML()
		{
			if (count($this->session->warnings))
			{
				return $this->parseTemplate
				(
					'block',
					'Warning!',
					$this->arrayToList($this->session->warnings)
				);
			}
			return '';
		}
		
		public function getErrorsHTML()
		{
			if (count($this->session->errors))
			{
				return $this->parseTemplate
				(
					'error',
					'Error!',
					$this->arrayToList($this->session->errors)
				);
			}
			return '';
		}
		
		private function arrayToList($list)
		{
			$html=array('<ul>');
			for ($i=0,$j=count($list); $i<$j; $i++)
			{
				$html[]='<li>'.$list[$i].'</li>';
			}
			$html[]='</ul>';
			
			return implode('',$html);
		}
		
		private function parseTemplate($type,$title,$messages)
		{
			return str_replace
			(
				array('{type}','{title}','{messages}'),
				array($type,$title,$messages),
				$this->template
			);
		}
	}
}