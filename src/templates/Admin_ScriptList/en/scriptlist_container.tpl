<script language="javascript">
//<![CDATA[
function deleteScript(scriptname){
        if(confirm("Are you sure to delete the script? You can alternatively disable it by unticking it.")){
                window.location="admin_script.php?function=delete&name="+scriptname;
    }
}
function editScript(scriptname){
    window.location="admin_script.php?function=edit&name="+scriptname;
    }
function confirmAdding(form){
    if(confirm("Are you sure to add a script file?")) form.submit();
    }
//]]>
</script>
<table>
    <tr><th align="left">Add a Script</th></tr>
    <tr><td>
            <form action="admin_script.php?function=add" method="post">
                script name <input type="text" name="name" class="textinput2" /> <input type="button" value="add" class="button1" onclick="confirmAdding(this.form)" />
            </form>
        </td>
    </tr>
    <tr><th align="left">Enable/Disable Scripts</th></tr>
    <tr><td align="center">
            <form action="admin_script.php?function=set" method="post">
                <div><table>{$ListItems}</table></div>
                <div>
                    <input type="submit" value="save" class="button1" />
                    <input type="button" value="back" class="button1" onclick="window.location='admin_setting.php'" />
                </div>
            </form>
        </td>
    </tr>
</table>
