<?php
namespace application\nutsNBolts\plugin\video
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Video extends Plugin implements Singleton
	{
		public function init()
		{

		}

		public function getBlogArticle($id)
		{
			return $record=$this->plugin->Mvc->model->Node->getBlog($id);
		}

		public function getType($url)
		{
			if(preg_match("/youtube.com/", $url))
			{
				return 'youtube';
			}
			
			if(preg_match("/vimeo.com/", $url))
			{
				return 'vimeo';
			}
		}
		
		public function getThumbnail($url,$quality='medium')
		{
			$type=$this->getType($url);
			switch($type)
			{
				case 'youtube':
				{
					$videoId=$this->getYoutubeVideoId($url);
					$thumbnail=null;
					
					switch($quality)
					{
						
						case 'normal':
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/default.jpg';
							break;
						}
						
						case 'medium':
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/mqdefault.jpg';
							break;
						}
						
						case 'high':
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/hqdefault.jpg';
							break;
						}
						

						default:
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/default.jpg';
						}
					}
				}
				break;
				
				case 'vimeo':
				{
					$videoId=$this->getVimeoVideoId($url);
					$thumbnail=$this->getVimeoThumbnail($videoId->video_id, $quality);
					break;
				}

				default:
				{
					exit('unknown type');
				}
			}
			
			return $thumbnail;
		}
		
		public function getYoutubeVideoId($url)
		{
			parse_str
			(
				parse_url( $url, PHP_URL_QUERY ), 
				$vars 
			);
			return $vars['v']; 			
		}
		
		public function getVimeoVideoId($url)
		{
			$apiCall		='http://vimeo.com/api/oembed.json?url='.urlencode($url);
			$videoData		=file_get_contents($apiCall);
		
			return
			(
				json_decode($videoData)->video_id
			);
		}
		
		public function getVimeoThumbnail($videoId, $quality='medium')
		{
			$hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$videoId.'.php'));
			switch($quality)
			{
				case 'normal':
				{
					$thumbnail=$hash[0]['thumbnail_small'];
					break;
				}
					
				case 'medium':
				{
					$thumbnail=$hash[0]['thumbnail_medium'];
					break;
				}
			
				case 'high':
				{
					$thumbnail=$hash[0]['thumbnail_large'];
					break;
				}
	
				default:
				{
					$thumbnail=$hash[0]['thumbnail_medium'];
				}					
			}
			return $thumbnail;
		}
		
		public function renderPlayer($url)
		{
			$html=null;
			switch($this->getType($url))
			{
				case 'youtube':
				{
					$html= $this->renderYoutube($url);
					break;
				}
					
				case 'vimeo':
				{
					$html= $this->renderVimeo($url);
					break;
				}
			}
			return $html;
		}
		
		public function renderYoutube($url)
		{
			parse_str(parse_url($url, PHP_URL_QUERY), $variables);
			return '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$variables['v'].'" frameborder="0" allowfullscreen></iframe>';
		}
		
		public function renderVimeo($url)
		{
			$videoId=$this->getVimeoVideoId($url);
			return '<iframe src="http://player.vimeo.com/video/'.$videoId.'" width="560" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}
	}
}