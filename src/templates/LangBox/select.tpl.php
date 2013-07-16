<div align="center">
    <form name="lang_select" method="post" action="?module=languages&function=select">
        <input type="hidden" name="from" value="<?php echo $referrer; ?>" />
        <table class="db3_list1">
            <?php
            foreach ($languages as $code => $name):
                ?>
                <tr><td align="left"><input type="radio" class="radio_checkbox" name="lang" value="<?php echo $code; ?>"<?php
            echo $code == $lang ? ' checked="checked"' : '';
                ?> /> <?php echo $name; ?></td></tr>
                    <?php
                endforeach;
                ?>
        </table>
        <div align="center"><?php echo DB3_Button('submit', $this->GetLangData('ok')); ?></div>
    </form>
</div>