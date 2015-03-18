<?php // MOBILE ADs below arrows ?>
<?php if ($userAgent->isMobile() && !$userAgent->isTablet() && ($page < $numpages)) : ?>
    <!-- Size: 336x280 -->
    <!-- 336x280 -->
    <div style="text-align: center;">
        <div style="display: inline-block;">
            <div style="font-size: 12px;">Advertisement</div>
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

    <?php // TABLET ADs below arrows ?>
<?php elseif ($userAgent->isTablet() && ($page < $numpages)) : ?>
    <!-- Size: 336x280 -->
    <!-- 336x280 -->
    <div style="text-align: center;">
        <div style="display: inline-block;">
            <div style="font-size: 10px;">Advertisement</div>
           <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- 336x280 -->
            <ins class="adsbygoogle"
            style="display:inline-block;width:336px;height:280px"
            data-ad-client="ca-pub-4049798989734696"
            data-ad-slot="6912161568"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
        </div>
     </div>

    <?php // DESKTOP ADs below arrows ?>
<?php elseif ($page < $numpages) : ?>
    <!-- Size: 336x280 -->
    <!-- 336x280 -->
    <div style="text-align: center;">
        <div style="display: inline-block;">
            <div style="font-size: 10px;">Advertisement</div>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- 336x280 -->
                <ins class="adsbygoogle"
                style="display:inline-block;width:336px;height:280px"
                data-ad-client="ca-pub-4049798989734696"
                data-ad-slot="6912161568"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
        </div>
     </div>
<?php endif; ?>