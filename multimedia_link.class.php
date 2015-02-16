<?php
/* Copyright (C) NAVER <http://www.navercorp.com> */
/**
 * @class  multimedia_link
 * @author NAVER (developers@xpressengine.com)
 * @brief The components connected to the body of multimedia data
 */
class multimedia_link extends EditorHandler
{

	// editor_sequence from the editor must attend mandatory wearing ....
	var $editor_sequence = 0;
	var $component_path = '';

	/**
	 * @brief editor_sequence and components out of the path
	 */
	function multimedia_link($editor_sequence, $component_path)
	{
		$this->editor_sequence = $editor_sequence;
		$this->component_path = $component_path;
	}

	/**
	 * @brief popup window to display in popup window request is to add content
	 */
	function getPopupContent()
	{
		// Pre-compiled source code to compile template return to
		$tpl_path = $this->component_path.'tpl';
		$tpl_file = 'popup.html';

		Context::set("tpl_path", $tpl_path);

		$oTemplate = &TemplateHandler::getInstance();
		return $oTemplate->compile($tpl_path, $tpl_file);
	}

	function transHTML($xml_obj)
	{
		//popup.html에서 편수 받아 오기
		$src = $xml_obj->attrs->multimedia_src;
		$start = $xml_obj->attrs->multimedia_start;
		$style = $xml_obj->attrs->style;

		/*//뭔가 이상해서 attrs에서 값을 참조하도록 수정하였음
		preg_match_all('/(width|height)([^[:digit:]]+)([0-9]+)/i',$style,$matches);
		$width = trim($matches[3][0]);
		$height = trim($matches[3][1]);*/
		
		$width = $xml_obj->attrs->width;
		$height = $xml_obj->attrs->height;
		
		//기본 높이와 너비를 지정해줌
		if(!$width) $width = 896; //890
		if(!$height) $height = 504; //530

		$auto_start = $xml_obj->attrs->auto_start;
		
		if($auto_start!="true")
		{
			$auto_play = 0;
			$auto_start = "false";
			//youtube 자동재생 옵션
			$auto_option = "autoplay=0";
		}
		else
		{
			$auto_play = 1;
			$auto_start = "true";
			$auto_option = "autoplay=1";
		}
			

		$wmode = $xml_obj->attrs->wmode;
		if($wmode == 'window') $wmode = 'window';
		else if($wmode == 'opaque') $wmode = 'opaque';
		else $wmode = 'transparent';


		$caption = $xml_obj->body;

		$src = str_replace(array('&','"'), array('&amp;','&qout;'), $src);
		$src = str_replace('&amp;amp;', '&amp;', $src);

		if(preg_match_all('/(youtube-nocookie\.com\/embed\/|youtube\.com\/watch\?v\=|youtube\.com\/v\/|youtu\.be\/|youtube\.com\/embed\/)(.*)(?:\/W)?/i',$src,$matches)) {
			if(strpos($src,"list=") !== false){
					$youtube_id = substr($matches[2][0], 0, 35);
					
					//$youtube_id = $youtube_id.'&amp;';
			} else {
					$youtube_id = substr($matches[2][0], 0, 11);
					//$youtube_id = $youtube_id.'?';
					
			}
				// youtube의 id
				$yt_id = $youtube_id;
				$yt_ids = Context::get('yt_ids');
				$yt_options = Context::get('yt_options');
				// <div>의 개별 코드로 활용됨 ex) <div id="plyaer0">, <div id="plyaer1">
				
				// 처음일 경우에는 0임
				if(empty($yt_ids)) {
					// 변수를 선언하고 첫번째 값 대입
					$yt_ids = array();
					$yt_ids[] = $yt_id;
					Context::set('yt_ids', $yt_ids);
					
					$yt_options[$yt_id] = array( 
						/* 기본 크기와 동영상 아이디
						 * Options (Private). 
						 *  
						 * holds options for helper 
						 */

						'width'     => $width, 
						'height'    => $height, 
						'video_id'  => $yt_id);

						/* 플레이어와 연관된 변수
						 * Player Vars (Private). 
						 *  
						 * holds parameters for embedded player 
						 * @see http://code.google.com/apis/youtube/player_parameters.html?playerVersion=HTML5 
						 */

						$yt_options[$yt_id]["playerVars"] = array (
						'autohide'  => 2, 
						'autoplay'  => $auto_play, 
						'controls'  => 1, 
						'enablejsapi'   => 0, 
						'loop'      => 0, 
						'origin'    => null, 
						'start'     => null, 
						'theme'     => 'dark');

					Context::set('yt_options', $yt_options);

				}else {
					// 변수값 추가하기
					$yt_ids[] = $yt_id;
					Context::set('yt_ids', $yt_ids);

					$yt_options[$yt_id] = array( 
						'width'     => $width, 
						'height'    => $height, 
						'video_id'  => $yt_id);
						
						$yt_options[$yt_id]["playerVars"] = array (
						'autohide'  => 2, 
						'autoplay'  => $auto_play, 
						'controls'  => 1, 
						'enablejsapi'   => 0, 
						'loop'      => 0, 
						'origin'    => null, 
						'start'     => null, 
						'theme'     => 'dark');
					Context::set('yt_options', $yt_options);
				}				

				//require( 'youtube_helper.php');
				//$youtubehelper = YoutubeHelper::loadClass('YoutubeHelper');
	
				$yh = new YoutubeHelper;
		
				$yt_html_code = $yh->iframePlayer('http://www.youtube.com/watch?v='.$yt_id.'&feature=feedrec',$yt_ids, $yt_options);

				return $yt_html_code.'<div id="youtube-player'.$yt_id.'"></div>';
			
		}


        /* daum.net */

		//최신형임
		elseif(preg_match('/daum.net\/?.*\/([0-9a-zA-Z]{23})(?:\W)?/i', $src, $match)) {
		//elseif(preg_match('~(?:daum\.net/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|daum\.net/)([^"&?/ ]{23})~i', $src, $match)) {
			$daum_id = $match[1];
			$daum_srl = "http://videofarm.daum.net/controller/player/VodPlayer.swf?vid=".$daum_id;
		
			return sprintf("<center><iframe width=\"%s\" height=\"%s\" src=\"%s&amp;play_loc=undefined&amp;autoPlay=%s&amp;profileName=HIGH&amp;showPreAD=false&amp;showPostAD=false\" frameborder=\"0\" scrolling=\"no\"></iframe></center>", $width, $height, $daum_srl,$auto_start);
		}

		// IMDB 최신형  imdb\.com\/?.*\/([0-9a-zA-Z]{10,12})(?:\/W)? 
		elseif(preg_match('/imdb\.com\/?.*(?:\/|-)([0-9a-zA-Z]{10,12})(?:\/W)?/i',$src,$matches)) {
			$imdb_srl = "http://www.imdb.com/video/imdb/".$matches[1];

			//854px 최대임 ㅡ.ㅡ
			return sprintf("<center><iframe width=\"%s\" height=\"%s\" src=\"%s/imdb/embed?autoplay=%s&amp;width=%s\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scolling=\"no\"></iframe></center>", $width, $height + 20, $imdb_srl,$auto_start, $width);
		}

		// Vimeo
		elseif(preg_match('/vimeo.com\/?.*\/(\d{8,11})(?:\W)?/', $src, $matches)){
			$vimeo_id = $matches[1];
			return sprintf("<div style=\"position:relative;padding-bottom:56.25&#37\"><iframe style=\"position:absolute; width:100&#37;; height:100&#37;\"  src=\"http://player.vimeo.com/video/%s?title=1&amp;byline=0&amp;portrait=0&amp;color=ff9933&amp;%s&amp;loop=1\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scolling=\"no\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>",$vimeo_id,$auto_option);
		}

		//Tudou 동영상
		elseif(preg_match('/tudou.com\/?.*\/([0-9a-zA-Z]{11})(?:\W)?/i', $src, $matches)){
			$tudou_id = $matches[1];
			return sprintf("<div class=\"embed-responsive embed-responsive-16by9\"><embed src=\"http://www.tudou.com/v/%s/\&resourceId=0_05_02_99\&autoPlay=%s/v.swf\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" wmode=\"opaque\" width=\"700\" height=\"450\"></embed></div>",$tudou_id,$auto_start);
		 }
			
		elseif(preg_match('/dailymotion\.com\/video\/(.*)/i',$src,$match)) {
			$dailymotion_id = reset(split('_', $match[1]));
			
			$dailymotion_srl ="//www.dailymotion.com/embed/video/".$dailymotion_id;
		
			return sprintf("<div style=\"position:relative;padding-bottom:56.25&#37\"><iframe style=\"position:absolute; width:100&#37;; height:100&#37;\"  src=\"%s?forcedQuality=auto&amp;autoplay=%s&amp;log\" frameborder=\"0\" scrolling=\"no\"></iframe></div>", $dailymotion_srl,$auto_start);
		}


		elseif(Context::getResponseMethod() != "XMLRPC"){
			return sprintf("<script type=\"text/javascript\">displayMultimedia(\"%s\", \"%s\",\"%s\", { \"autostart\" : %s, \"wmode\" : \"%s\" });</script>", $src, $width + 6 , $height + 8 , $auto_start, $wmode);
		}

		else return sprintf("<div style=\"width: %dpx; height: %dpx;\"><span style=\"position:relative; top:%dpx;left:%d\"><img src=\"%s\" /><br />Attached Multimedia</span></div>", $width, $height, ($height/2-16), ($width/2-31), Context::getRequestUri().'./modules/editor/components/multimedia_link/tpl/multimedia_link_component.gif');
	}
}

/* End of file multimedia_link.class.php */
/* Location: ./modules/editor/components/multimedia_link/multimedia_link.class.php */





/** 
* Author: Tomas Pavlatka [tomas.pavlatka@gmail.com] 
* Created: Sep 8, 2011 
*/ 
class YoutubeHelper { 

    /* 
     * Options (Private). 
     *  
     * holds options for helper 
     */ 
    var $_options = array( 
        'width'     => 640, 
        'height'    => 390, 
        'video_id'  => null); 

    /* 
     * Player Vars (Private). 
     *  
     * holds parameters for embedded player 
     * @see http://code.google.com/apis/youtube/player_parameters.html?playerVersion=HTML5 
     */ 
     var $_playerVars = array( 
         'autohide'  => 2, 
         'autoplay'  => 0, 
         'controls'  => 1, 
         'enablejsapi'   => 0, 
         'loop'      => 0, 
         'origin'    => null, 
         'start'     => null, 
         'theme'     => 'dark'); 

     /* 
      * iFrame Code. 
      *  
      * holds code for iFrame Player 
      */ 
     var $_frameCode = null; 

     /** 
      * Init. 
      *  
      * inits helper 
      * @param array $options - option for helper 
      * @param array $playerVars - parameters for embedded player 
      */ 
     function init(array $options = array(),array $playerVars = array()) { 
          $this->_options = am($this->_options,$options); 
          $this->_playerVars = am($this->_playerVars,$playerVars); 
     } 

    /** 
     * iFrame Player. 
     *  
     * creates script for iframe player and returns it back 
     * @param string url - url of youtube video 
     * @param string divId - id of div element 
     */ 
    function iframePlayer($url,$divId, $options) { 
        // Get video id. 
        $this->_parseVideoId($url); 

        // Validation. 
        if(empty($this->_options['video_id'])) { 
            $this->_iframeCode = __('Video id cannot be left blank. Check url of youtube video.',true); 
        } else if(!is_numeric($this->_options['width']) || $this->_options['width'] < 1) { 
            $this->_iframeCode = __('Width of video player must be numeric and greather than 1.',true); 
        } else if(!is_numeric($this->_options['height']) || $this->_options['height'] < 1) { 
            $this->_iframeCode = __('Height of video player must be numeric and greather than 1.',true); 
        } else { 
            // Build code. 
            $this->_loadIframePlayer(); 
            $this->_createIframePlayer($divId, $options); 
            $this->_closeIframePlayer(); 
        } 

        // Return code. 
        return $this->_iframeCode; 
    } 

    /** 
     * Close iFrame Player (Private) 
     *  
     * closes iframe player. 
     */ 
    function _closeIframePlayer() { 
        $this->_iframeCode  .= '</script>'; 
    } 

    /** 
     * Create iFrame Player. 
     *  
     * creates iframe player. 
     * @param string divId - id of div element 
     */ 
    function _createIframePlayer($divIds, $options) { 

        /*// Build player params. 
        $params = null; 
        foreach($this->_playerVars as $key => $value) { 
            if(is_numeric($value) || !empty($value)) { 
                 $params .= "'{$key}': "; 
  
                 if(is_numeric($value)) { 
                  $params .= $value; 
                 } else { 
                      $params .= "'{$value}'"; 
                 } 

                 $params .= ','; 
            }     
        } */

        // Build JS code. 
        $this->_iframeCode .= 'var player;'."\r\n"; 
        $this->_iframeCode .= 'function onYouTubePlayerAPIReady() {'."\r\n"; 
		
		foreach($divIds as $divId) {
			$this->_iframeCode .= 'player = new YT.Player("youtube-player'.$divId.'", {'."\r\n"; 
			//$this->_iframeCode .= 'height: "'.(int)$this->_options['height'].'",'."\r\n"; 
			$this->_iframeCode .= 'height: "'.(int)$options[$divId]['height'].'",'."\r\n"; 
			//$this->_iframeCode .= 'width:  "'.(int)$this->_options['width'].'",'."\r\n"; 
			$this->_iframeCode .= 'width:  "'.(int)$options[$divId]['width'].'",'."\r\n"; 
			//$this->_iframeCode .= 'videoId: "'.$this->_options['video_id'].'",'."\r\n"; 
			$this->_iframeCode .= 'videoId: "'.$divId.'",'."\r\n"; 


			/*if(!empty($params)) { 
				$this->_iframeCode .= 'playerVars: {'.substr($params,0,-1).'},'."\r\n"; 
			}*/


			// Build player params. 
			$options_params = null; 
			foreach($options[$divId]["playerVars"] as $key => $value) { 
				if(is_numeric($value) || !empty($value)) { 
					 $options_params .= "'{$key}': "; 
	  
					 if(is_numeric($value)) { 
					  $options_params .= $value; 
					 } else { 
						  $options_params .= "'{$value}'"; 
					 } 
	
					 $options_params .= ','; 
				}     
			}


			if(!empty($options_params)) { 
				$this->_iframeCode .= 'playerVars: {'.substr($options_params,0,-1).'},'."\r\n"; 
			}
		
	        $this->_iframeCode .= '});'."\r\n"; 
		} 
        $this->_iframeCode .= '}'."\r\n\r\n";     
    } 

    /** 
     * Load iFrame Player (Private). 
     *  
     * starts building iframe player code. 
     */ 
    function _loadIframePlayer() { 
        $this->_iframeCode  = '<script type="text/javascript">'."\r\n"; 
        $this->_iframeCode .= 'var tag = document.createElement("script");'."\r\n"; 
        $this->_iframeCode .= 'tag.src = "http://www.youtube.com/player_api"'."\r\n"; 
        $this->_iframeCode .= 'var firstScriptTag = document.getElementsByTagName("script")[0]'."\r\n"; 
        $this->_iframeCode .= 'firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)'."\r\n\r\n"; 
    } 

    /** 
     * Parse Video Id (Private). 
     *  
     * parses video id from url 
     * @param string $url - url from youtube 
     */ 
    function _parseVideoId($url) { 
        //http://www.youtube.com/watch?v=UF6wdrRAZug&feature=relmfu     

        $urlQuery = parse_url($url,PHP_URL_QUERY); 
        if(!empty($urlQuery)) { 
            $parseArray = explode('&',$urlQuery); 
            foreach($parseArray as $key => $value) { 
                $explodeArray = explode('=',$value); 
                if($explodeArray[0] == 'v' && isset($explodeArray[1])) { 
                    $this->_options['video_id'] = (string)$explodeArray[1]; 
                    break; 
                } 
            } 
        } 
    }
}
