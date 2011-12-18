<tr>
    <td>
        <input type="checkbox" name="scripts[]" value="{$ListView_ScriptName}"{$ListView_Checked} /> 
        {$ListView_ScriptName} 
    </td>
    <td>
        <input type="button" value="编辑" onclick="editScript('{$ListView_ScriptName}')" />
        <input type="button" value="删除" onclick="deleteScript('{$ListView_ScriptName}')" />
    </td>
</tr>