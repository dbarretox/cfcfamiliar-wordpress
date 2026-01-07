<?php
/**
 * Generic Page Template
 *
 * @package CFC_Familiar
 */

get_header();
?>

    <!-- Hero Section -->
    <section class="relative h-[40vh] min-h-[300px] flex items-center justify-center overflow-hidden">
        <?php if (has_post_thumbnail()) : ?>
        <div class="absolute inset-0">
            <?php the_post_thumbnail('cfc-hero', array('class' => 'w-full h-full object-cover')); ?>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/70 to-gray-900/50"></div>
        <?php else : ?>
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>
        <?php endif; ?>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-4" data-aos="fade-up">
                <?php the_title(); ?>
            </h1>
        </div>
    </section>

    <!-- Content -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <?php while (have_posts()) : the_post(); ?>
                <article class="prose prose-lg max-w-none">
                    <?php the_content(); ?>
                </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
