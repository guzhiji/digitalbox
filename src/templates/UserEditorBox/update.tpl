<form name="admin_user" method="post" action="{$url}">
    <div style="text-align: center;">
        {$this->GetLangData('name')}
        <input type="hidden" name="uid" value="{$text_name}" />
        {$text_name}
    </div>
    <div style="text-align: center;">
        {$this->GetLangData('password')}
        <input type="password" name="pwd" />
    </div>
    <div style="text-align: center;">
        {$this->GetLangData('pwdnew')}
        <input type="password" name="newpwd" />
    </div>
    <div style="text-align: center;">
        {$this->GetLangData('pwdrepeat')}
        <input type="password" name="repeat" />
    </div>
    <div style="text-align: center;">
        <input type="submit" class="button1" value="{$this->GetLangData('save')}" />
        <input type="button" class="button1" value="{$this->GetLangData('cancel')}" onclick="window.location.href='?module=user'" />
    </div>
</form>
