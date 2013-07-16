<form name="admin_user" method="post" action="?module=user&function=login">
    <div style="text-align: center;">
        {$this->GetLangData('name')}
        <input type="text" name="uid" />
    </div>
    <div style="text-align: center;">
        {$this->GetLangData('password')}
        <input type="password" name="pwd" />
    </div>
    <div style="text-align: center;">
        {$this->CreateButton('submit', $this->GetLangData('login'), array(
            'class' => 'db3_button1'
        ))}
        {$this->CreateButton('button', $this->GetLangData('cancel'), array(
            'url' => '?module=',
            'class' => 'db3_button1'
        ))}
    </div>
</form>
