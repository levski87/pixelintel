<?php

global $bt_options;
$bt_options = get_option( BTPFX . '_theme_options' );

global $bt_page_options;
$bt_page_options = array();

if ( ! is_404() && ! is_search() && have_posts() ) {
	$tmp_bt_page_options = bt_rwmb_meta( BTPFX . '_override' );
}
if ( ! is_404() && ! is_search() && have_posts() && is_array( $tmp_bt_page_options ) ) {
	foreach ( $tmp_bt_page_options as $item ) {
		$item_key = substr( $item, 0, strpos( $item, ':' ) );
		$item_value = substr( $item, strpos( $item, ':' ) + 1 );
		$bt_page_options[ $item_key ] = $item_value;
	}
}

$html_class = '';
if ( bt_get_option( 'sticky_header' ) ) {
	$html_class = ' boldFixedHeader';
	if ( bt_get_option( 'centered_logo' ) ) {
		$html_class .= ' boldFixedHeaderCentered';
	}	
}

?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60567209-1', 'auto');
  ga('send', 'pageview');

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<!DOCTYPE html>
<html class="no-js<?php echo $html_class; ?>" <?php language_attributes(); ?>>
<head>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<meta name="google-site-verification" content="69v6uRanZ0y73l9HgMy49mTage6hs2D0pytoGCu9Apo" />
    <title><?php wp_title( '' ); ?></title>
	
	<?php
	
		$desc = bt_rwmb_meta( BTPFX . '_description' );
		
		if ( $desc != '' ) {
			echo '<meta name="description" content="' . $desc . '">';
		}
		
		if ( is_single() ) {
			echo '<meta property="twitter:card" content="summary">';

			echo '<meta property="og:title" content="' . get_the_title() . '" />';
			echo '<meta property="og:type" content="article" />';
			echo '<meta property="og:url" content="' . get_permalink() . '" />';
			
			$img = null;
			
			$bt_featured_slider = bt_get_option( 'blog_featured_image_slider' ) && has_post_thumbnail();
			if ( $bt_featured_slider ) {
				$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
				$img = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
				$img = $img[0];
			} else {
				$images = bt_rwmb_meta( BTPFX . '_images', 'type=image' );
				if ( is_array( $images ) ) {
					foreach ( $images as $img ) {
						$img = $img['full_url'];
						break;
					}
				}
			}
			if ( $img ) {
				echo '<meta property="og:image" content="' . $img . '" />';
			}
			
			if ( $desc != '' ) {
				echo '<meta property="og:description" content="' . $desc . '" />';
			}
		}
	
		$favicon = bt_get_option( 'favicon' );
		$mobile_touch_icon = bt_get_option( 'mobile_touch_icon' );
		
		if ( strpos( $favicon, '/wp-content' ) === 0 ) $favicon = get_site_url() . $favicon;
		if ( strpos( $mobile_touch_icon, '/wp-content' ) === 0 ) $mobile_touch_icon = get_site_url() . $mobile_touch_icon;
		
		if ( bt_get_option( 'favicon' ) != '' ) {
			echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '" type="image/x-icon">';
		}
		
		if ( bt_get_option( 'mobile_touch_icon' ) != '' ) {
			echo '<link rel="icon" href="' . esc_url( $mobile_touch_icon ) . '">';
			echo '<link rel="apple-touch-icon-precomposed" href="' . esc_url( $mobile_touch_icon ) . '">';
		}
		
		$facebook = '';
		if ( bt_get_option( 'contact_facebook' ) ) {
			$facebook = '<li><a href="' . esc_url( bt_get_option( 'contact_facebook' ) ) . '" data-icon="&#xf09a;"></a></li>';
		}
		$twitter = '';
		if ( bt_get_option( 'contact_twitter' ) ) {
			$twitter = '<li><a href="' . esc_url( bt_get_option( 'contact_twitter' ) ) . '" data-icon="&#xf099;"></a></li>';
		}
		$google_plus = '';
		if ( bt_get_option( 'contact_google_plus' ) ) {
			$google_plus = '<li><a href="' . esc_url( bt_get_option( 'contact_google_plus' ) ) . '" data-icon="&#xf0d5;"></a></li>';
		}
		$linkedin = '';
		if ( bt_get_option( 'contact_linkedin' ) ) {
			$linkedin = '<li><a href="' . esc_url( bt_get_option( 'contact_linkedin' ) ) . '" data-icon="&#xf0e1;"></a></li>';
		}
		$pinterest = '';
		if ( bt_get_option( 'contact_pinterest' ) ) {
			$pinterest = '<li><a href="' . esc_url( bt_get_option( 'contact_pinterest' ) ) . '" data-icon="&#xf0d2;"></a></li>';
		}
		$vk = '';
		if ( bt_get_option( 'contact_vk' ) ) {
			$vk = '<li><a href="' . esc_url( bt_get_option( 'contact_vk' ) ) . '" data-icon="&#xf189;"></a></li>';
		}
		$slideshare = '';
		if ( bt_get_option( 'contact_slideshare' ) ) {
			$slideshare = '<li><a href="' . esc_url( bt_get_option( 'contact_slideshare' ) ) . '" data-icon="&#xf1e7;"></a></li>';
		}
		$instagram = '';
		if ( bt_get_option( 'contact_instagram' ) ) {
			$instagram = '<li><a href="' . esc_url( bt_get_option( 'contact_instagram' ) ) . '" data-icon="&#xf16d;"></a></li>';
		}		
		$youtube = '';
		if ( bt_get_option( 'contact_youtube' ) ) {
			$youtube = '<li><a href="' . esc_url( bt_get_option( 'contact_youtube' ) ) . '" data-icon="&#xf167;"></a></li>';
		}
		$vimeo = '';
		if ( bt_get_option( 'contact_vimeo' ) ) {
			$vimeo = '<li><a href="' . esc_url( bt_get_option( 'contact_vimeo' ) ) . '" data-icon="&#xf194;"></a></li>';
		}
		
		$custom_text = '';
		if ( bt_get_option( 'custom_text' ) ) {
			$custom_text = bt_get_option( 'custom_text' );
		}
		
		$social_html = '';
		if ( $facebook != '' || $twitter != '' || $google_plus != '' || $linkedin != '' || $pinterest != '' || $vk != '' || $slideshare != '' || $instagram != '' || $youtube != '' || $vimeo != '' ) {
			$social_html = $facebook . $twitter . $google_plus . $linkedin . $pinterest . $vk . $slideshare . $instagram . $youtube . $vimeo;
		}
		
		$lang_selector = '';
		if ( function_exists( 'icl_get_languages' ) ) {
		$lang_arr = icl_get_languages( 'skip_missing=0&orderby=code' );
			if ( count( $lang_arr > 1 ) ) {
				$lang_selector = '<li class="lang">';

				foreach ( $lang_arr as $key => $lang ) {
					if ( $lang['active'] == 1 ) {
						$lang_selector .= '<a href="#">' . strtoupper( $lang['language_code'] ) . '</a>';
						unset ( $lang_arr[ $key ] );
						break;
					}
				}
				
				$lang_selector .= '<ul>';
				foreach ( $lang_arr as $key => $lang ) {
					$lang_selector .= '<li><a href="' . esc_url( $lang['url'] ) . '">' . strtoupper( $lang['language_code'] ) . '</a></li>';
				}
				$lang_selector .= '</ul>';
				$lang_selector .= '</li>';
			}
		}		
		
	?> 
	
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">

	<?php
		if ( is_singular() ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		$centered_logo_class = '';
		if ( bt_get_option( 'centered_logo' ) ) {
			$centered_logo_class = ' btr boldTwoRow';
		}

		wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="pageWrap">
	<header class="mainHeader<?php echo $centered_logo_class; ?>">
		<div class="blackBar">
			<div class="logo"><?php bt_logo(); ?></div>
			
			<div class="socNtools">
				<span class="toolsToggler"></span>
				<ul class="standAlone">
					<li class="search"><a href="#" data-icon="&#xf002;"></a></li>
				</ul>
				<ul class="sntList">
					<?php echo $social_html; ?>
				</ul>
			</div><!-- /socNtools -->
			<nav class="mainNav" role="navigation">
				<ul>
					<?php
						wp_nav_menu( array( 'theme_location' => 'primary', 'items_wrap' => '%3$s', 'container' => '', 'depth' => 3, 'fallback_cb' => false ));
					?>
				</ul>
			</nav>
		</div><!-- /blackBar -->
	</header><!-- /mainHeader -->

		<div class="ssPort" role="search">
			<span class="closeSearch"></span>
			<form action="/" method="get">
				<input type="text" name="s" value="<?php _e( 'Search term...', 'bt_theme' ); ?>">
			</form>
		</div><!-- /ssPort -->
	
	<?php
	
	if ( ( is_front_page() && bt_get_option( 'slider' ) ) || ( isset( $_GET['slider'] ) && $_GET['slider'] != '' ) ) {
		global $bt_home_slider;
		$bt_home_slider = true;
		get_template_part( 'php/slider' ); 
	}