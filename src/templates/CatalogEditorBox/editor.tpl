<form name="admin_catalog" method="post" action="{$url}">
    <div style="text-align: center;">
        {$this->GetLangData('name')}
        <input type="text" name="name" value="{$text_value}" />
        <input type="hidden" name="parent" value="{$int_parent}" />
    </div>
    <div style="text-align: center;">
        <input type="submit" class="button1" value="{$this->GetLangData('save')}" />
        <input type="button" class="button1" value="{$this->GetLangData('cancel')}" onclick="window.location.href='?module=catalog&id={$int_parent}'" />
    </div>
</form>
