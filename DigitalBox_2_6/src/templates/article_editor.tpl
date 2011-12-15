<form name="edit_article" method="post"
      action="admin_content.php?module=content&class={$Content_Class}&type=article&function={$Content_Function}">
    <div style="text-align: left; padding-top: 5px;">所属栏目：{$Content_ClassName}<input
            type="hidden" name="parent_class" value="{$Content_Class}" /></div>
    <div style="text-align: left; padding-top: 5px;">文章名称：<input type="text"
                                                                 name="article_name" class="textinput5" value="{$Content_Name}" /><input
                                                                 type="hidden" name="id" value="{$Content_ID}" /></div>
    <div style="text-align: left; padding-top: 5px;">文章作者：<input type="text"
                                                                 name="article_author" class="textinput5" value="{$Content_Author}" /></div>
    <div style="text-align: left; padding-top: 5px;"><input type="hidden"
                                                            name="article_HTML" value="yes" /><textarea name="article_text"
        id="article_text" class="textarea1">{$Content_Text}</textarea><script
        type="text/javascript" src="ckeditor/ckeditor.js"></script><script
            type="text/javascript">
		//<![CDATA[
			CKEDITOR.replace("article_text",
            {
		        filebrowserBrowseUrl : 'admin_editor_uploadlist.php?editor=ckeditor',
		        filebrowserUploadUrl : 'admin_editor_uploader.php'
            });
		//]]>
        </script></div>
    <div style="text-align: center; padding-top: 5px;"><input type="submit"
                                                              class="button1" value="保存" /> <input type="button" class="button1"
                                                              value="返回"
                                                              onclick="window.location='admin_content.php?module=content&class={$Content_Class}'" /></div>
</form>
