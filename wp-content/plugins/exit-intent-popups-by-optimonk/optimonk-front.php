<?php

/**
 * @Class OptiMonkFront
 */
class OptiMonkFront
{
    public function init()
    {
        $accountId = get_option('optiMonk_accountId');

        if (!$accountId) {
            return;
        }

        $insertJavaScript = file_get_contents(dirname(__FILE__) . '/template/insert-code.js');
        $insertJavaScript = str_replace('{{accountId}}', $accountId, $insertJavaScript);

        echo <<<EOD
<script type="text/javascript">
$insertJavaScript
</script>
EOD;
    }
}
