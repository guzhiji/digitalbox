<form name="edit_article" method="post"
      action="?module={$this->module}&function=save">
    <div style="text-align: left; padding-top: 5px;">
        所属栏目：{$text_parent}
        <input
            type="hidden" name="parent_catalog" value="{$int_parent}" />
    </div>
    <div style="text-align: left; padding-top: 5px;">
        文章名称：
        <input type="text"
               name="article_name" class="textinput5" value="{$text_title}" />
        <input
            type="hidden" name="id" value="{$int_id}" />
    </div>
    <div style="text-align: left; padding-top: 5px;">
        文章作者：
        <input type="text"
               name="article_author" class="textinput5" value="{$text_author}" />
    </div>
    <div style="text-align: left; padding-top: 5px;">
        <textarea name="article_text"
        id="article_text" class="textarea1">{$content}</textarea><script
        type="text/javascript" src="ckeditor/ckeditor.js"></script><script
            type="text/javascript">
                //<![CDATA[
                CKEDITOR.replace("article_text", {
                filebrowserBrowseUrl : 'admin_editor_uploadlist.php?editor=ckeditor',
                filebrowserUploadUrl : 'admin_editor_uploader.php'
            });
            //]]>
        </script>
    </div>
    <div style="text-align: center; padding-top: 5px;">
        <input type="submit"
               class="button1" value="保存" /> 
        <input type="button" class="button1"
               value="返回"
               onclick="window.location='?module=catalog&id={$int_parent}'" />
    </div>
</form>