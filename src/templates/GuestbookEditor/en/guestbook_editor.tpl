<center>
    <form name="guestbook" method="post" action="guestbook.php?function=add">
        <table width="550" border="0" cellspacing="0" cellpadding="1">
            <tr>
                <td colspan="1" width="60" align=center>*Name: </td>
                <td colspan="1" width="215"><input type="text" name="guest_name"
                                                   class="textinput3" /></td>
                <td colspan="1" width="60" align="center">*e-Mail: </td>
                <td colspan="2" width="215"><input type="text" name="guest_mail"
                                                   class="textinput3" /></td>
            </tr>
            <tr>
                <td colspan="1" width="60" align="center">Homepage: </td>
                <td colspan="1" width="215"><input type="text" name="guest_homepage"
                                                   class="textinput3" value="http://" /></td>
                <td colspan="1" width="60" align="center">Face: </td>
                <td colspan="1" width="65"><select name="guest_head"
                                                   onchange="javascript:show_head(head_viewer)" class="select1">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                    </select></td>
                <td rowspan="2" colspan="1" width="150" align="center" valign="middle"><img
                        id="head_viewer" src="images/head/1.gif" border="0" width="55" height="60" /></td>
            </tr>
            <tr>
                <td colspan="1" width="60" align="center">*Subject: </td>
                <td colspan="3" width="340"><input type="text" name="guest_title"
                                                   class="textinput4" value="{$Editor_Title}" /></td>
            </tr>
            <tr>
                <td colspan="1" width="60" align="center" valign="middle">*Content: </td>
                <td colspan="4" width="490"><textarea name="guest_text" class="textarea1"></textarea></td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2" width="60" align="center" valign="middle">*Check code: </td>
                <td colspan="4" width="490"><a title="refresh" 
                                               href="#"><img border="0" src="checkcode.php" onclick="refresh_checkcode(this);" /></a></td>
            </tr>
            <tr>
                <td colspan="4" width="490"><input type="text" name="check_code"
                                                   class="textinput2" /></td>
            </tr>
        </table>
    </form>
</center>
<script language="javascript">
    //<![CDATA[
function refresh_checkcode(img) {
    if(!window.checkcode_refreshes)
        checkcode_refreshes=0;
    checkcode_refreshes++;
    img.src='checkcode.php?refresh='+checkcode_refreshes;
}
function save_message(){
    document.guestbook.method="post";
    document.guestbook.action="guestbook.php?function=add";
    document.guestbook.submit();
}
function reset_message(){
document.guestbook.reset();
document.all.head_viewer.src="images/head/1.gif";
}
function show_head(a){
a.src="images/head/"+document.guestbook.guest_head.value+".gif";
}
        
show_head(document.getElementById("head_viewer"));


//]]>
</script>
