<?php

// https://developers.google.com/youtube/player_parameters
	
	namespace Panchesco\Addons\Gnome\Library;

	class YouTube {
		
	
	var $url;	
	var $video_id;	
	var $width	= false;
	var $height	= false;
	var $params	= array('autoplay' => 0,
			  			'rel' => 1,
			  			'showinfo' => 1,
			  			'controls' => 1,
			  			'modestbranding' => 0,
			  			'color' => 'red',
			  			'cc_load_policy' => 1,
			  			'disablekb' => 0,
			  			'end' => false,
			  			'fs' => 1,
			  			'iv_load_policy' => 1,
			  			'loop' => 0,
			  			'start' => 0,
			  			);
	var $endpoint		= 'https://www.youtube.com/';
	
			/**
			 * 
			 *
			*/
			function __construct($url='',$params =[]) 
			{
				// Get the video_id from the url and set it.
				
				$this->video_id = $this->video_id($url);
				
				$this->url = $url;
				
				foreach($params as $key => $val){
					
					$this->params[$key] = $val;
				}

			}
				
			//-----------------------------------------------------------------------------
		
			/**
			 * Return html for embedded YouTube player.
			 * @param $width integer,
			 * @param $height integer
			 * @param $params array
			 * @param $extra string
			 * 
			 * @return string
			*/
			public function embed($params=[],$width=false,$height=false,$extra=false) 
			{
				$str = '';
				
				if($this->video_id)
				{
					$str.= '<iframe ';
					$str.= ( $extra !== false) ? $extra  . ' ': '';
					$str.= ( $width !== false ) ? 'width="' . $width . '" ' : '';
					$str.= ( $height !== false ) ? 'height="' . $height . '" ' : '';
					$str.= 'type="text/html" frameborder="0" ';
					$str.= 'src="' . $this->endpoint . 'embed/' . $this->video_id;
					$str.= $this->param_string($params);
					$str.= '"';
					$str.= '>';
					$str.= '</iframe>';
				}
					
				return $str;	
			}
				
			//-----------------------------------------------------------------------------	
			
			/**
			 * Return string of youtube params from an array.
			 * @param $params array
			 * @return string
			 *
			*/
			public function param_string($params=[]) 
			{
				$str = '';
				
				foreach($params as $key => $val)
				{
					if(array_key_exists($key,$this->params))
					{
						$str.= $key . '=' . $val . '&';
					}
				}
				
				$str = trim($str,'&');
				$str = htmlentities($str);
				
				return ( !empty($str)) ? '?' . $str : '';
				
			}
				
			//-----------------------------------------------------------------------------
			
			/**
			 * Extract the video id from a url.
			 * @param $url string
			 * @return string
			 *
			*/
			public function video_id($url) 
			{
				$video_id = NULL;
				
				if(strpos($url, '//youtu.be/')>0)
				{
					$url		= str_replace('https','http',$url);
					$url		= str_replace('http://youtu.be/','',$url);
					$parse	= parse_url($url);
					
					if(isset($parse['path']))
					{
						$video_id = $parse['path'];
					}

				} else {

					$parse	= parse_url($url);
				
					if(isset($parse['query']))
					{
						$parse = parse_str($parse['query'],$values);
					
						if(isset($values['v']))
						{
							$video_id =  $values['v'];
						}
					}
				}
				
				return $video_id;
			}
				
			//-----------------------------------------------------------------------------
		
		
	}