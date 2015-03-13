<form name="admin_catalog" method="post" action="{$url}">
    <div style="text-align: center;">
        {$this->GetLangData('name')}
        <input type="text" name="name" value="{$text_value}" />
        <input type="hidden" name="parent" value="{$int_parent}" />
    </div>
    <div style="text-align: center; padding-top: 5px;">
        {$this->CreateButton('submit', $this->GetLangData('save'), array(
            'class' => 'db3_button1'
        )
        )}
        {$this->CreateButton('button', $this->GetLangData('cancel'), array(
            'url' => '?module=catalog&id=' . $int_parent,
            'class' => 'db3_button1'
        )
        )}
    </div>
</form>
