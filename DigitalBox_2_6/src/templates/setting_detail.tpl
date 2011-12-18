<form action="admin_setting.php?module=detail&function=save" method="post">
    <table>
        <tbody>
            <tr>
                <th align="left">栏目开关</th>
            </tr>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td width="25"><input type="checkbox"{$Setting_GuestBook_Visible} 
                                                      class="radio_checkbox" value="true" name="guestbook_visible" /></td>
                                <td>开通留言本</td>
                                <td width="25"><input type="checkbox"{$Setting_Vote_Visible} 
                                                      class="radio_checkbox" value="true" name="vote_visible" /></td>
                                <td>显示投票栏目框</td>
                            </tr>
                            <tr>
                                <td width="25"><input type="checkbox"{$Setting_Search_Visible} 
                                                      class="radio_checkbox" value="true" name="search_visible" /></td>
                                <td>开通站内搜索</td>
                                <td width="25"><input type="checkbox"{$Setting_Calendar_Visible} 
                                                      class="radio_checkbox" value="true" name="calendar_visible" /></td>
                                <td>显示日历栏目框</td>
                            </tr>
                            <tr>
                                <td width="25"><input type="checkbox"{$Setting_FriendSite_Visible} 
                                                      class="radio_checkbox" value="true" name="friendsite_visible" /></td>
                                <td>开通友情链接</td>
                                <td width="25"><input type="checkbox"{$Setting_Style_Changeable} 
                                                      class="radio_checkbox" value="true" name="style_changeable" /></td>
                                <td>允许选择风格</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left">列表调节</th>
            </tr>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black" align="center" valign="middle" rowspan="3"><b>标题每行文字个数</b></td>
                                <td>小栏目框(右)列表</td>
                                <td><input type="text" maxlength="2" value="{$Setting_B1T_MaxLen}"
                                           name="box1_title_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>小栏目框(左)列表</td>
                                <td><input type="text" maxlength="2" value="{$Setting_B2T_MaxLen}"
                                           name="box2_title_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>大栏目框列表</td>
                                <td><input type="text" maxlength="2" value="{$Setting_B3T_MaxLen}"
                                           name="box3_title_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black" align="center" valign="middle" rowspan="7"><b>标题列表最大行数</b></td>
                                <td>通常列表</td>
                                <td><input type="text" maxlength="2" value="{$Setting_GTL_MaxLen}"
                                           name="general_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>排名列表（最新、最热）</td>
                                <td><input type="text" maxlength="2" value="{$Setting_TTL_MaxLen}"
                                           name="toplist_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>频道范围内的内容列表（首页）</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ChTL_MaxLen}"
                                           name="channel_titlelist_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>栏目范围内的内容列表（频道页）</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ClTL_MaxLen}"
                                           name="class_titlelist_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>友情链接列表</td>
                                <td><input type="text" maxlength="2" value="{$Setting_FSTL_MaxLen}"
                                           name="site_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>留言本消息列表</td>
                                <td><input type="text" maxlength="2" value="{$Setting_GBTL_MaxLen}"
                                           name="guestbook_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>内容评论列表</td>
                                <td><input type="text" maxlength="2" value="{$Setting_CMTL_MaxLen}"
                                           name="comment_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black" align="center" valign="middle" rowspan="2"><b>图片列表</b></td>
                                <td>通常列表行数</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ImageDefault_MaxRow}"
                                           name="general_grid_maxrow" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>简要列表行数</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ImageIndex_MaxRow}"
                                           name="index_grid_maxrow" class="textinput2" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left">上传设置</th>
            </tr>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td>最大上传</td>
                                <td><input type="text" value="{$Setting_Upload_MaxSize}" class="textinput2"
                                           name="upload_maxsize" /> <select name="upload_sizeunit"
                                           class="select1">{$Setting_Upload_SizeUnit}</select></td>
                            </tr>
                            <tr>
                                <td>类型允许</td>
                                <td><input type="text"
                                           value="{$Setting_Upload_FileTypes}"
                                           class="textinput3" name="upload_filetypes" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <table cellspacing="0" cellpadding="5">
                        <tbody>
                            <tr>
                                <td align="center"><input type="submit" value="保存"
                                                          class="button1" /></td>
                                <td align="center"><input type="reset" value="重设"
                                                          class="button1" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>