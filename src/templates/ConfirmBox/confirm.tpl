<table width="80%">
    <tr>
        <td align="left">{$msg}</td>
    </tr>
    <tr>
        <td align="center">
            <input class="button1" type="button" value="{$this->GetLangData('yes')}" onclick="location.href='{$url}'" />
            <input class="button1" type="button" value="{$this->GetLangData('no')}" onclick="history.back(1)" />
        </td>
    </tr>
</table>