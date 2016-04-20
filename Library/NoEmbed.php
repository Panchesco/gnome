<?php

	namespace Panchesco\Addons\Gnome\Library;

	class NoEmbed {
			   
		/**
		 * Request via cURL.
		 * @return object
		 */
		public function curl_get($url) 
		{
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			$return = curl_exec($curl);
			curl_close($curl);
			return $return;
		}
		
		//-----------------------------------------------------------------------------	
		
		/**
		 * Return Noembed gateway response as decoded php array of objects.
		 * @param $url string
		 * @param $nowrap mixed string/boolean
		 * @param $maxwidth mixed string/boolean 
		 * @param $maxheight mixed string/boolean
		*/
		public function response($url,$nowrap=FALSE,$maxwidth=FALSE,$maxheight=FALSE) 
		{
				$oembed_url = 'https://noembed.com/embed?url=';
				
				// Strip out www. 
				$url = str_ireplace('www.','',$url);
				
				// Encode URL.
				$url = rawurlencode($url);
				
				// Add nowrap, maxwidth, maxheight params.
				if(in_array($nowrap,array('yes','on','true',1)))
				{
					$url.= '&nowrap=on';
				}
				
				if($maxwidth)
				{
					$url.= '&maxwidth=' . $maxwidth;
				}
				
				if($maxheight)
				{
					$url.= '&maxheight=' . $maxheight;
				}
				
				// Add to endpoint.
				$url = $oembed_url . $url;
				
				$response = $this->curl_get($url);
				
				
				if ( ! empty($response))
				{
					return json_decode($response);
					
					} else {
						
					return (object) ['error' => 'cURL error'];
				}
		}
			
		//-----------------------------------------------------------------------------
		
		/**
		 * Get Noembed array of providers.
		 * @return array
		*/
		public function providers() 
		{
			$response = $this->curl_get('https://noembed.com/providers');
			return json_decode($response);
		}
			
		//-----------------------------------------------------------------------------
		
	}