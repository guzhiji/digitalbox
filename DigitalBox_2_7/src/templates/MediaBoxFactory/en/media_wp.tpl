<!-- media player ("wmv" "wma" "mpg" "asf" "mp3" "mpeg" "avi") -->
<object
	codeBase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"
	standby="Loading" type="application/x-oleobject"
	classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6"
	width="{$Width}" height="{$Height}">
	<param NAME="URL" value="{$Address}" />
	<param name="AutoSize" value="0" />
	<param name="AutoStart" value="-1" />
	<param name="Volume" value="70" />
	<param name="WindowlessVideo" value="0" />
</object>
