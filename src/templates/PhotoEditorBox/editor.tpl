<form name="edit_photo" method="post"
      action="?module={$this->module}&function=save">
    <div style="text-align: left; padding-top: 5px;">
        所属栏目：{$text_parent}
        <input
            type="hidden" name="parent_catalog" value="{$int_parent}" />
    </div>
    <div style="text-align: left; padding-top: 5px;">
        图片名称：
        <input type="text"
               name="photo_name" class="textinput5" value="{$text_title}" />
        <input
            type="hidden" name="id" value="{$int_id}" />
    </div>
    <div style="text-align: left; padding-top: 5px;">
        图片作者：
        <input type="text"
               name="photo_author" class="textinput5" value="{$text_author}" />
    </div>
    <div style="text-align: left; padding-top: 5px;">
        图片地址：
        <input type="text"
               name="photo_filename" class="textinput5" value="{$text_filename}" />
    </div>
    <div style="text-align: left; padding-top: 5px;">
        <textarea name="photo_text"
        id="article_text" class="textarea1">{$description}</textarea><script
        type="text/javascript" src="ckeditor/ckeditor.js"></script><script
            type="text/javascript">
                //<![CDATA[
                CKEDITOR.replace("photo_text", {
                filebrowserBrowseUrl : 'admin_editor_uploadlist.php?editor=ckeditor',
                filebrowserUploadUrl : 'admin_editor_uploader.php'
            });
            //]]>
        </script>
    </div>
    <div style="text-align: center; padding-top: 5px;">
        {$this->CreateButton('submit', $this->GetLangData('save'), array(
            'class' => 'db3_button1'
        )
        )}
        {$this->CreateButton('button', $this->GetLangData('back'), array(
            'url' => DB3_URL('admin','catalog', '', array('id' => $int_parent)),
            'class' => 'db3_button1'
        )
        )}
    </div>
</form>