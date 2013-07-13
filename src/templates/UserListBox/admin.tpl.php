<div>
    <?php echo DB3_Button('link', '添加', array('url' => "?module={$this->module}/editor")); ?>
    <table class="db3_list1">
        <?php
        while ($item = $this->reader->GetEach()):
            ?>
            <tr>
                <td><?php echo $item->UID; ?></td>
                <td class="button">
                    <?php echo DB3_Button('link', '编辑', array('url' => "?module={$this->module}/editor&uid={$item->UID}")); ?>
                </td>
                <td class="button">
                    <?php echo DB3_Button('link', '删除', array('url' => "?module={$this->module}&function=delete&uid={$item->UID}")); ?>
                </td>
            </tr>
            <?php
        endwhile;
        ?>
    </table>
</div>
