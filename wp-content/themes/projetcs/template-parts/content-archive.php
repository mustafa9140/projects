<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package projetcs
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if (is_singular()) :
			the_title('<h1 class="entry-title">', '</h1>');
		else :
			the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
		endif;

		if ('post' === get_post_type()) :
		?>
			<div class="entry-meta">
				<?php
				projetcs_posted_on();
				projetcs_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php projetcs_post_thumbnail(); ?>

	<div class="entry-content">

		<!--  -->


		<div>
			<hr>
			<h2>Pagination for custom post type</h2>
			<?php
			wp_reset_postdata();
			$the_query = new WP_Query(
				array(
					'posts_per_page' => 6,
					'post_type' => 'projects',
					'paged' => get_query_var('paged') ? get_query_var('paged') : 1
				)
			);
			?>
			<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
				<div class="col-xs-12 file">
					<a href="<?php the_permalink(); ?>" class="file-title" target="_blank">
						<i class="fa fa-angle-right" aria-hidden="true"></i> <?php echo get_the_title(); ?>
					</a>
					<div class="file-description"><?php the_content(); ?></div>
				</div>
			<?php
			endwhile;

			$big = 999999999; // need an unlikely integer
			echo paginate_links(array(
				'base' => str_replace($big, '%#%', get_pagenum_link($big)),
				'format' => '?paged=%#%',
				'current' => max(1, get_query_var('paged')),
				'total' => $the_query->max_num_pages
			));

			wp_reset_postdata();
			?>
			<!--  -->
			<hr>
		</div>
		<div>
			<?php

			if (is_user_logged_in()) {
				echo "<h2>Logged In - Latest Six Projects of Architecture Type from Custom End Point : </h2>";
				$request = wp_remote_get('http://localhost/ikonic/wp-json/projects/architecture/1');
			} else {
				echo "<h2>Logged Out - Latest Three Projects of Architecture Type from Custom End Point : </h2>";
				$request = wp_remote_get('http://localhost/ikonic/wp-json/projects/architecture');
			}
			if (!empty($request)) {
				$data = json_decode($request['body'], TRUE);
				foreach ($data['data'] as $project) {
					echo "<b>Title:</b> ".$project['title'] . ", ";
					echo "<b>ID:</b>: ".$project['id'] . ", ";
					echo "<b>Link:</b> ".$project['link'] . "<br> ";
				}
			}
			?>
			<hr>
		</div>
		<div>
			<h2>Coffee API Image</h2>
			<?php
			echo do_shortcode('[GetCoffee]');
			?>
			<hr>
		</div>
		<div>
			<h2>Kanye Quotes API</h2>
			<?php
			echo do_shortcode('[KanyeQuotes]');
			?>
			<hr>
		</div>
		<!--  -->
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'projetcs'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'projetcs'),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php projetcs_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->