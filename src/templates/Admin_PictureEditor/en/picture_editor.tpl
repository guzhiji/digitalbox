<form name="edit_picture" method="post"
	action="admin_content.php?module=content&class={$Content_Class}&type=picture&function={$Content_Function}">
<div style="text-align: left; padding-top: 5px;">Class: {$Content_ClassName}<input
	type="hidden" name="parent_class" value="{$Content_Class}" /></div>
<div style="text-align: left; padding-top: 5px;">Name: <input type="text"
	class="textinput5" name="picture_name" value="{$Content_Name}" /><input
	type="hidden" name="id" value="{$Content_ID}" /></div>
<div style="text-align: left; padding-top: 5px;">URL: <input type="text"
	class="textinput4" name="picture_add" value="{$Content_Addr}" /> <input
	type="button" value="文件" class="button1"
	onclick="window.open('admin_editor_uploadlist.php','choose file','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=600, height=500')" /></div>
<div style="text-align: left; padding-top: 5px;"><input type="hidden"
	name="picture_HTML" value="yes" /><textarea name="picture_text"
	class="textarea1">{$Content_Text}</textarea><script
	type="text/javascript" src="ckeditor/ckeditor.js"></script><script
	type="text/javascript">
		//<![CDATA[
			function setAddr(addr){document.edit_picture.picture_add.value=addr;}
			CKEDITOR.replace("picture_text");
		//]]>
		</script></div>
<div style="text-align: center; padding-top: 5px;"><input type="submit"
	class="button1" value="save" /> <input type="button" class="button1"
	value="back"
	onclick="window.location='admin_content.php?module=content&class={$Content_Class}'" /></div>
</form>
