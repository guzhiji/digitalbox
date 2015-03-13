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
        {$this->CreateButton('submit', $this->GetLangData('save'), array(
            'class' => 'db3_button1'
        ))}
        {$this->CreateButton('button', $this->GetLangData('cancel'), array(
            'url' => '?module=user',
            'class' => 'db3_button1'
        ))}
    </div>
</form>
