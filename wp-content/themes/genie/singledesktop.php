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
	if ($postid != 871 && $postid != 697 || $page !=  1): ?>
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

<div id="ac_delayedModal-widgetcontent"><div id="contentad49830"></div></div>
<script type="text/javascript">
function delayedpopModal_open(){if(document.getElementById("ac_49830")){var e=document.getElementById("ac_delayedModal-widgetcontent").innerHTML;delayedpopModal.open({content:e})}else setTimeout("delayedpopModal_open();",300)}var delayedpopModal=function(){var e,t,n={},d=document.createElement("div"),o=document.createElement("div"),i=document.createElement("div"),l=document.createElement("div"),c=document.createElement("div"),a={width:"auto",height:"auto",lock:!1,hideClose:!1,closeAfter:0,openCallback:function(){},closeCallback:!1,hideOverlay:!1};return n.open=function(s){e=function(){n.center()},l.innerHTML=s.content&&!s.ajaxContent?s.content:"",o.style.width=a.width,o.style.height=a.height,n.center(),(a.lock||a.hideClose)&&(c.style.visibility="hidden"),a.hideOverlay||(d.style.visibility="visible"),o.style.visibility="visible",document.onkeypress=function(e){27===e.keyCode&&a.lock!==!0&&n.close()},c&&(c.onclick=function(){return a.hideClose?!1:void n.close()}),d&&(d.onclick=function(){return a.lock?!1:void n.close()}),window.addEventListener?(window.addEventListener("resize",e,!1),window.addEventListener("gestureend",function(){window.setTimeout("delayedpopModal.center()",250)},!1),window.addEventListener("orientationchange",function(){window.setTimeout("delayedpopModal.center()",250)},!1)):window.attachEvent&&window.attachEvent("onresize",e),i.onmousedown=function(){return!1},a.closeAfter>0&&(t=window.setTimeout(function(){n.close()},1e3*a.closeAfter)),a.openCallback&&a.openCallback()},n.close=function(){l.innerHTML="",d.setAttribute("style",""),d.style.cssText="",d.style.visibility="hidden",o.setAttribute("style",""),o.style.cssText="",o.style.visibility="hidden",i.style.cursor="default",c.setAttribute("style",""),c.style.cssText="",t&&window.clearTimeout(t),a.closeCallback&&a.closeCallback(),window.removeEventListener?window.removeEventListener("resize",e,!1):window.detachEvent&&window.detachEvent("onresize",e)},n.widget=function(){var e={id:"1bec0dae-9c4b-4b7c-b572-b9a075c7d550",d:"cGl4ZWxpbnRlbC5jb20=",wid:"49830",cb:(new Date).getTime()},t="";for(var n in e)t+=n+"="+e[n]+"&";t=t.substring(0,t.length-1);var d=document.createElement("script");d.type="text/javascript",d.src="http://api.content.ad/Scripts/widget.aspx?"+t,d.async=!0,document.getElementById("contentad49830").appendChild(d)},n.center=function(){var e=document.createElement("div");e.style.visibility="hidden",e.style.width="100px",e.style.msOverflowStyle="scrollbar",document.body.appendChild(e);var t=e.offsetWidth;e.style.overflow="scroll";var n=document.createElement("div");n.style.width="100%",e.appendChild(n);var i=n.offsetWidth;e.parentNode.removeChild(e);var l=t-i,c=Math.max(window.innerHeight,document.documentElement.scrollHeight),a=Math.max(o.clientWidth,o.offsetWidth),s=Math.max(o.clientHeight,o.offsetHeight),r=0,u=0,m=0,y=0;"number"==typeof window.innerWidth?(r=window.innerWidth-l,u=window.innerHeight):document.documentElement&&document.documentElement.clientWidth&&(r=document.documentElement.clientWidth-l,u=document.documentElement.clientHeight),"number"==typeof window.pageYOffset?(y=window.pageYOffset,m=window.pageXOffset):document.body&&document.body.scrollLeft?(y=document.body.scrollTop,m=document.body.scrollLeft):document.documentElement&&document.documentElement.scrollLeft&&(y=document.documentElement.scrollTop,m=document.documentElement.scrollLeft),o.style.top=Math.max(0,u/2-s/2)+"px",o.style.left=Math.max(0,r/2-a/2)+"px",d.style.height=u+"px",d.style.width="100%",navigator.userAgent.match(/iPad/i)&&(o.style.position="absolute",o.style.top=y+u/2-s/2+"px",o.style.left=m+r/2-a/2+"px",d.style.height=c+"px")},d.setAttribute("id","ac_delayedModal-overlay"),o.setAttribute("id","ac_delayedModal-container"),i.setAttribute("id","ac_delayedModal-header"),l.setAttribute("id","ac_delayedModal-content"),c.setAttribute("id","ac_delayedModal-close"),i.appendChild(c),o.appendChild(i),o.appendChild(l),d.style.visibility="hidden",o.style.visibility="hidden",document.body.appendChild(d),document.body.appendChild(o),n}();setTimeout("delayedpopModal.widget();",179e3),setTimeout("delayedpopModal_open();",18e4);
</script>

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
<?php endif ?>