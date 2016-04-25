<?php
	
	use Panchesco\Addons\Gnome\Library\NoEmbed;

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
							 
						 	return $this->fetch_response($matches[0],$this->nowrap,$this->maxwidth,$this->maxheight)->html;
						 
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
			$nowrap			= strtolower(ee()->TMPL->fetch_param('nowrap','no'));
			$maxwidth		= ee()->TMPL->fetch_param('maxwidth');
			$maxheight		= ee()->TMPL->fetch_param('maxheight');

			if($url)
			{

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

					return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,$data);
				}
			} 
			
			ee()->TMPL->log_item('No URL parameter');
			
			return ee()->TMPL->no_results();	
		}
		
		//-----------------------------------------------------------------------------
		 
		 private function fetch_response($url,$nowrap=FALSE,$maxwidth=FALSE,$maxheight=FALSE)
		 {
			 	$noembed = new NoEmbed();

				return $noembed->response($url,$nowrap,$maxwidth,$maxheight);
		 }
		 
		 // ----------------------------------------------------------------------------- 
		 
	}