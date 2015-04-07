<?php // MOBILE ADs ?>
<?php if ($userAgents->isMobile() && ($page <= $numpages) && !$userAgents->isTablet()) : ?>
    <!-- Size: 300x100 || 320x50 -->
    <div style="text-align: center;">
        <div style="display: inline-block;">
        <div id="div-gpt-ad-1415463844615-0">
            <div style="font-size: 12px;">Advertisement
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- 300x250 -->
        <ins class="adsbygoogle"
            style="display:inline-block;width:300px;height:250px"
            data-ad-client="ca-pub-4049798989734696"
            data-ad-slot="8388894769"></ins>
                <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div>
        </div>
    </div>
    <?php // TABLET ADs ?>
<?php elseif ($userAgents->isTablet() && ($page <= $numpages)) : ?>
    <!-- Size: 728x90 -->
    <!-- leaderboard -->
    <div style="text-align: center;">
        <div style="display: inline-block;">
            <div style="font-size: 12px;">Advertisement
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- 728x90 -->
                    <ins class="adsbygoogle"
                    style="display:inline-block;width:728px;height:90px"
                    data-ad-client="ca-pub-4049798989734696"
                    data-ad-slot="3958695161"></ins>
                <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
                    </div>
                </div>
            </div>
    <?php // DESKTOP ADs ?>
<?php elseif ($page <= $numpages) :?>
    <!-- Size: 728x90 -->
    <!-- leaderboard -->
    <div style="text-align: center;">
        <div style="display: inline-block;">
            <div style="font-size: 12px;"> Advertisement 
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 728x90 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-4049798989734696"
     data-ad-slot="3958695161"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
        </div>
        </div>
    </div>
<?php endif; ?>
