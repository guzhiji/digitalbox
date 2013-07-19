<div>
    <?php echo DB3_Button('link', '创建文章', array('url' => "?module=content/editor&catalog={$int_catalogid}")); ?>
    <table class="db3_list1">
        <?php
        while ($item = $this->reader->GetEach()):
            ?>
            <tr>
                <td><?php echo DB3_Link($item->Name, "?module=content&id={$item->ID}"); ?></td>
                <td><?php echo $item->UID ?></td>
                <td class="button">
                    <?php echo DB3_Button('link', '编辑', array('url' => "?module=content/editor&id={$item->ID}")); ?>
                </td>
                <td class="button">
                    <?php echo DB3_Button('link', '删除', array('url' => "?module=content&function=delete&id={$item->ID}")); ?>
                </td>
            </tr>
            <?php
        endwhile;
        ?>
    </table>
</div>