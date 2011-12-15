<form action="admin_setting.php?module=basic&function=save" name="SForm0" method="post">
    <table>
        <tbody>
            <tr>
                <td>网站名称：</td>
                <td><input type="text" value="{$Setting_SiteName}"
                           class="textinput1" name="site_name"></td>
            </tr>
            <tr>
                <td>关键描述：</td>
                <td><input type="text" value="{$Setting_SiteKeywords}"
                           class="textinput1" name="site_keywords"></td>
            </tr>
            <tr>
                <td>站长邮箱：</td>
                <td><input type="text" value="{$Setting_MasterMail}"
                           class="textinput1" name="master_mail"></td>
            </tr>
            <tr>
                <td>底部声明：</td>
                <td><input type="text" value="{$Setting_SiteStatement}" class="textinput1"
                           name="site_statement"></td>
            </tr>
            <tr>
                <td>LOGO地址：</td>
                <td><input type="text" class="textinput1" name="logo_URL" value="{$Setting_LogoURL}"></td>
            </tr>
            <tr>
                <td>LOGO尺寸：</td>
                <td><input type="text" maxlength="3" class="textinput2"
                           name="logo_width" value="{$Setting_LogoWidth}">×<input type="text" maxlength="3"
                           class="textinput2" name="logo_height" value="{$Setting_LogoHeight}"><input type="checkbox"
                           class="radio_checkbox" value="true"
                           name="logo_hidden"{$Setting_LogoHidden}>隐藏</td>
            </tr>
            <tr>
                <td>横幅名称：</td>
                <td><input type="text" class="textinput1" name="banner_name" value="{$Setting_BannerName}"></td>
            </tr>
            <tr>
                <td>横幅尺寸：</td>
                <td><input type="text" maxlength="3" class="textinput2"
                           name="banner_width" value="{$Setting_BannerWidth}">×<input type="text" maxlength="3"
                           class="textinput2" name="banner_height" value="{$Setting_BannerHeight}"><input
                           type="checkbox" class="radio_checkbox"
                           value="true" name="banner_hidden"{$Setting_BannerHidden}>隐藏</td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <table cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <td align="center"><input type="submit" value="保存" 
                                                          class="button1"></td>
                                <td align="center"><input type="reset" 
                                                          value="重设" class="button1"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>
