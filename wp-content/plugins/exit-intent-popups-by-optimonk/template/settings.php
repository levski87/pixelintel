<div class="wrapper">
    <h2>OptiMonk</h2>
    <?php echo join("<br>\n", $error); ?>
    <form method="post" action="admin-post.php?action=optimonk_settings">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="optiMonk-accountId"><?php echo __('Account Id', 'optimonk'); ?></label></th>
                <td><input type="text" name="optiMonk_accountId" id="optiMonk-accountId" value="<?php echo get_option('optiMonk_accountId'); ?>" /></td>
                <td><img src="<?php echo $pluginDir; ?>/assets/example.jpg" /></td>
            </tr>
        </table>
        <?php @submit_button(); ?>
    </form>
</div>
