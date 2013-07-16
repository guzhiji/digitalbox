<div>
    <?php if ($int_id > 0): ?>
        <?php echo DB3_Button('link', '向上', array('url' => "?module={$this->module}&id={$int_parent}")); ?>
    <?php endif; ?>
</div>
<div>
    <table class="db3_list1">
        <?php
        while ($item = $this->reader->GetEach()) :
            ?>
            <tr>
                <td><?php echo DB3_Link($item->Name, "?module={$this->module}&id={$item->ID}"); ?></td>
                <td><?php echo $item->UID; ?></td>
            </tr>
            <?php
        endwhile;
        ?>
    </table>
</div>
