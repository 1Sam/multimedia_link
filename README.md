# multimedia_link
[컴포넌트] 에디터에 사용되는 멀티미디어 링크 컴포넌트

동영상 링크에 최적화 되어 있습니다.




<pre>
https://developers.google.com/youtube/youtube_player_demo?hl=ko
/*** ABC 순서 (기본값 [옵션]) ***
★ autohide         > 컨트롤바를 자동으로 숨길지 여부 (2 [0, 1])
|               ├ 0: 프로그레스바 및 컨트롤바가 재생중 & 전체화면에서도 표시됨
|               ├ 1: 재생 몇 초 후 프로그레스바 및 컨트롤바 사라짐. 마우스 hover, 키보드 누를 경우출력
|               └ 2: 컨트롤바가 계속 표시되는 반면 프로그레스바는 점차 사라짐

★ autoplay         > 자동재생 여부 (0 [1])

★ cc_load_policy   > 자막 강제 표시 여부 (사용자 환경설정 따름 [1])

★ color            > 프로그레스바 색상 (red [white])
|               └* white로 할 경우 modestbranding 옵션 사용 불가

★ controls         > 컨트롤바 표시 여부 (1 [0, 2])
|               ├ 0: 표시 해제
|               ├ 1: 표시
|               └ 2: 재생 시작 후 컨트롤바 표시 (iframe의 경우만)

★ disablekb         > 키보드 컨트롤 적용 여부 (0 [1])

★ enablejsapi      > JavaScript API 사용 여부 (0 [1])
|               └* seekTo 등 페이지에서 버튼 컨트롤을 위해 필요

★ end            > 재생 중지 시간을 시작 부분(start 지정 시간이 아닌)부터 초 단위로 측정하여 지정 (양의 정수)

★ fs            > 전체화면 버튼이 표시 여부 (1 [0])

★ hl            > 플레이어 인터페이스 언어 설정 (두 문자 언어코드 [ex: ko])
               └* http://www.loc.gov/standards/iso639-2/php/code_list.php

★ iv_load_policy   > 특수효과 표시 여부 (1 [3])

★ list            > listType 과 함께, 로드될 콘텐츠 식별
               ├ listType 값, playlist 일 경우-> YouTube 재생목록 ID 지정 (*ID앞에 "PL" 필수)
               ├ listType 값, search 일 경우-> 검색어 지정
               ├ listType 값, user_uploads 일 경우-> YouTube 채널 식별
               └* list 와 listType 값 지정할 경우, IFrame 삽입 URL에서 동영상 ID 지정 불필요

★ listType         > list 와 함께, 로드할 콘텐츠를 식별 ([playlist, search, user_uploads])

★ loop            > 반복 재생 (0 [1])
               ├* 현재 playlist 와 함께 사용하는 경우에만 AS3 플레이어에서 작동
               └* http://www.youtube.com/v/영상ID?version=3&loop=1&playlist=영상ID

★ modestbranding   > 컨트롤바에 YouTube 로고 표시 여부 (0 [1])
|               └* color 값을 white로 할 경우 옵션 적용 안됨

★ origin         > 추가 보안 수단 제공, IFrame 삽입에서만 지원 (도메인 URL)
               └* enablejsapi 를 1로 설정할 경우, 도메인(URL)을 항상 origin 값으로 지정해야 함

★ playerapiid      > JavaScript API와 함께 사용 (모든 영,숫자 문자열)
               └* https://developers.google.com/youtube/js_api_reference?hl=ko

★ playlist         > (동영상 ID를 쉼표(,)로 구분한 목록)
               └* URL 에서 지정한 VIDEO_ID 먼저 재생 후, playlist 지정 동영상 재생

★ playsinline      > iOS HTML5 플레이어, 인라인 or 전체화면 재생 여부 제어 (0 [1])
               ├ 0: 전체화면 재생. 현재 기본값이지만 변경될 수 있음
               └ 1: TRUE로 설정된 allowsInlineMediaPlayback 속성과 함께 만들어진 UIWebViews이 인라인으로 재생됨

★ rel            > 재생 종료 시점에, 관련 동영상 표시할지 여부 (1 [0])

★ showinfo         > 동영상 제목 및 업로더 같은 정보 표시 여부 (1 [0])

★ start            > 특정 시간(단위: 초) 지점부터, 동영상 재생 (양의 정수)
               └* seekTo 와 비슷하게, 지정한 시간과 가장 가까운 키프레임 찾는 점 유의. 즉, 요청한 시간 바로 앞 부분을 찾을 수도 있으며 일반적으로 2초 이내

★ theme            > 어두운 컨트롤바 or 밝은 컨트롤바 표시 제어 (dark [light])

= 공식 API 매개변수에 소개되지 않은 변수들 =
@ html5            > html5 지원 영상일 경우 강제 활성화 여부 (브라우져?, 사용자설정?, 따름?[0, 1])
               └* 파이어폭스는 기본 html5 라서 켜고 끄는게 자유롭지 못함

= 공식 API 매개변수에 없는 확인되지 않은 변수들 =
[X] cc_lang_pref   > 자막 언어 api (두 문자 언어코드 [ex: ko])
               └* 기본 iframe 플레이어에서 작동한 변수
[X] vq            > 화질
               └* 기본 iframe 플레이어에서 작동한 변수
[X] showsearch
* * * * * *
</pre>
