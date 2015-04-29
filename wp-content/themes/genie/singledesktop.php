<?php
/* Maximum Pages */
$maxpages = $wp_query->max_num_pages;

if ( have_posts() ) {
	
	while ( have_posts() ) {
	
		the_post();
		
		$post_id = get_the_ID();
		
		$intro_text = bt_rwmb_meta( BTPFX . '_intro_text' );
		
		global $bt_featured_slider;
		$bt_featured_slider = bt_get_option( 'blog_featured_image_slider' ) && has_post_thumbnail();
		
		$featured_overlay = bt_rwmb_meta( BTPFX . '_featured_overlay' );
		
		$images = bt_rwmb_meta( BTPFX . '_images', 'type=image' );
		if ( $images == null ) $images = array();
		$video = bt_rwmb_meta( BTPFX . '_video' );
		$audio = bt_rwmb_meta( BTPFX . '_audio' );
		
		$link_title = bt_rwmb_meta( BTPFX . '_link_title' );
		$link_url = bt_rwmb_meta( BTPFX . '_link_url' );
		$quote = bt_rwmb_meta( BTPFX . '_quote' );
		$quote_author = bt_rwmb_meta( BTPFX . '_quote_author' );		
		
		$permalink = get_permalink();
		
		$post_format = get_post_format();
		
		$media_html = '';
		
		if ( $post_format == 'image' ) {
		
			foreach ( $images as $img ) {
				$src = $img['full_url'];
				$media_html = '<div class="mediaBox"><img src="' . esc_url( $src ) . '" alt="' . esc_attr( basename( $src ) ) . '"></div>';
				break;
			}
			
		} else if ( $post_format == 'gallery' ) {
		
			if ( count( $images ) > 0 ) {
				$images_ids = array();
				foreach ( $images as $img ) {
					$images_ids[] = $img['ID'];
				}			
				if ( bt_rwmb_meta( BTPFX . '_grid_gallery' ) ) {
					$media_html = do_shortcode( '[bt_grid_gallery ids="' . join( ',', $images_ids ) . '"]' );
				} else {
					$media_html = do_shortcode( '[gallery ids="' . join( ',', $images_ids ) . '"]' );
				}
			}
			
		} else if ( $post_format == 'video' ) {
			
			$media_html = '<div class="mediaBox"><div class="videoBox"><img class="aspectKeeper" src="' . esc_url( get_template_directory_uri() . '/gfx/16x9.gif' ) . '" alt="" role="presentation" aria-hidden="true"><div class="videoPort">';

			if ( strpos( $video, 'vimeo.com/' ) > 0 ) {
				$video_id = substr( $video, strpos( $video, 'vimeo.com/' ) + 10 );
				$media_html .= '<ifra' . 'me src="' . esc_url( 'http://player.vimeo.com/video/' . $video_id ) . '" allowfullscreen></ifra' . 'me>';
			} else {
				$yt_id_pattern = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
				$youtube_id = ( preg_replace( $yt_id_pattern, '$1', $video ) );
				if ( strlen( $youtube_id ) == 11 ) {
					$media_html .= '<ifra' . 'me width="560" height="315" src="' . esc_url( 'http://www.youtube.com/embed/' . $youtube_id ) . '" allowfullscreen></ifra' . 'me>';
				} else {	
					$media_html .= do_shortcode( $video );
				}
			}
			
			$media_html .= '</div></div></div>';
			
			if ( $video == '' ) {
				$media_html = '';
			}
			
		} else if ( $post_format == 'audio' ) {
			
			if ( strpos( $audio, '</ifra' . 'me>' ) > 0 ) {
				$media_html = '<div class="soundCloudBox">' . wp_kses( $audio, array( 'iframe' => array( 'height' => array(), 'src' =>array() ) ) ) . '</div>';
			} else {
				$media_html = '<div class="mediaBox">' . do_shortcode( $audio ) . '</div>';
			}
			
			if ( $audio == '' ) {
				$media_html = '';
			}
			
		} else if ( $post_format == 'link' ) {
			
			$media_html = '<blockquote class="quote link"><p>' . esc_html( $link_title ) . '</p><p class="author"><a href="' . esc_url( $link_url ) . '">' . esc_url( $link_url ) . '</a></p></blockquote>';
			
			if ( esc_html( $link_title ) == '' || esc_url( $link_url ) == '' ) {
				$media_html = '';
			}
			
		} else if ( $post_format == 'quote' ) {
			
			$media_html = '<blockquote class="quote"><p>' . esc_html( $quote  ). '</p><p class="author">' . esc_html( $quote_author ) . '</p></blockquote>';
			
			if ( esc_html( $quote ) == '' || esc_html( $quote_author ) == '' ) {
				$media_html = '';
			}
			
		}

		global $date_format;


		$content_html = apply_filters( 'the_content', get_the_content( '', false ) );
		$content_html = str_replace( ']]>', ']]&gt;', $content_html );
		
		$categories = get_the_category();
		$categories_html = '';
		if ( $categories ) {
			foreach ( $categories as $cat ) {
				$categories_html .= '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
			}
		}

		$tags = get_the_tags();
		$tags_html = '';
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				$tags_html .= '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li>';
			}
			$tags_html = rtrim( $tags_html, ', ' );
			$tags_html = '<div class="tagsCloud"><ul>' . $tags_html . '</ul></div>';
		}
		
		$prev_next_html = '';
		$prev = get_adjacent_post( false, '', true );
		if ( '' != $prev ) {
			$prev_next_html .= '<div class="neighbor left"><a href="' . esc_url( get_permalink( $prev ) ) . '">' . __( 'Previous Post', 'bt_theme' ) . '<strong>' . esc_html( $prev->post_title ) . '</strong></a></div>';
		}
		$next = get_adjacent_post( false, '', false );
		if ( '' != $next ) {
			$prev_next_html .= '<div class="neighbor right"><a href="' . esc_url( get_permalink( $next ) ) . '">' . __( 'Next Post', 'bt_theme' ) . '<strong>' . esc_html( $next->post_title ) . '</strong></a></div>';
		}
		if ( '' != $prev_next_html  ) {
			$prev_next_html = '<div class="neighbors">' . $prev_next_html . '</div>';
		}

		$about_author_html = '';
		if ( bt_get_option( 'blog_author_info' ) ) {
		
			$avatar_html = get_avatar( get_the_author_meta( 'ID' ), 280 );
			$avatar_html = str_replace ( 'width=\'280\'', 'width=\'140\'', $avatar_html );
			$avatar_html = str_replace ( 'height=\'280\'', 'height =\'140\'', $avatar_html );
			$avatar_html = str_replace ( 'width="280"', 'width="140"', $avatar_html );
			$avatar_html = str_replace ( 'height="280"', 'height ="140"', $avatar_html );
			
			$about_author_html = '<div class="btAboutAuthor">';
			
			$user_url = get_the_author_meta( 'user_url' );
			if ( $user_url != '' ) {
				$author_html = '<a href="' . esc_url( $user_url ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a>';
			} else {
				$author_html = esc_html( get_the_author_meta( 'display_name' ) );
			}
			
			if ( $avatar_html ) {
				$about_author_html .= '<div class="aaAvatar">' . $avatar_html . '</div>';
			}
			
			$about_author_html .= '<div class="aaTxt"><h4>' . $author_html;
			$about_author_html .= '</h4>
					<p>' . get_the_author_meta( 'description' ) . '</p>
				</div>
			</div>';
		}

		$blog_share_facebook = bt_get_option( 'blog_share_facebook' );
		$blog_share_twitter = bt_get_option( 'blog_share_twitter' );
		$blog_share_google_plus = bt_get_option( 'blog_share_google_plus' );
		$blog_share_linkedin = bt_get_option( 'blog_share_linkedin' );
		$blog_share_vk = bt_get_option( 'blog_share_vk' );		

		get_template_part( 'php/share' );

		$share_html = '';
		if ( $blog_share_facebook || $blog_share_twitter || $blog_share_google_plus || $blog_share_linkedin || $blog_share_vk ) {
			
			if ( $blog_share_facebook ) {
				$share_html .= '<a href="' . esc_url( bt_get_share_link( 'facebook', $permalink ) ) . '" class="ico" title="Facebook"><span data-icon="&#xf09a;"></span></a>';
			}
			if ( $blog_share_twitter ) {
				$share_html .= '<a href="' . esc_url( bt_get_share_link( 'twitter', $permalink ) ) . '" class="ico" title="Twitter"><span data-icon="&#xf099;"></span></a>';
			}
			if ( $blog_share_linkedin ) {
				$share_html .= '<a href="' . esc_url( bt_get_share_link( 'linkedin', $permalink ) ) . '" class="ico" title="LinkedIn"><span data-icon="&#xf0e1;"></span></a>';
			}
			if ( $blog_share_google_plus ) {
				$share_html .= '<a href="' . esc_url( bt_get_share_link( 'google_plus', $permalink ) ) . '" class="ico" title="Google Plus"><span data-icon="&#xf0d5;"></span></a>';
			}
			if ( $blog_share_vk ) {
				$share_html .= '<a href="' . esc_url( bt_get_share_link( 'vk', $permalink ) ) . '" class="ico" title="VK"><span data-icon="&#xf189;"></span></a>';
			}			
		}
		
		$blog_author = bt_get_option( 'blog_author' );
		$blog_date = bt_get_option( 'blog_date' );
		$blog_number_comments = bt_get_option( 'blog_number_comments' );
		$comments_open = comments_open();
		$comments_number = get_comments_number();
		$show_comments_number = true;
		if ( ! $blog_number_comments || ( ! $comments_open && $comments_number == 0 ) ) {
			$show_comments_number = false;
		}
		
		$meta = '';
		
		$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
		$author_html = '<a href="' . esc_url( $author_url ) . '">' . esc_html( get_the_author() ) . '</a>';			
		
		if ( $blog_author || $blog_date || $show_comments_number ) {
			$meta .= '<p class="meta">';
			if ( $blog_date ) $meta .= esc_html( date_i18n( $date_format, strtotime( get_the_time( 'Y-m-d' ) ) ) ); 
			if ( $blog_date && $blog_author ) $meta .= ' &mdash; ';
			if ( $blog_author ) $meta .= __( 'by', 'bt_theme' ) . ' <strong>' . $author_html . '</strong>';
			if ( ( $blog_date || $blog_author ) && $show_comments_number ) $meta .= ' &mdash; ';
			if ( $show_comments_number ) $meta .= '<span class="commentCount"><a href="' . esc_url( $permalink ) . '#comments">' . $comments_number . '</a></span>';
			$meta .= '</p>';
		}
		
		if ( has_post_thumbnail() && $bt_featured_slider ) {
		
			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$img = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );

			wp_enqueue_script( 'bt_anystretch_js', get_template_directory_uri() . '/js/jquery.anystretch.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'bt_classie_js', get_template_directory_uri() . '/js/classie.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'bt_single_js', get_template_directory_uri() . '/js/single.js', array( 'jquery' ), '', true );	

			echo '<div id="topBlock" class="topBlock tpost">';
				echo '<div id="imageHolder" data-stretch="' . esc_attr( $img[0] ) . '">';
					if ( $featured_overlay ) {
						echo '<div class="tbPort fade">';
					} else {
						echo '<div class="tbPort">';
					}
						echo '<div class="tbTable classic">';
							echo '<header class="tbHeader light">';
								echo '<h3>' . $categories_html . '</h3>';
								echo '<h1>' . esc_html( get_the_title() ) . '</h1>';
								echo $meta;
							echo '</header>';
						echo '</div><!-- /tbTable -->';
					echo '</div><!-- /tbPort -->';
				echo '</div><!-- /imageHolder -->';
			echo '</div><!-- /topBlock -->';
		}
		
		if ( has_post_thumbnail() && $bt_featured_slider ) {
			echo '<div id="content" class="content tpost">';
		} else {
			echo '<div id="content" class="content">';
		}
		echo '<div class="gutter">';

		$sidebar = bt_get_option( 'sidebar' );
		if ( isset( $_GET['sidebar'] ) && $_GET['sidebar'] != '' ) {
			$sidebar = $_GET['sidebar'];
		}
		if ( ( $sidebar == 'left' || $sidebar == 'right' ) && ! is_404() ) {
			echo '<aside class="side column ' . sanitize_html_class( $sidebar ) . '" role="complementary" data-toggler-label="' . esc_attr( __( 'Additional Content', 'bt_theme' ) ) . '">';
				dynamic_sidebar( 'primary_widget_area' );
			echo '</aside><!-- /side -->';
			echo '<section class="main column narrow" role="main"><h2>main</h2>';


		
			$class_array = array( 'classic' );
		} else {
			echo '<section class="main column wide" role="main"><h2>main</h2>';
			$class_array = array( 'classic', 'btSingle' );
		}
		
		?><article <?php post_class( $class_array ); ?>>
			<?php
			
				echo '<header>
				<h3>' . $categories_html . '</h3>
				<h2>' . get_the_title() . '</h2>';
				echo $meta;			
			/* Top Ads */
			render_partial('partials/ads-atf', ['page' => $page, 'numpages' => $numpages, 'userAgents' => $userAgents]); 
			echo '</header>
			' . $media_html . '
			<p class="loud">' . esc_html( $intro_text ) . '</p>
			<div class="articleBody">';
			if ( $post_format == 'link' && $media_html == '' ) {
				echo '<blockquote class="quote link">';
			}			
			echo $content_html;
			if ( $post_format == 'link' && $media_html == '' ) {
				echo '</blockquote>';
			}					
			echo '</div><!-- /articleBody -->';
			
		 if ($numpages > 1)  { ?>
   				 <div class="page-link-container" style="text-align: center !important;">
        	<?php
        // This shows the Previous link
        wp_link_pages( array( 'before' => '<div class="page-link-nextprev" style="display: inline-block !important;">',
                              'previouspagelink' => '<span class="previous">Back</span>', 
                              'nextpagelink' => '',
                              'next_or_number' => 'next',
                              'after' => '</div>', 
                               ) );
       		 ?>

        <div class="page-count" style="display: inline-block !important;">

            <?php echo( $page.' of '.count($pages) ); ?>

        </div>
        <?php
        // This shows the Next link
        wp_link_pages( array( 'before' => '<div class="page-link-nextprev" style="display: inline-block !important;">',  
        					  'previouspagelink' => '',
                              'nextpagelink' => 
                              '<span class="next">Next</span>', 
                              'next_or_number' => 'next',
                              'after' => '</div>', 
                              ) );
        ?>
    </div>
<?php } ?>

	    <div class="sswpds-social-wrap col-lg-6 col-lg-offset-3" style="padding: 17px 0px; text-align: center !important;">
        <a href="<?php echo esc_url('http://www.facebook.com/sharer.php?u='
            .get_permalink()); ?>" target="_blank">
            <i class="fa fa-facebook-official"></i> Share on Facebook
        </a>
    </div>


<?php $postid = get_the_ID(); 
	if ($postid != 871 && $postid != 697 && $postid != 828 && $postid != 516 || $page !=  1): ?>
		<!-- 336x280 -->
	 <div style="text-align: center;">
        <div style="display: inline-block;">
            <div style="font-size: 12px;">Advertisement</div>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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


    <div class="contentID" style="text-align:center;">
    <div id="contentad49382"></div>
<script type="text/javascript">
    (function() {
        var params =
        {
            id: "3b1fd093-40eb-4845-927b-aa87c5def8a8",
            d:  "cGl4ZWxpbnRlbC5jb20=",
            wid: "49382",
            cb: (new Date()).getTime()
        };

        var qs="";
        for(var key in params){qs+=key+"="+params[key]+"&"}
        qs=qs.substring(0,qs.length-1);
        var s = document.createElement("script");
        s.type= 'text/javascript';
        s.src = "http://api.content.ad/Scripts/widget.aspx?" + qs;
        s.async = true;
        document.getElementById("contentad49382").appendChild(s);
    })();
</script>
</div>


<?php
	
			echo $about_author_html . '
			<footer>
				' . $tags_html . '	
				<div class="socialsRow">
					' . $share_html . '
				</div><!-- /socialsRow -->
			</footer>			
		</article>';
		
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		
		echo $prev_next_html;
		
	}
}

echo '</section><!-- /main -->';
echo '</div><!-- /gutter -->';
echo '</div><!-- /content -->';

global $bt_exclude_post;
$bt_exclude_post = $post_id;

get_template_part( 'php/slider' );

get_footer();

 if ($page > 2) : ?>
    <div class="col-md-4 col-xs-12 floating-share-bar">
        <div class="fb-share-button col-xs-7 text-center" style="border-radius: 5px; background-color: #2a5697; padding: 4px 6px; font-size: 23px">
            <a href="<?php echo esc_url('http://www.facebook.com/sharer.php?u='
            .get_permalink()); ?>" id="fb-floating-share" target="_blank">
                <i class="fa fa-facebook-square"></i> Share On Facebook
            </a>
        </div>
    </div>


    <style>#ac_exitpopModal-widgetcontent{display:none}#ac_exitpopModal-overlay{background-color:rgba(0,0,0,.75);height:100%;left:0;position:fixed;top:0;width:100%;z-index:999999}#ac_exitpopModal-container,#ac_exitpopModal-container *{box-sizing:content-box}#ac_exitpopModal-container{position:fixed;width:50%!important;max-width:800px;background:#fff;border:1px solid #ababab;box-shadow:0 4px 16px rgba(0,0,0,.2);height:auto;padding:18px 14px 24px;font-family:arial,sans-serif;font-size:14px;z-index:1000000}@media (min-width:768px){#ac_exitpopModal-container{padding:18px 24px 24px}}#ac_exitpopModal-header{position:relative;float:right}#ac_exitpopModal-close{position:absolute;top:0;right:0;cursor:pointer;display:block;filter:alpha(opacity=60);-moz-opacity:.6;-webkit-opacity:.6;-ms-filter:alpha(opacity=60);opacity:.6}#ac_exitpopModal-close:before{content:"X";font-family:arial,sans-serif;font-size:20px;font-weight:400;font-style:normal}#ac_exitpopModal-close:hover{filter:alpha(opacity=100);-moz-opacity:1;-webkit-opacity:1;-ms-filter:alpha(opacity=100);opacity:1}#ac_exitpopModal-content{display:block;z-index:999;text-align:left;color:#333;font-family:arial,sans-serif;font-size:12px;background-color:#fff}
</style>
<div id="ac_exitpopModal-widgetcontent">
<div id="contentad49830"></div>
</div>
    <script type="text/javascript">
var exitpopModal = function() {
    var method = {},
        modalOverlay = document.createElement("div"),
        modalContainer = document.createElement("div"),
        modalHeader = document.createElement("div"),
        modalContent = document.createElement("div"),
        modalClose = document.createElement("div"),
        centerModal, closeModalEvent, defaultSettings = {
            width: "auto",
            height: "auto",
            lock: false,
            hideClose: false,
            closeAfter: 0,
            openCallback: function() {},
            closeCallback: false,
            hideOverlay: false
        };
    method.open = function(parameters) {
        centerModal = function() {
            method.center()
        };
        if (parameters.content && !parameters.ajaxContent) modalContent.innerHTML = parameters.content;
        else modalContent.innerHTML = "";
        modalContainer.style.width = defaultSettings.width;
        modalContainer.style.height = defaultSettings.height;
        method.center();
        if (defaultSettings.lock || defaultSettings.hideClose) modalClose.style.visibility = "hidden";
        if (!defaultSettings.hideOverlay) modalOverlay.style.visibility = "visible";
        modalContainer.style.visibility = "visible";
        document.onkeypress = function(e) {
            if (e.keyCode === 27 && defaultSettings.lock !==
                true) method.close()
        };
        if (modalClose) modalClose.onclick = function() {
            if (!defaultSettings.hideClose) method.close();
            else return false
        };
        if (modalOverlay) modalOverlay.onclick = function() {
            if (!defaultSettings.lock) method.close();
            else return false
        };
        if (window.addEventListener) {
            window.addEventListener("resize", centerModal, false);
            window.addEventListener("gestureend", function() {
                window.setTimeout("exitpopModal.center()", 250)
            }, false);
            window.addEventListener("orientationchange", function() {
                window.setTimeout("exitpopModal.center()",
                    250)
            }, false)
        } else if (window.attachEvent) window.attachEvent("onresize", centerModal);
        modalHeader.onmousedown = function() {
            return false
        };
        if (defaultSettings.closeAfter > 0) closeModalEvent = window.setTimeout(function() {
            method.close()
        }, defaultSettings.closeAfter * 1E3);
        if (defaultSettings.openCallback) defaultSettings.openCallback()
    };
    method.close = function() {
        modalContent.innerHTML = "";
        modalOverlay.setAttribute("style", "");
        modalOverlay.style.cssText = "";
        modalOverlay.style.visibility = "hidden";
        modalContainer.setAttribute("style",
            "");
        modalContainer.style.cssText = "";
        modalContainer.style.visibility = "hidden";
        modalHeader.style.cursor = "default";
        modalClose.setAttribute("style", "");
        modalClose.style.cssText = "";
        if (closeModalEvent) window.clearTimeout(closeModalEvent);
        if (defaultSettings.closeCallback) defaultSettings.closeCallback();
        if (window.removeEventListener) window.removeEventListener("resize", centerModal, false);
        else if (window.detachEvent) window.detachEvent("onresize", centerModal)
    };
    method.widget = function() {
        var params = {
            id: "1bec0dae-9c4b-4b7c-b572-b9a075c7d550",
            d:  "cGl4ZWxpbnRlbC5jb20=",
            wid: "49830",
            cb: (new Date()).getTime()
        };
        var qs = "";
        for (var key in params) qs += key + "=" + params[key] + "&";
        qs = qs.substring(0, qs.length - 1);
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.src = "http://api.content.ad/Scripts/widget.aspx?" + qs;
        s.async = true;
        document.getElementById("contentad49830").appendChild(s)
    };
    method.center = function() {
        var outer = document.createElement("div");
        outer.style.visibility = "hidden";
        outer.style.width = "100px";
        outer.style.msOverflowStyle =
            "scrollbar";
        document.body.appendChild(outer);
        var widthNoScroll = outer.offsetWidth;
        outer.style.overflow = "scroll";
        var inner = document.createElement("div");
        inner.style.width = "100%";
        outer.appendChild(inner);
        var widthWithScroll = inner.offsetWidth;
        outer.parentNode.removeChild(outer);
        var scrollbarWidth = widthNoScroll - widthWithScroll;
        var documentHeight = Math.max(window.innerHeight, document.documentElement.scrollHeight),
            modalWidth = Math.max(modalContainer.clientWidth, modalContainer.offsetWidth),
            modalHeight = Math.max(modalContainer.clientHeight,
                modalContainer.offsetHeight),
            browserWidth = 0,
            browserHeight = 0,
            amountScrolledX = 0,
            amountScrolledY = 0;
        if (typeof window.innerWidth === "number") {
            browserWidth = window.innerWidth - scrollbarWidth;
            browserHeight = window.innerHeight
        } else if (document.documentElement && document.documentElement.clientWidth) {
            browserWidth = document.documentElement.clientWidth - scrollbarWidth;
            browserHeight = document.documentElement.clientHeight
        }
        if (typeof window.pageYOffset === "number") {
            amountScrolledY = window.pageYOffset;
            amountScrolledX = window.pageXOffset
        } else if (document.body &&
            document.body.scrollLeft) {
            amountScrolledY = document.body.scrollTop;
            amountScrolledX = document.body.scrollLeft
        } else if (document.documentElement && document.documentElement.scrollLeft) {
            amountScrolledY = document.documentElement.scrollTop;
            amountScrolledX = document.documentElement.scrollLeft
        }
        modalContainer.style.top = Math.max(0, browserHeight / 2 - modalHeight / 2) + "px";
        modalContainer.style.left = Math.max(0, browserWidth / 2 - modalWidth / 2) + "px";
        modalOverlay.style.height = browserHeight + "px";
        modalOverlay.style.width = "100%";
        if (navigator.userAgent.match(/iPad/i)) {
            modalContainer.style.position = "absolute";
            modalContainer.style.top = amountScrolledY + browserHeight / 2 - modalHeight / 2 + "px";
            modalContainer.style.left = amountScrolledX + browserWidth / 2 - modalWidth / 2 + "px";
            modalOverlay.style.height = documentHeight + "px"
        }
    };
    modalOverlay.setAttribute("id", "ac_exitpopModal-overlay");
    modalContainer.setAttribute("id", "ac_exitpopModal-container");
    modalHeader.setAttribute("id", "ac_exitpopModal-header");
    modalContent.setAttribute("id", "ac_exitpopModal-content");
    modalClose.setAttribute("id", "ac_exitpopModal-close");
    modalHeader.appendChild(modalClose);
    modalContainer.appendChild(modalHeader);
    modalContainer.appendChild(modalContent);
    modalOverlay.style.visibility = "hidden";
    modalContainer.style.visibility = "hidden";
    document.body.appendChild(modalOverlay);
    document.body.appendChild(modalContainer);
    return method
}();

function exitpopModal_open() {
    if (document.getElementById("ac_49830")) {
        var exitpopModalContent = document.getElementById("ac_exitpopModal-widgetcontent").innerHTML;
        exitpopModal.open({
            content: exitpopModalContent
        })
    } else setTimeout("exitpopModal_open();", 300)
}
var IE = document.all ? true : false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = getMouseXY;
var tempX = 0;
var tempY = 0;

function getMouseXY(e) {
    tempX = e.pageX;
    tempY = e.pageY;
    if (tempX < 0) tempX = 0;
    if (tempY < 0) tempY = 0;
    if (tempY <= 20 && document.cookie.indexOf("exitpopped") == -1) {
        exitpopModal.widget();
        exitpopModal_open();
        document.cookie = "exitpopped=1;max-age=" + 36E5 * 24 * 7
    }
};
    </script>
<?php endif ?>