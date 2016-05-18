<?php

	namespace Panchesco\Addons\Gnome\Library;

	class NoEmbed {
		
		public $unsupported;
		
		function __construct()
		{
			// Set array of non-noembed provider names.
			$this->unsupported = $this->unsupportedProviders();
				
		}
			   
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
		   * Return an array of params for providers.
		   * @param $provider string
		   * @return array
		   *
		  */
		  public function player_params($provider) 
		  {
		  	$provider = strtolower($provider);
		  	
		  	$player_params = array(
			  	
			  	'youtube'	=> array('autoplay',
			  					'rel',
			  					'showinfo',
			  					'controls',
			  					'modestbranding',
			  					'color',
			  					'cc_load_policy',
			  					'disablekb',
			  					'end',
			  					'fs',
			  					'iv_load_policy',
			  					'loop',
			  					'start',
			  					),
			  					
			  	'vimeo'	=> array('autopause',
						'autoplay',
						'badge',
						'byline',
						'color',
						'loop',
						'player_id',
						'portrait',
						'title'
						),
		  	);
		  	
		  	if(isset($player_params[$provider]))
		  	{
			  	return $player_params[$provider];
		  	
		  		} else {
			  	
			  	return array();
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
		
		
		/**
		 * Return array of regex patterns for providers.
		 */
		 public function providers_array()
		 {
			 $regx['boingboing'][]		= "http:\/\/boingboing\.net\/\d{4}\/\d{2}\/\d{2}\/[^\/]+\.html";
			 $regx['flickr'][]			= "https?:\/\/(?:www\.)?flickr\.com\/.*";
			 $regx['flickr'][]			= "https?:\/\/flic\.kr\/p\/[a-zA-Z0-9]+";
			 $regx['gist'][]			= "https?:\/\/gist\.github\.com\/(?:[-0-9a-zA-Z]+\/)?([0-9a-fA-f]+)";
			 $regx['imdb'][]			= "http:\/\/(?:www\.)?imdb\.com\/title\/(tt\d+)\/?";
			 $regx['instagram'][]		= "https?:\/\/(?:www\.)?instagram\.com\/p\/.+";
			 $regx['mixcloud'][]		= "https?:\/\/(?:www\.)?mixcloud\.com\/(.+)";
			 	//$regx['slideshare'][]	= "http:\/\/www\.slideshare\.net\/.*\/.*";
			 $regx['soundcloud'][]		= "https?:\/\/soundcloud.com\/.*\/.*";
				 //$regx['ted'][]			= "http:\/\/www\.ted\.com\/talks\/.+\.html";
			 $regx['twitter'][]		= "https?:\/\/(?:www\.)?twitter\.com\/(?:#!\/)?[^\/]+\/status(?:es)?\/(\d+)";
			 $regx['vimeo'][]		= "https?:\/\/(?:www\.)?vimeo\.com\/.+";
			 $regx['wikipedia'][]	= "https?:\/\/[^\.]+\.wikipedia\.org\/wiki\/(?!Talk:)[^#]+(?:#(.+))?";
			 $regx['wired'][]		= "https?:\/\/(?:www\.)?wired\.com\/.*";
			 $regx['youtube'][]		= "https?:\/\/(?:[^\.]+\.)?youtube\.com\/watch\/?\?(?:.+&)?v=([^&]+)";
			 $regx['youtube'][]		= "https?:\/\/youtu\.be\/([a-zA-Z0-9_-]+)";
             
			 return $regx;
		 }
		 
		 // ----------------------------------------------------------------------------- 
		 
		 /**
			* Get the provider key for a URL.
			* @param $url string
			* @return $string
			*/
		   public function provider_key($url)
		   {
			  $providers = $this->providers_array();

			   foreach($providers as $key => $row)
			   {
				   
				   foreach($row as $pattern)
				   {
					   if(preg_match("~" . $pattern . "~",$url))
					   {
						   return $key;
					   }
					   
				   } 
			   }
			   
			   return '';
		   }
		   
		   // ----------------------------------------------------------------------------- 
		   
		   /**
		    * Return Noembed gateway response as decoded php array of objects.
		    * @param $url string
		    * @param $nowrap mixed string/boolean
		    * @param $maxwidth mixed string/boolean 
		    * @param $maxheight mixed string/boolean
		   */
		   public function response($url,$nowrap=FALSE,$maxwidth=FALSE,$maxheight=FALSE) 
		   {
		   		
		   		// Strip out www. 
		   		$url = str_ireplace('www.','',$url);
		   		
		   		
		   		$provider = $this->provider_key($url);
		   	
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
		   		
		   		if( ! in_array($provider,$this->unsupported))
		   		{
		   			$oembed_url = 'https://noembed.com/embed?url=';
		   		
		   			// Add to endpoint.
		   			$url = $oembed_url . $url;
		   		
		   			$response = $this->curl_get($url);
		   			
		   			} else {
		   		
		   			$response = $this->noNoEmbed($provider,$url);
		   			
		   		}
		   		
		   		if ( ! empty($response))
		   		{
		   			
		   			$response = json_decode($response);
		   			
		   			// Adding a "slug" version of the provider name.
		   			$response->provider_slug = strtolower($response->provider_name);
		   			$response->provider_slug = preg_replace("/[^[:alnum:]]/","-",$response->provider_slug);
		   			
		   			return $response;
		   			
		   			} else {
		   				
		   			return (object) ['error' => 'cURL error'];
		   		}
		   }
		   	
		   //-----------------------------------------------------------------------------
		 
		 /**
		  * Return array of regex patters for an array of providers.
		  * @param $sources array
		  * @return array
		  */
		 public function source_patterns($sources)
		 {
			 $data		= array();
			 
			 $providers	= $this->providers_array();
			 
			 foreach($sources as $key)
			 {
				 if(array_key_exists($key, $providers))
				 {
					foreach($providers[$key] as $pattern){
						 	$data[] = $pattern;
					 	};
				 }
			 }
			 
			 return $data;
		 
		 }
		 
		 // ----------------------------------------------------------------------------- 
		 

		 /** 
		  * Get an oEmbed response object for non-supported provider.
		  * @param $provider string
		  * @param $url string
		  * @return string
		  */
		  private function noNoEmbed($provider,$url)
		  {
			  $reponse = FALSE; // Pessimist...
			  
			  switch($provider)
			  {
				  case 'mixcloud':
				  $url = 'https://www.mixcloud.com/oembed/?url=' . $url. '&format=json';
				  $response = $this->curl_get($url);
				  break;
				  
			  }
			  
			  if($response)
			  {
				  return $response;
			  } else {
				  
				  $response = (object) ['error' => 'Provider ' . $provider . ' not currently supported'];
				  return json_encode($response);
			  }
			  
		  }
		  

		  // ----------------------------------------------------------------------------- 
		  
		  /**
		   * Return array of providers not currently supported by Noembed we'll attempt
		   * to fetch a response for using our own call to.
		   * @return array
		   */
		   private function unsupportedProviders()
		   {
			   return array(
				   'mixcloud',
			   );
		   }
		   
		   // ----------------------------------------------------------------------------- 

	}