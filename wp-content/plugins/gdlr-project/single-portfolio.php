<?php 
	get_header(); 
	
	while( have_posts() ){ the_post();
?>
<div class="gdlr-content">

	<?php 
		global $gdlr_sidebar, $theme_option, $gdlr_post_option, $gdlr_is_ajax;
		
		if( empty($gdlr_post_option['sidebar']) || $gdlr_post_option['sidebar'] == 'default-sidebar' ){
			$gdlr_sidebar = array(
				'type'=>$theme_option['post-sidebar-template'],
				'left-sidebar'=>$theme_option['post-sidebar-left'], 
				'right-sidebar'=>$theme_option['post-sidebar-right']
			); 
		}else{
			$gdlr_sidebar = array(
				'type'=>$gdlr_post_option['sidebar'],
				'left-sidebar'=>$gdlr_post_option['left-sidebar'], 
				'right-sidebar'=>$gdlr_post_option['right-sidebar']
			); 				
		}
		$gdlr_sidebar = gdlr_get_sidebar_class($gdlr_sidebar);
		
		if( !empty($gdlr_post_option['port-page-style']) && $gdlr_post_option['port-page-style'] != 'default' ){
			$portfolio_page_style = $gdlr_post_option['port-page-style'];
		}else{
			$portfolio_page_style = $theme_option['portfolio-page-style'];
		}
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container gdlr-class-<?php echo $gdlr_sidebar['type']; ?>">
			<div class="with-sidebar-left <?php echo $gdlr_sidebar['outer']; ?> columns">
				<div class="with-sidebar-content <?php echo $gdlr_sidebar['center']; ?> columns">
					<div class="gdlr-item gdlr-portfolio-<?php echo $portfolio_page_style; ?> gdlr-item-start-content">
						<div id="portfolio-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php 
								if( $portfolio_page_style == 'style2' ){
									$thumbnail = gdlr_get_portfolio_thumbnail($gdlr_post_option, $theme_option['portfolio-thumbnail2-size']);
								}else{
									$thumbnail = gdlr_get_portfolio_thumbnail($gdlr_post_option, $theme_option['portfolio-thumbnail-size']);
								}
								$thumbnail_control = gdlr_get_portfolio_thumbnail_control($gdlr_post_option);
								if(!empty($thumbnail)){
									echo '<div class="gdlr-single-portfolio-thumbnail gdlr-portfolio-thumbnail ' . gdlr_get_portfolio_thumbnail_class($gdlr_post_option) . '">';
									echo $thumbnail;
									if( $portfolio_page_style == 'style2' ){
										echo $thumbnail_control;
									}
									echo '</div>';
								}
							?>
							<div class="gdlr-portfolio-content">
								<div class="gdlr-portfolio-info">
									<div class="content">
									<?php 
										echo '<div class="portfolio-info-wrapper">';
										echo gdlr_get_portfolio_info(array('clients', 'location', 'scope-of-work', 
											'schedule', 'architect', 'tag'), $gdlr_post_option, false); 
										echo '</div>';
										gdlr_get_social_shares();
									?>							
									</div>
								</div>								
								<div class="gdlr-portfolio-description">
									<?php 
										if( $portfolio_page_style == 'style1' ){
											echo $thumbnail_control;
										}
									?>
								
									<h4 class="head"><?php echo __('Project Detail', 'gdlr-portfolio'); ?></h4>
									<div class="content">
									<?php 
										the_content(); 
										wp_link_pages( array( 
											'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'gdlr-portfolio' ) . '</span>', 
											'after' => '</div>', 
											'link_before' => '<span>', 
											'link_after' => '</span>' ));
									?>
									</div>
									
									<?php 
										ob_start();
										previous_post_link('<span class="previous-nav">%link</span>', '<i class="icon-angle-left"></i>' . __('Previous Project', 'gdlr-portfolio'));
										$prev_nav = ob_get_contents();
										ob_end_clean();
										
										ob_start();
										next_post_link('<span class="next-nav">%link</span>', __('Next Project', 'gdlr-portfolio') . '<i class="icon-angle-right"></i>'); 
										$next_nav = ob_get_contents();
										ob_end_clean();
										
										if( !empty($prev_nav) || !empty($next_nav) ){
											echo '<nav class="gdlr-single-nav">';
											echo $prev_nav;
											if( !empty($prev_nav) && !empty($next_nav) ){
												echo '<span class="gdlr-single-nav-sep">/</span>';
											}
											echo $next_nav;
											echo '</nav>';
										}
									?>	
								</div>			
								<div class="clear"></div>
							</div>	
						</div><!-- #portfolio -->
						<?php //  ?>
						
						<div class="clear"></div>
						<?php 
							// print portfolio comment
							if( $theme_option['portfolio-comment'] == 'enable' ){
								comments_template( '', true ); 
							} 							
						?>		
					</div>
					
					<?php
						// print related portfolio
						if( !$gdlr_is_ajax && is_single() && $theme_option['portfolio-related'] == 'enable' ){	
							global $gdlr_related_section; $gdlr_related_section = true;
						
							$args = array('post_type' => 'portfolio', 'suppress_filters' => false);
							$args['posts_per_page'] = (empty($theme_option['related-portfolio-num-fetch']))? '3': $theme_option['related-portfolio-num-fetch'];
							$args['post__not_in'] = array(get_the_ID());
							
							$portfolio_term = get_the_terms(get_the_ID(), 'portfolio_tag');
							$portfolio_tags = array();
							if( !empty($portfolio_term) ){
								foreach( $portfolio_term as $term ){
									$portfolio_tags[] = $term->term_id;
								}
								$args['tax_query'] = array(array('terms'=>$portfolio_tags, 'taxonomy'=>'portfolio_tag', 'field'=>'id'));
							} 
							$query = new WP_Query( $args );
							
							if( $query->have_posts() ){
								echo '<div class="gdlr-related-portfolio portfolio-item-holder">';
								echo '<h4 class="head">' . __('Related Projects', 'gdlr-portfolio') . '</h4>';
								if( $theme_option['related-portfolio-style'] == 'classic-portfolio' ){
									global $gdlr_excerpt_length; $gdlr_excerpt_length = $theme_option['related-portfolio-num-excerpt'];
									add_filter('excerpt_length', 'gdlr_set_excerpt_length');

									echo gdlr_get_classic_portfolio($query, $theme_option['related-portfolio-size'], 
										$theme_option['related-portfolio-thumbnail-size'], 'fitRows' );
									
									remove_filter('excerpt_length', 'gdlr_set_excerpt_length');	
								}else{
									echo gdlr_get_modern_portfolio($query, $theme_option['related-portfolio-size'], 
										$theme_option['related-portfolio-thumbnail-size'], 'fitRows' );								
								}
								echo '<div class="clear"></div>';
								echo '</div>'; 
							}
							$gdlr_related_section = false;
						}					
					?>
				</div>
				<?php get_sidebar('left'); ?>
				<div class="clear"></div>
			</div>
			<?php get_sidebar('right'); ?>
			<div class="clear"></div>
		</div>				
	</div>				

</div><!-- gdlr-content -->
<?php
	}
	
	get_footer(); 
?>