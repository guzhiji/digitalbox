<tr>
    <td>
        <input type="checkbox" name="scripts[]" value="{$ScriptName}"{$Checked} /> 
        {$ScriptName} 
    </td>
    <td>
        <input type="button" value="edit" onclick="editScript('{$ScriptName}')" />
        <input type="button" value="delete" onclick="deleteScript('{$ScriptName}')" />
    </td>
</tr>
