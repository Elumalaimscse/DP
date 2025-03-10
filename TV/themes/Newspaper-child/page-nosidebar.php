<?php
/* Template Name: No Sidebar page template */
get_header(); ?>

    <div class="td-main-content-wrap td-container-wrap">
        <div class="td-container">

            <div class="td-pb-row" style="margin-top: 25px;">
                <div class="td-pb-span8 td-main-content">
                    <div class="td-ss-main-content">
                        <?php
                            if (have_posts()) {
                                while ( have_posts() ) : the_post();
                                    ?>
<!--                                    <div class="td-page-header">
                                        <h1 class="entry-title td-page-title">
                                            <span><?php the_title() ?></span>
                                        </h1>
                                    </div>-->
<div class="td-page-content tagdiv-type">
                                        <?php the_content(); ?>
                                    </div>
                            <?php endwhile;//end loop
                                comments_template('', true);
                            }
                        ?>
                    </div>
                </div>

                <div class="td-pb-span4 td-main-sidebar">
                
                    <div class="td-ss-main-sidebar">
                        <?php dynamic_sidebar( 'td-default' ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>