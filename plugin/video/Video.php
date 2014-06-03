<?php
namespace application\nutsNBolts\plugin\video
{
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
			$type=self::getType($url);
			switch($type)
			{
				case 'youtube':
				{
					$videoId=self::getYoutubeVideoId($url);
					$thumbnail=null;
					
					switch($quality)
					{
						
						case 'normal':
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/default.jpg';
						}
						break;
						
						case 'medium':
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/mqdefault.jpg';
						}
						break;
						
						case 'high':
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/hqdefault.jpg';
						}
						break;
						
						default:
						{
							$thumbnail='http://i1.ytimg.com/vi/'.$videoId.'/default.jpg';
						}
					}
				}
				break;
				
				case 'vimeo':
				{
					$videoId=self::getVimeoVideoId($url);
					self::getVimeoThumbnail($videoId);
					
				}
				break;
				
				default:
				{
					exit('unknown type');
				}
			}
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
			
			return(json_decode($videoData->vide_id));
		}
		
		public function getVimeoThumbnail($videoId)
		{
			echo "inja";
			$hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$videoId.'.php'));
			print_r($hash);
//			echo $hash[0]['thumbnail_medium'];
		}
	}
}