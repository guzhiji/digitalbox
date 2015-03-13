<form action="admin_upload.php?function=getupload"
      method="post" name="UploadForm" enctype="multipart/form-data">
    <table>
        <tbody>
            <tr>
                <td align="center">
                    <table>
                        <tbody>
                            <tr>
                                <td height="5" colspan="2">file types：{$Upload_FileTypes}</td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2">max size：{$Upload_MaxSize}</td>
                            </tr>
                            <tr>
                                <td>[File 1] description: </td>
                                <td><input type="text" name="UpFile1Caption" /></td>
                            </tr>
                            <tr>
                                <td>[File 1] local path: </td>
                                <td><input type="file" name="UpFile1" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>[File 2] description: </td>
                                <td><input type="text" name="UpFile2Caption" /></td>
                            </tr>
                            <tr>
                                <td>[File 2] local path: </td>
                                <td><input type="file" name="UpFile2" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>[File 3] description: </td>
                                <td><input type="text" name="UpFile3Caption" /></td>
                            </tr>
                            <tr>
                                <td>[File 3] local path: </td>
                                <td><input type="file" name="UpFile3" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>[File 4] description: </td>
                                <td><input type="text" name="UpFile4Caption" /></td>
                            </tr>
                            <tr>
                                <td>[File 4] local path: </td>
                                <td><input type="file" name="UpFile4" /></td>
                            </tr>
                            <tr>
                                <td height="5" colspan="2"></td>
                            </tr>
                            <tr>
                                <td>[File 5] description: </td>
                                <td><input type="text" name="UpFile5Caption" /></td>
                            </tr>
                            <tr>
                                <td>[File 5] local path: </td>
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
                                <td><input type="submit" value="upload" class="button1" /></td>
                                <td><input type="button"
                                           onclick="window.location.href='admin_upload.php'"
                                           value="back" class="button1" /></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</form>
