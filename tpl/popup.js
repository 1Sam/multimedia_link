/**
 * popup으로 열렸을 경우 부모창의 위지윅에디터에 select된 멀티미디어 컴포넌트 코드를 체크하여
 * 있으면 가져와서 원하는 곳에 삽입
 **/
jQuery(function($){

	(function(){

		if(!is_def(opener)) return;
	
		var $node = $(opener.editorPrevNode).filter('img'), attrs;
		if(!$node.length) return;
	
	// 변수의 기본값
		attrs = {
			url     : $node.attr('multimedia_src') || null,
			caption : $node.attr('alt') || null,
			width   : $node.width() || '896',
			height  : $node.height() || '504',
			wmode   : $node.attr('wmode') || null
		};
	
		$.each(attrs, function (key, val) {
			get_by_id('multimedia_'+key).value = val;
		});
	
		get_by_id('auto_start').checked = ($node.attr('auto_start') == 'true');
		get_by_id('multimedia_responsive').checked = ($node.attr('multimedia_responsive') == 'true');
	
	// 1/4. 변수 추가
	// 모두 4항목에 알맞은 형태로 추가해야합니다.
	// multimedia_link.class.php로 넘어갈 때, 
	// $xml_obj->attrs->변수
	// 형태로 넘겨집니다.
	// 변수의 저장된 값을 팝업창에 표시합니다.
		get_by_id('yt_loop').checked = ($node.attr('yt_loop') || '0');
		get_by_id('volume').value = ($node.attr('volume') || '70');



	});

	$('.btnArea button').click(function(){
		if(!is_def(opener)) return;
	
		var el_wmode = get_by_id('fo').elements['multimedia_wmode'];
		var attrs = {
			alt    : encodeURIComponent(get_by_id('multimedia_caption').value),
			width  : get_by_id('multimedia_width').value || 896,
			height : get_by_id('multimedia_height').value || 504,
			wmode  : el_wmode.value || el_wmode.options[0].value,
			auto_start : get_by_id('auto_start').checked?'true':'false',
			multimedia_src : get_by_id('multimedia_url').value.replace(request_uri, ''),
			multimedia_responsive : get_by_id('multimedia_responsive').checked?'true':'false',
	
	// 2/4. 변수 추가
	// 변수의 값을 정렬합니다..
			yt_loop : get_by_id('yt_loop').checked?'1':'0',
			volume : get_by_id('volume').value || 70
		};
	
		if(!attrs['multimedia_src']) {
		  window.close();
		  return;
		}
	
		var $selected_node = $(opener.editorPrevNode);
	
		if($selected_node.is('img') && $selected_node.attr('editor_component') == 'multimedia_link'){
			$selected_node
				.attr('multimedia_src', attrs.multimedia_src)
				.attr('width', attrs.width)
				.attr('height', attrs.height)
				.attr('wmode', attrs.wmode)
				.attr('auto_start', attrs.auto_start)
				.attr('alt', attrs.alt)
				.attr('multimedia_responsive', attrs.multimedia_responsive)
	// 3/4. 변수 추가
	// 2/4에서 정렬된 실제 저장값을 변수에 담습니다.
				.attr('yt_loop', attrs.yt_loop)
				.attr('volume', attrs.volume)
	
				.css('width', attrs.width + 'px')
				.css('height', attrs.height + 'px')
		} else {
	// 4/4. 변수 추가 마지막 과정
	// <img 태그 안에 
	// 변수=""
	// 형태로 꼭 추가합니다. 따옴포 안에 값은 넣지 않습니다.
			var html = '<img src="../../../../common/img/blank.gif" editor_component="multimedia_link" multimedia_src="" width="" height="" wmode="" style="display:block;width:'+attrs.width+'px;height:'+attrs.height+'px;border:2px dotted #4371B9;background:url(./modules/editor/components/multimedia_link/tpl/multimedia_link_component.gif) no-repeat center" auto_start="" alt="" multimedia_responsive="" yt_loop="" volume="" />';
	
			html = html.replace(/(\w+)=""/g, function(m0,m1) {
				return attrs[m1] ? (m1+'="'+attrs[m1]+'"') : '';
			});
	
			opener.editorFocus(opener.editorPrevSrl);
			var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl)
			opener.editorReplaceHTML(iframe_obj, html);
		}
		opener.editorFocus(opener.editorPrevSrl);
		window.close();
	});

});
