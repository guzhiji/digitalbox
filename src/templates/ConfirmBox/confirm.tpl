<table width="80%">
    <tr>
        <td align="left">{$msg}</td>
    </tr>
    <tr>
        <td align="center">
            {$this->CreateButton('link', $this->GetLangData('yes'), array(
                'url' => $yes,
                'class' => 'db3_button1'
            )
            )}
            {$this->CreateButton('link', $this->GetLangData('no'), array(
                'url' => $no,
                'class' => 'db3_button1'
            )
            )}
        </td>
    </tr>
</table>