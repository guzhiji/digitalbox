<form action="{$Code_URL}" method="post">
    <table>
        <tbody>
            <tr>
                <td align="center"><textarea name="code" class="textarea1">{$Code_Content}</textarea></td>
            </tr>
            <tr>
                <td align="center">
                    <table cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <td><input type="submit"
                                           value="保存" class="button1" /></td>
                                <td><input type="button" onclick="{$Code_Back}"
                                           value="返回" class="button1" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>
