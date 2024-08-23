<?php get_header(); ?>
	<?php
	//$today = date('Y-m-d');
	//$new_query = new WP_Query(array(
	//    'cat' => 3155,
	//    'posts_per_page' => 5,
	//    'orderby' => 'meta_value',
	//    'order' => 'ASC',
	//    'meta_query' => array(
	//        array(
	//            'key' => 'event_end_date',
	//            'value' => $today,
	//            'compare' => '>=',
	//            'type' => 'DATE'
	//        ), array(
	//            'key' => 'select_artist',
	//            'value' => $post->ID,
	//            'compare' => '=',
	//        )
	//    )
	//        ));
	////post event page// $new_query->posts
	//while (have_posts()) : the_post(); 
	    
	//    $cat = get_the_category();
	//    $cat_id = $cat[0]->term_id;
	  //  endwhile;
	    ?>

	  <?php
		$fields = get_fields();
	  	$banner_image = $fields['main_banner_artist_image']['url'];
		$biografia_des = get_field('biografia');
		$timeline_title = get_field('timeline_title');  
	    ?>
		<style>
			#category-header .cat-title {
				padding: 22% 7% 0 7%;
				text-transform: uppercase;
				font: 260% 'Raleway', sans-serif !important;
				width: 100%;
				vertical-align: middle;
				word-wrap: break-word;
			}
		</style>
	    <main class="embajadores">
		<div id="category-header" style="background-image:url('<?php echo $banner_image;?>')">
		    <div class="l-container l-flex">
		        <div class="cat-header-container">
		            <h1 class="cat-title"><?php the_title(); ?></h1> 
		            <div class="cat-description">
		                <p><?php the_field('profession'); ?><?php //echo do_shortcode('[breadcrumb]'); ?></p>
		            </div>
		           <div class="social-icon">
                        <ul> 
                        	<?php 
                        	$fb_link = get_field('fb_link');
                        	$twitter_link = get_field('twitter_link');
                        	$instagram_link = get_field('instagram_link');
                        	$website = get_field('website');
                        	?>
                        	<?php if($fb_link){ ?>
                            <li><a href="<?php echo $fb_link; ?>" target="_blank"><i class="fb"></i></a></li>
							<?php } ?>
							<?php if($twitter_link){ ?>
                            <li><a href="<?php echo $twitter_link; ?>" target="_blank"><i class="tw"></i></a></li>
							<?php } ?>
							<?php if($instagram_link){ ?>
                            <li><a href="<?php echo $instagram_link; ?>" target="_blank"><i class="insta"></i></a></li>
							<?php } ?>
							<?php if($website){ ?>
                            <li><a href="<?php echo $website; ?>" target="_blank"><i class="web"></i></a></li>
                            <?php } ?>
                    </div>
		        </div>
		    </div>
		</div><section>
		    <div id="content-wrapper" class="l-container l-flex">
		        <div class="content-box">
		            <div class="inner-box-over">
		                <div class="post-sharing">
		                    <a href="<?php echo  get_category_link(3453); ?>" class="btn btn-danger slide-cta hidden-xs">volver</a>
		                </div>
		            </div>
		            <div class="list-title">
		                <h2>Biografía</h2>
		            </div>
		            <div class="post-content" id="biografia_half_content">
						<?php echo $biografia_des;?>
					</div>
                            
		            <div class="list-title">
		                <h2><?php echo $timeline_title;?></h2>
		            </div>
		            <section class="page-section">
		                <div class="container">
		                    <div class="timeline" data-start-index="0">
		                        <div class="timeline__wrap">
		                            <div class="timeline__items">
		                                <?php
		                                $timeline_cnt1 = count(get_field('timeline'));
		                                if($timeline_cnt1 > 5){
		                                    $timeline_cnt = 5;
		                                }else{
		                                   $timeline_cnt = count(get_field('timeline')); 
		                                }
		                                if( have_rows('timeline') ):
		                                    $i=0;
		                                while ( have_rows('timeline') ) : the_row(); 
		                                 $active_Class = "";
		                                   if($i == 0){
		                                     $active_Class = "active";
		                                   }
		                                  $year = get_sub_field('year');
		                                  $timeline_image = get_sub_field('timeline_image');
										  $timeline_description = get_sub_field('timeline_description');
										  if($i%2 == 0){								  
		                                  ?>
		                                <div class="timeline__item <?php echo $active_Class;?>" id="historyitem<?php echo $i;?>">
		                                    <div class="timeline__content">
		                                        <p><?php echo $timeline_description;?></p>
		                                        <img src="<?php echo $timeline_image; ?>" >
		                                        <h2><?php echo $year;?></h2>
		                                    </div>
										</div>
										  <?php }else{?>
										  <div class="timeline__item <?php echo $active_Class;?>" id="historyitem<?php echo $i;?>">
		                                    <div class="timeline__content">
												<h2><?php echo $year;?></h2>
		                                        <p><?php echo $timeline_description;?></p>
		                                        <img src="<?php echo $timeline_image; ?>" >
		                                       
		                                    </div>
										</div>

										  <?php } ?>
		                                <?php $i++; endwhile;
		                                endif; ?> 
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </section>
					<div class="timeline_for_mobile">
						<div class="owl-carousel mobile_timeline">
							<?php 
							 if( have_rows('timeline') ):
							 while ( have_rows('timeline') ) : the_row();
							 	 		  $year = get_sub_field('year');
		                                  $timeline_image = get_sub_field('timeline_image');
										  $timeline_description = get_sub_field('timeline_description');
							   ?>
							<div class="timeline_cont item">
								 <p><?php echo $timeline_description;?></p>
								<h2><?php echo $year;?></h2>
								<div class="timeline_profileimg">
										<div class="profileimg" style="background-image:url('<?php echo $timeline_image; ?>')"></div>
								</div>		
							</div>
							<?php endwhile; endif;?>
							<!-- <div class="timeline_cont item">
								<p>Al disolverse la entonces exitosa banda Ekhymosis en 1997, Juanes decide avanzar como solista, primero con un EP de 1999, en principio las discográficas se negaban a patrocinarlo porque buscaban estilos musicales más corrientes y con menos rock, sin embargo el material llega a manos del productor Gustavo Santaolalla, quien ve en Juanes gran calidad y potencial, como resultado de esto firman contrato en 2000 y es así como Juanes lanza su primer disco de estudio como solista titulado: Fíjate bien.</p>
								<h2>2000</h2>
								<div class="timeline_profileimg">
										<div class="profileimg" style="background-image:url('<?php bloginfo('template_directory'); ?>/img/eventos-image.png') "></div>
								</div>		
							</div> -->
						</div>
					</div>
					<div class="timeline_for_desktop">
						<div class="owl-carousel desktop_timeline">
							<?php 
							 if( have_rows('timeline') ):
								$i=0;
							 while ( have_rows('timeline') ) : the_row();
							 	 		  $year = get_sub_field('year');
		                                  $timeline_image = get_sub_field('timeline_image');
										  $timeline_description = get_sub_field('timeline_description');
							 if ( $i % 2 == 0) 
							 {  
							   ?>

							<div class="timeline_cont item top">
								<p><?php echo $timeline_description;?></p>
								<div class="timeline_profileimg">
									<div class="profileimg" style="background-image:url('<?php echo $timeline_image; ?>')"></div>
								</div>
								<h2><?php echo $year;?></h2>	
							</div>
							<?php } else { ?>
								<div class="timeline_cont item bottom">
								<h2><?php echo $year;?></h2>
								<p><?php echo $timeline_description;?></p>
								<div class="timeline_profileimg">
									<div class="profileimg" style="background-image:url('<?php echo $timeline_image; ?>')"></div>
								</div>
									
							</div>
						<?php 	} 
							$i++;
							endwhile; endif;?>
						</div>
					</div>
		            
		            <?php 
		            $today = date('y-m-d');
		            $events_list = new WP_Query(array(
		               'cat' => 3155,
		               'posts_per_page' => 3,
		               'orderby' => 'meta_value',
		               'order' => 'ASC',
		               'meta_query' => array(
		                   array(
		                       'key' => 'event_end_date',
		                       'value' => $today,
		                       'compare' => '>=',
		                       'type' => 'DATE'
		                   ), 
		                   array(
		                       'key' => 'select_artist',
		                       'value' => $post->ID,
		                       'compare' => '=',
		                   )
		               )
		                   ));
		            if($events_list->have_posts()){
		            ?>

		            <div class="detailpart no-border">
		                <h2>Eventos</h2>
		            </div>
		           <?php } ?> 
		            <section class="eventos l-flex">
		                <?php while($events_list->have_posts()) : $events_list->the_post();

		                	if(get_field('event_link') == 'External'){
		                		$link =  get_field('external_link');
		                		$target = "target='_blank'";
		                	}else{
		                		$link =  get_permalink();
		                		$target = "";
		                	}?>
		                <article class="eventos-item" >
		                    <figure class="post-img">
		                        <a href="<?php echo $link; ?>" <?php echo $target; ?>>
		                            <img src="<?php the_field('event_thumb_image')?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="Destinos Colombia, Destinos, Viajes, Turismo">
		                        </a>
		                        <div class="post-category">
		                            <h2 class="hidden-xs"><a href="<?php echo $link; ?>" <?php echo $target; ?>><?php the_title(); ?></a></h2>	
		                            <a href="<?php echo $link ?>" class="orange-with-arrow" <?php echo $target; ?>>
		                                <span><?php the_field('event_start_date')?><i></i></span></a>
								</div>
								
		                    </figure>
		                    <div class="post-excerpt">
								<a href="<?php echo $link; ?>" <?php echo $target; ?>>
									<div class="excerpt-wrapper">
										<span class="read-more"><i class="icon-read-more"></i>lEER</span>
									</div>
								</a>
							</div>
							<div class="event_info_mobile">
									<p>Lorem ipsum dolor</p>
									<a href="#">IR A EVENTO</a>
								</div>
		                </article>
		                <?php endwhile; ?>
                                <div id="temp-posts"></div>
                         	<?php if($events_list->max_num_pages > 1){ ?>
					<div id="pagination">
					<button id="load-more1" class="btn-secondary" type="button" data-current-page="<?php echo $paged; ?>" data-total-pages="<?php echo $events_list->max_num_pages; ?>">Cargar más</button>
					</div>
					<?php } ?>                        
                            </section>
                            <?php wp_reset_postdata();?>
                             <?php $embeded_code =  get_field('embeded_code'); ?>
                             <?php if($embeded_code){?>
                             <section>
                                 <iframe src="<?php echo $embeded_code; ?>" height="356" width="800"></iframe>
                             </section>
                           <?php } ?>   
                                
		            <div class="detailpart no-border">
		                <h2>Otros embajadores</h2>
		            </div>
		            
		            <?php 
		            $args = array(
		            'posts_per_page' => 3, // How many items to display
		            'post__not_in'   => array( get_the_ID() ), // Exclude current post
		            'no_found_rows'  => true, // We don't ned pagination so this speeds up the query
		            'orderby'=>'rand'    
		            );
		        $cats = wp_get_post_terms( get_the_ID(), 'category' ); 
		        $cats_ids = array();  
		        foreach( $cats as $wpex_related_cat ) {
		            $cats_ids[] = $wpex_related_cat->term_id;
		            }
		        if ( ! empty( $cats_ids ) ) {
		        	$args['category__in'] = $cats_ids;
		            }
		        $wpex_query = new wp_query( $args ); ?>
		        <section id="country_brand" class="l-flex country_brand">
		        <?php foreach( $wpex_query->posts as $post ) : setup_postdata( $post ); 
		       $get_post_image = get_the_post_thumbnail_url();
		       
				?>
				
			<article class="category- post-28368 post type-post status-publish format-standard has-post-thumbnail hentry category-esta-es-colombia category-regiones-del-pais category-turismo category-turismo-por-regiones tag-colombia tag-regiones-de-colombia tag-turismo tag-viajes" id="post-28368">
		                    <?php if(! empty( $get_post_image )) {?>
		                    <figure class="post-img">
		                     <a href="<?php the_permalink(); ?>">
		                            <img src="<?php echo $get_post_image ;?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="Destinos Colombia, Destinos, Viajes, Turismo">						</a>
		                    </figure>
		                    <?php } ?>
		                    <div class="post-category">
		                        <ul class="post-categories">
		                            <li><a href="<?php the_permalink(); ?>" rel="category"><?php the_field('profession'); ?></a></li>
		                        </ul>					
		                    </div>
		                    <div class="post-title">
		                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		                    </div>
		                    <a href="<?php the_permalink(); ?>">
		                        <div class="post-excerpt">
		                            <div class="excerpt-wrapper">
										<p><?php echo get_the_excerpt();?></p>
		                                <span class="read-more"><i class="icon-read-more"></i><br>Ver más</span>
		                            </div>
		                        </div>
		                    </a>
		                </article>
		        <?php endforeach; ?>
		        </section>
		        </div>
		        <?php get_sidebar(); ?>
		    </div>
		</section>
	    </main>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/timeline.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/owl.carousel.min.js"></script>
	<script>
            
            var ajaxUrl = "<?php echo admin_url('admin-ajax.php')?>";
            var page = 0; // What page we are on.
            var ppp = 3; // Post per page

            $("#load-more1").on("click",function(){ // When Load More Button is pressed.

              event.preventDefault();

                $("#load-more1").attr("disabled",true); // Disable the button, temp.
                $.post(ajaxUrl, {
                    action:"more_post_ajax",
                    offset: (page * ppp) + 3,
                    ppp: ppp,
                    pid: '<?php echo $post->ID; ?>'
                }).success(function(posts){
                    page++;
                     if(posts === '') {
                      $("#load-more1").hide();
                    } else {
                      $("#posts").append(posts); // Which div to insert the posts
                    }
                    $("#load-more1").attr("disabled",false);
                });
			});

        function create_timeline(){
        	var current_width = $(window).width();
			var current_height = $(window).height();
			var default_item = 0;
			
			if(current_width > 1024){
				default_item = 5;
			}else if(current_width > 768){
				default_item = 3;
			}else if(current_width > 420){
				default_item = 2;
			}else{
				default_item = 1;
			}
			//console.log(default_item);
			timeline(document.querySelectorAll('.timeline'), {
				forceVerticalMode: 0,
				mode: 'horizontal',
				//verticalStartPosition: 'left',
				visibleItems: default_item,
				swipe:true,
				//horizontalStartPosition: "top"
			});
        }

	    

	    $(window).resize(function(){
	    	create_timeline();
	    });
			$(document).ready(function(){
				timeline(document.querySelectorAll('.timeline'), {
					forceVerticalMode: 0,
					//forceVerticalWidth: 800,
					mode: 'horizontal',
					//verticalStartPosition: 'left',
					visibleItems: <?php echo $timeline_cnt; ?>,
					swipe:true,
					//horizontalStartPosition: "top"
					//defaultValue: "top"
				});		
				
				$(".mobile_timeline").owlCarousel({
					items:1,
					nav:true,
				});
				$(".desktop_timeline").owlCarousel({
					nav:true,
					// /loop:true,
					autoWidth:true,
					//center:true,
					responsive:{
						// 0:{
						// 	items:1
						// },
						768:{
							items:2
						},
						992:{
							items:3
						},
						1201:{
							items:4
						}
					}
				});
				$('.timeline-nav-button--next').click(function(){
					//$('.timeline__item').removeClass('active');
					//$('.timeline__items .active').next().addClass('active');
				});

				$('.desktop_timeline .owl-item ').each(function(index){
					if(index == 0){
						$(this).addClass('current_active');
					}
				});
				$('.desktop_timeline .owl-item ').click(function(){
					$('.desktop_timeline .owl-item.active').removeClass('current_active');	
					$(this).addClass('current_active');
				});

			});

			$(document).on("click", ".timeline .timeline__item", function(){
				$('.timeline__item').removeClass('active');
				$(this).addClass('active');
			});
			
            function biografia_content(){
               $("#biografia_full_content").show();
               $("#biografia_half_content").hide();
            }         
	</script>
	<?php //endwhile; ?>
	<?php get_footer(); ?>