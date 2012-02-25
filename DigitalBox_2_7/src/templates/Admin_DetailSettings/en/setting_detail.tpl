<form action="admin_setting.php?module=detail&function=save" method="post">
    <table>
        <tbody>
            <tr>
                <th align="left">Modules</th>
            </tr>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td width="25"><input type="checkbox"{$Setting_GuestBook_Visible} 
                                                      class="radio_checkbox" value="true" name="guestbook_visible" /></td>
                                <td>use guestbook</td>
                                <td width="25"><input type="checkbox"{$Setting_Vote_Visible} 
                                                      class="radio_checkbox" value="true" name="vote_visible" /></td>
                                <td>show vote box</td>
                            </tr>
                            <tr>
                                <td width="25"><input type="checkbox"{$Setting_Search_Visible} 
                                                      class="radio_checkbox" value="true" name="search_visible" /></td>
                                <td>use search</td>
                                <td width="25"><input type="checkbox"{$Setting_Calendar_Visible} 
                                                      class="radio_checkbox" value="true" name="calendar_visible" /></td>
                                <td>show calendar box</td>
                            </tr>
                            <tr>
                                <td width="25"><input type="checkbox"{$Setting_FriendSite_Visible} 
                                                      class="radio_checkbox" value="true" name="friendsite_visible" /></td>
                                <td>show friendsites</td>
                                <td width="25"><input type="checkbox"{$Setting_Style_Changeable} 
                                                      class="radio_checkbox" value="true" name="style_changeable" /></td>
                                <td>allow changing themes</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left">Lists</th>
            </tr>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black" align="center" valign="middle" rowspan="3"><b>title length shown</b></td>
                                <td>in small boxes of the RIGHT column</td>
                                <td><input type="text" maxlength="2" value="{$Setting_B1T_MaxLen}"
                                           name="box1_title_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>in small boxes of the LEFT column</td>
                                <td><input type="text" maxlength="2" value="{$Setting_B2T_MaxLen}"
                                           name="box2_title_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>in big boxes</td>
                                <td><input type="text" maxlength="2" value="{$Setting_B3T_MaxLen}"
                                           name="box3_title_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black" align="center" valign="middle" rowspan="8"><b>max item number</b></td>
                                <td>general</td>
                                <td><input type="text" maxlength="2" value="{$Setting_GTL_MaxLen}"
                                           name="general_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>top lists (new & popular)</td>
                                <td><input type="text" maxlength="2" value="{$Setting_TTL_MaxLen}"
                                           name="toplist_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>content lists for channels (on the home page)</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ChTL_MaxLen}"
                                           name="channel_titlelist_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>content lists for classes (on channel pages)</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ClTL_MaxLen}"
                                           name="class_titlelist_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>friendsite list</td>
                                <td><input type="text" maxlength="2" value="{$Setting_FSTL_MaxLen}"
                                           name="site_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>message list in the guestbook</td>
                                <td><input type="text" maxlength="2" value="{$Setting_GBTL_MaxLen}"
                                           name="guestbook_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>message list in comment boxes</td>
                                <td><input type="text" maxlength="2" value="{$Setting_CMTL_MaxLen}"
                                           name="comment_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>RSS list</td>
                                <td><input type="text" maxlength="2" value="{$Setting_RSS_MaxLen}"
                                           name="rss_list_maxlen" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black" align="center" valign="middle" rowspan="2"><b>images</b></td>
                                <td>default max rows</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ImageDefault_MaxRow}"
                                           name="general_grid_maxrow" class="textinput2" /></td>
                            </tr>
                            <tr>
                                <td>max rows for overviews</td>
                                <td><input type="text" maxlength="2" value="{$Setting_ImageIndex_MaxRow}"
                                           name="index_grid_maxrow" class="textinput2" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left">Uploads</th>
            </tr>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td>max size</td>
                                <td><input type="text" value="{$Setting_Upload_MaxSize}" class="textinput2"
                                           name="upload_maxsize" /> <select name="upload_sizeunit"
                                           class="select1">{$Setting_Upload_SizeUnit}</select></td>
                            </tr>
                            <tr>
                                <td>types</td>
                                <td><input type="text"
                                           value="{$Setting_Upload_FileTypes}"
                                           class="textinput3" name="upload_filetypes" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left">Caching</th>
            </tr>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td>timeout</td>
                                <td><input type="text" value="{$Setting_Cache_Timeout}" class="textinput2"
                                           name="cache_timeout" /> seconds (as the lifetime for cached data)</td>
                            </tr>
                            <tr>
                                <td>clear cached data</td>
                                <td><input type="button" value="clear" class="button1" 
                                           onclick="window.location='admin_setting.php?module=detail&function=clearcache'" /></td>
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
                                <td align="center"><input type="submit" value="save"
                                                          class="button1" /></td>
                                <td align="center"><input type="reset" value="reset"
                                                          class="button1" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>
