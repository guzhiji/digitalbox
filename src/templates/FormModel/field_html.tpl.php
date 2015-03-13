<div style="text-align: left; padding-top: 5px;">
    <textarea name="article_text"
    id="article_text" class="textarea1"><?php echo $value; ?></textarea><script
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