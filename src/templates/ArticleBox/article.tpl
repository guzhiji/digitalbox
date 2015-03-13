
<div style="text-align: left; padding-top: 5px;">
    所属栏目：{$this->CreateButton('link', $text_parent, array(
            'url' => '?module=catalog&id=' . $int_parent
        )
        )}
</div>
<div style="text-align: left; padding-top: 5px;">
    文章名称：{$text_title}
    <input
        type="hidden" name="id" value="{$int_id}" />
</div>
<div style="text-align: left; padding-top: 5px;">
    文章作者：{$text_author}
</div>
<div style="text-align: left; padding-top: 5px;">
    {$content}
</div>
<div style="text-align: center; padding-top: 5px;">
    {$this->CreateButton('link', $this->GetLangData('back'), array(
            'url' => '?module=catalog&id=' . $int_parent,
            'class' => 'db3_button1'
        )
        )}
    {$this->CreateButton('link', 'top', array(
            'js' => 'window.scrollTo(0, 0)',
            'class' => 'db3_button1'
        )
        )}
</div>