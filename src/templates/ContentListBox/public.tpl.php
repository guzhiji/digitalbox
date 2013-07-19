<div>
    <table class="db3_list1">
        <?php
        while ($item = $this->reader->GetEach()):
            ?>
            <tr>
                <td><?php echo DB3_Link($item->Name, "?module=content&id={$item->ID}"); ?></td>
                <td><?php echo $item->UID ?></td>
            </tr>
            <?php
        endwhile;
        ?>
    </table>
</div>