<div align="center">
    <form name="lang_select" method="post" action="?module=languages&function=select">
        <input type="hidden" name="from" value="<?php echo $referrer; ?>" />
        <table>
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
        <div align="center"><input type="submit" class="button1" value="<?php echo $this->GetLangData('ok'); ?>" /></div>
    </form>
</div>