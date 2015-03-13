<form name="edit_media" method="post"
      action="admin_content.php?module=content&class={$Content_Class}&type=media&function={$Content_Function}">
    <div style="text-align: left; padding-top: 5px;">Class: {$Content_ClassName}<input
            type="hidden" name="parent_class" value="{$Content_Class}" /></div>
    <div style="text-align: left; padding-top: 5px;">Name: <input
            class="textinput5" type="text" name="media_name"
            value="{$Content_Name}" /><input type="hidden" name="id"
            value="{$Content_ID}" /></div>
    <div style="text-align: left; padding-top: 5px;">URL: <input
            class="textinput4" type="text" name="media_add" value="{$Content_Addr}" />
        <input type="button" value="文件" class="button1"
               onclick="window.open('admin_editor_uploadlist.php','choose file','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=600, height=500')" />
        <select id="playerselector" class="select1" onchange="changePlayer()">
            <option value="auto" selected="selected">auto</option>
            <option value="wp">Windows Media Player</option>
            <option value="rp">Real Player</option>
            <option value="fp">Flash Player</option>
            <option value="if">iframe</option>
        </select></div>
    <div style="text-align: left; padding-top: 5px;"><input type="hidden"
                                                            name="media_HTML" value="yes" /><textarea class="textarea1"
        name="media_text">{$Content_Text}</textarea><script
        type="text/javascript" src="ckeditor/ckeditor.js"></script><script
            type="text/javascript">
		//<![CDATA[
			function setAddr(addr){
				document.edit_media.media_add.value=addr;
				changePlayer();
            }
			function getPlayerFromAddr(){
				var tbaddr=document.edit_media.media_add;
				var p=tbaddr.value.substring(0,3);
				switch(p){
					case "wp:":
					case "rp:":
					case "fp:":
					case "if:":
						return p;
					default:
						return "";
            }
			}
			function getSelectedPlayer(){
				var p=document.getElementById("playerselector");
				for(var i=0;i<p.options.length;i++){
					var o=p.options[i];
					if(o.selected){
						if(o.value=="auto") return "";
						return o.value + ":";
            }
				}
				return "";
			}
			function changePlayer(){
				var tbaddr=document.edit_media.media_add;
				var p=getPlayerFromAddr();
				if(p!="")
					p=tbaddr.value.substring(3);
				else
					p=tbaddr.value;
				tbaddr.value=getSelectedPlayer()+p;
            }
			function setPlayer(){
				var tbaddr=document.edit_media.media_add;
				var p=document.getElementById("playerselector");
				var pa=getPlayerFromAddr();
				if(pa==""){
					p.options[0].selected=true;
            }else{
					pa=pa.substring(0,2);
					for(var i=1;i<p.options.length;i++){
						var o=p.options[i];
						if(o.value==pa){
							o.selected=true;
							break;
            }
					}
				}
			}
			setPlayer();
			CKEDITOR.replace("media_text");
		//]]>
        </script></div>
    <div style="text-align: center; padding-top: 5px;"><input type="submit"
                                                              class="button1" value="save" /> <input type="button" class="button1"
                                                              value="back"
                                                              onclick="window.location='admin_content.php?module=content&class={$Content_Class}'" /></div>
</form>
