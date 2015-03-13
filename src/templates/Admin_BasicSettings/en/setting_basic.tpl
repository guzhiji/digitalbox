<form action="admin_setting.php?module=basic&function=save" name="SForm0" method="post">
    <table>
        <tbody>
            <tr>
                <td>Website name: </td>
                <td><input type="text" value="{$Setting_SiteName}"
                           class="textinput1" name="site_name"></td>
            </tr>
            <tr>
                <td>Keywords: </td>
                <td><input type="text" value="{$Setting_SiteKeywords}"
                           class="textinput1" name="site_keywords"></td>
            </tr>
            <tr>
                <td>Master's mail: </td>
                <td><input type="text" value="{$Setting_MasterMail}"
                           class="textinput1" name="master_mail"></td>
            </tr>
            <tr>
                <td>Copyright statement: </td>
                <td><input type="text" value="{$Setting_SiteStatement}" class="textinput1"
                           name="site_statement"></td>
            </tr>
            <tr>
                <td>Icon URL: </td>
                <td><input type="text" class="textinput1" name="icon_URL" value="{$Setting_IconURL}"></td>
            </tr>
            <tr>
                <td>Logo URL:</td>
                <td><input type="text" class="textinput1" name="logo_URL" value="{$Setting_LogoURL}"></td>
            </tr>
            <tr>
                <td>Logo size: </td>
                <td><input type="text" maxlength="3" class="textinput2"
                           name="logo_width" value="{$Setting_LogoWidth}">×<input type="text" maxlength="3"
                           class="textinput2" name="logo_height" value="{$Setting_LogoHeight}"><input type="checkbox"
                           class="radio_checkbox" value="true"
                           name="logo_hidden"{$Setting_LogoHidden}>hidden</td>
            </tr>
            <tr>
                <td>Banner code: </td>
                <td><a href="admin_setting.php?module=banner">edit</a></td>
            </tr>
            <tr>
                <td>Banner size: </td>
                <td><input type="text" maxlength="3" class="textinput2"
                           name="banner_width" value="{$Setting_BannerWidth}">×<input type="text" maxlength="3"
                           class="textinput2" name="banner_height" value="{$Setting_BannerHeight}"><input
                           type="checkbox" class="radio_checkbox"
                           value="true" name="banner_hidden"{$Setting_BannerHidden}>hidden</td>
            </tr>
            <tr>
                <td>Default Language: </td>
                <td><select name="default_lang">{$Setting_DefaultLang}</select></td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <table cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <td align="center"><input type="submit" value="save" 
                                                          class="button1"></td>
                                <td align="center"><input type="reset" 
                                                          value="reset" class="button1"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>
