<script language="javascript">
//<![CDATA[
function changeType(value){
document.getElementById('addr_input').style.display=(value==0?'':'none');
    }
//]]>
</script>
<form action="admin_channel.php?function={$Content_Function}" method="post" name="channel_form">
    <input type="hidden" value="{$Content_ChannelID}" name="id" />
    <table>
        <tbody>
            <tr>
                <td>频道名称：</td>
                <td><input type="text" name="channel_name" class="textinput1"
                           value="{$Content_ChannelName}" /></td>
            </tr>
            <tr>
                <td>频道类型：</td>
                <td>{$Content_Types}</td>
            </tr>
            <tr{$Content_AddrHidden} id="addr_input">
                <td>频道地址：</td>
                <td><input type="text" value="{$Content_ChannelURL}"
                           name="channel_add" class="textinput1" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <table border="0" cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <td><input type="submit" value="确定" class="button1" /></td>
                                <td><input type="button"
                                           onclick="window.location='admin_channel.php'"
                                           value="返回" class="button1" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>
