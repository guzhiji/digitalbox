<form action="admin_upload.php?function=getupload"
      method="post" name="UploadForm" enctype="multipart/form-data">
    <table>
        <tbody>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td height="5" colspan="2">允许文件类型：{$Upload_FileTypes}</td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2">一次最大上传：{$Upload_MaxSize}</td>
                            </tr>
                            <tr>
                                <td>文件1 描述：</td>
                                <td><input type="text" name="UpFile1Caption" /></td>
                            </tr>
                            <tr>
                                <td>文件1 地址：</td>
                                <td><input type="file" name="UpFile1" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>文件2 描述：</td>
                                <td><input type="text" name="UpFile2Caption" /></td>
                            </tr>
                            <tr>
                                <td>文件2 地址：</td>
                                <td><input type="file" name="UpFile2" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>文件3 描述：</td>
                                <td><input type="text" name="UpFile3Caption" /></td>
                            </tr>
                            <tr>
                                <td>文件3 地址：</td>
                                <td><input type="file" name="UpFile3" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>文件4 描述：</td>
                                <td><input type="text" name="UpFile4Caption" /></td>
                            </tr>
                            <tr>
                                <td>文件4 地址：</td>
                                <td><input type="file" name="UpFile4" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>文件5 描述：</td>
                                <td><input type="text" name="UpFile5Caption" /></td>
                            </tr>
                            <tr>
                                <td>文件5 地址：</td>
                                <td><input type="file" name="UpFile5" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
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
                                <td><input type="submit" value="上传" class="button1" /></td>
                                <td><input type="button"
                                           onclick="window.location.href='admin_upload.php'"
                                           value="返回" class="button1" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>
