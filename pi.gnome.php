<?php
	
	use Panchesco\Addons\Gnome\Library\NoEmbed;

	class Gnome {

		// No embed endpoint...
		public $endpoint = 'https://noembed.com/embed?url=';
		
		public function html()
		{

			$show_errors	= ee()->TMPL->fetch_param('show_errors','no');
			$url			= ee()->TMPL->fetch_param('url');
			$nowrap			= strtolower(ee()->TMPL->fetch_param('nowrap','no'));
			$maxwidth		= ee()->TMPL->fetch_param('maxwidth');
			$maxheight		= ee()->TMPL->fetch_param('maxheight');

			if($url)
			{

				$noembed = new NoEmbed();
				
				$obj = $noembed->response($url,$nowrap,$maxwidth,$maxheight);
				
				
				// If errors returned and debugging enabled, show them.
				if(isset($obj->error))
				{
					ee()->TMPL->log_item($obj->error);
					
					return ee()->TMPL->no_results();
					
				} else {
					
					$data = array();
					
					// Hack to get author name for Twitter.
					if( ! isset($obj->author_name))
					{
						if(isset($obj->provider_name) && $obj->provider_name=='Twitter')
						{
							$obj->author_name = str_ireplace('Tweet by ', '', $obj->title);
						}
					}
					
					foreach($obj as $key => $row)
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

		/**
		 * Return list of Noembed providers as tag pairs.
		 * @return response
		*/
		public function providers() 
		{
			
			$noembed = new NoEmbed();
				
			$objects = $noembed->providers();
			
			if( is_array($objects) && isset($objects[0]->name) )
			{
				
				foreach($objects as $key => $row)
				{
					$sorted[$row->name] = $row;
				}
				
				ksort($sorted);
				
				foreach($sorted as $key => $obj)
				{
					$patterns = array();
					
					foreach($obj->patterns as $index => $pattern)
					{
						
						$patterns[] = array('regex'=>$pattern);
					}
					
					$data[] = array('name' => $obj->name,'patterns' => $patterns);
					
				}

				return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,$data);
				

			} else {
			
				return ee()->TMPL->no_results();
			}
			
		}
			
		//-----------------------------------------------------------------------------
		
		
	}