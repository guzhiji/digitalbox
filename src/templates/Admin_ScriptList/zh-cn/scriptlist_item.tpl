<tr>
    <td>
        <input type="checkbox" name="scripts[]" value="{$ScriptName}"{$Checked} /> 
        {$ScriptName} 
    </td>
    <td>
        <input type="button" value="编辑" onclick="editScript('{$ScriptName}')" />
        <input type="button" value="删除" onclick="deleteScript('{$ScriptName}')" />
    </td>
</tr>