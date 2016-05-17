<?php
	
	use Panchesco\Addons\Gnome\Library\NoEmbed;
	use Panchesco\Addons\Gnome\Library\YouTube;

	class Gnome {
		
		public $nowrap;
		public $maxwith;
		public $maxheight;

		/**
		 * Bulk process tagdata.
		 */
		 public function bulk()
		 {
			$this->nowrap		= strtolower(ee()->TMPL->fetch_param('nowrap','no'));
			$this->maxwidth		= ee()->TMPL->fetch_param('maxwidth');
			$this->maxheight	= ee()->TMPL->fetch_param('maxheight');
			 
			 $str = '';
			 
			 $tagdata = str_replace("\r","\n",ee()->TMPL->tagdata);
			 
			 if(ee()->TMPL->fetch_param('sources')) {
				 	$sources = explode("|",strtolower(ee()->TMPL->fetch_param('sources')));
				 } else {
					 $sources = array();
				 }
				 
			 $noembed = new NoEmbed();
			 $patterns = $noembed->source_patterns($sources);
			 
			 
			 $lines = explode("\n",$tagdata);
			 
			 foreach($lines as $line)
			 {
				 foreach($patterns as $regx)
				 {
						$line = preg_replace_callback("|" . $regx . "|", function($matches){
						 if(isset($matches[0]))
						 {
							$response = $this->fetch_response($matches[0],$this->nowrap,$this->maxwidth,$this->maxheight);
							
							// Lets instantiate another noembed object in here so we can check if
							// a player we can configure. 
							$noembed = new NoEmbed();
							$provider = $noembed->provider_key($response->url);
							
							switch($provider)
							{
								case 'youtube':
									if($this->nowrap != 'no')
									{
										$response->html = $this->youtube_player($response->url);
									}
								break;
							}
							
							return $response->html;
						 
						 } else {
							 return '';
						 }
					 }, $line);
				 }

				 $str.= $line;
			 }

			 return $str;
		 }
		 
		 // ----------------------------------------------------------------------------- 
		
		public function html()
		{

			$noembed = new NoEmbed();
			
			$show_errors	= ee()->TMPL->fetch_param('show_errors','no');
			$url			= ee()->TMPL->fetch_param('url');
			$nowrap		= strtolower(ee()->TMPL->fetch_param('nowrap','no'));
			$maxwidth		= ee()->TMPL->fetch_param('maxwidth');
			$maxheight	= ee()->TMPL->fetch_param('maxheight');

			if($url)
			{

				$provider = $noembed->provider_key($url);

				$response = $this->fetch_response($url,$nowrap,$maxwidth,$maxheight);

				// If errors returned and debugging enabled, show them.
				if(isset($response->error))
				{
					ee()->TMPL->log_item($response->error);
					
					return ee()->TMPL->no_results();
					
				} else {
					
					$data = array();
					
					// Hack to get author name for Twitter.
					if( ! isset($response->author_name))
					{
						if(isset($response->provider_name) && $response->provider_name=='Twitter')
						{
							$response->author_name = str_ireplace('Tweet by ', '', $response->title);
						}
					}
					
					foreach($response as $key => $row)
					{
						$data[0]['gnome_' . $key] = $row;
					}
					
					// If this is a youtube url, allow customization of the player.
					if($provider == 'youtube' && $nowrap!='no')
					{
						$data[0]['gnome_html'] = $this->youtube_player($url);
					}
					
					return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,$data);
				}
			} 
			
			ee()->TMPL->log_item('No URL parameter');
			
			return ee()->TMPL->no_results();	
		}
		
		//-----------------------------------------------------------------------------
		
		/**
		 * Return YouTube player HTML
		 * @param $url mixed bool/string
		 *
		*/
		public function youtube_player($url=false) 
		{
					// Find the URL;
					if($url===false)
					{
						$url = ee()->TMPL->fetch_param('url');
					}
					
					
					$noembed = new NoEmbed();
					
					$provider = $noembed->provider_key($url);
					
					// Check template for params.
					
					$width	= ee()->TMPL->fetch_param('maxwidth');
					$height	= ee()->TMPL->fetch_param('maxheight');
					$extra	= ee()->TMPL->fetch_param('extra');
					
					
					// Get provider parameters so we can check the template for them.
					$provider_params = $noembed->player_params('youtube');
					
					$params = array();
					
					
					foreach($provider_params as $param)
					{
						$val = ee()->TMPL->fetch_param($param);
						
						if($val)
						{
							$params[$param] = $this->translate_param($provider,$val);
						}
					}
					
					return $this->youtube_html($url,$params,$width,$height,$extra);
		}
			
		//-----------------------------------------------------------------------------
		 
		 private function fetch_response($url,$nowrap=FALSE,$maxwidth=FALSE,$maxheight=FALSE)
		 {
			 	$noembed = new NoEmbed();

				return $noembed->response($url,$nowrap,$maxwidth,$maxheight);
		 }
		 
		 // ----------------------------------------------------------------------------- 
		 
		 
		 /**
		  * Translate ee template yes/no, on/off params to provider values.
		  * @param $provider $string
		  * @param $param $string
		  * @return string
		  */
		 private function translate_param($provider,$param)
		 {
			 	switch($provider)
			 	{
				 	
				 	case 'youtube': 
				 		
				 		$param = str_ireplace(array('yes','y','on','true'), 1, $param);
				 		$param = str_ireplace(array('no','n','off','false'), 0, $param);
				 		
				 	break;
			 	}

			 	return $param;
		 }
		 
		 // ----------------------------------------------------------------------------- 
		 
		/**
		 * 
		 *
		*/
		private function youtube_html($url='',$params,$width=false,$height=false,$extra=false) 
		{
			$youtube = new YouTube($url);
			
			
			return $youtube->embed($params,$width,$height,$extra);
			
		}
			
		//-----------------------------------------------------------------------------
		 
	}