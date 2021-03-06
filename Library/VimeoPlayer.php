<?php

// https://developer.vimeo.com/player/embedding
	
	namespace Panchesco\Addons\Gnome\Library;

	class VimeoPlayer {
		
	
	var $url;	
	var $video_id;	
	var $width	= false;
	var $height	= false;
	var $params	= array('autopause'	=> 1,
						'autoplay'	=> 0,
						'badge'	=> 1,
						'byline'	=> 1,
						'color'	=> '#00adef',
						'loop'	=> 0,
						'player_id'	=> '',
						'portrait'	=> 1,
						'title'	=> 1
						);
	
	var $endpoint		= 'https://player.vimeo.com/video/';
	
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
			 * Return html for embedded Vimeo player.
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
					$str.= 'src="' . $this->endpoint . $this->video_id;
					$str.= $this->param_string($params);
					$str.= '"';
					$str.= '>';
					$str.= '</iframe>';
				}
				
				return $str;	
			}
				
			//-----------------------------------------------------------------------------	
			
			/**
			 * Return string of Vimeo params from an array.
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
				//$str = htmlentities($str);
				
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
				return preg_replace("/[^[:digit:]]/","",$url);
			}
				
			//-----------------------------------------------------------------------------
	}