<?php
get_header();

    $td_archive_title = '';
    if (is_day()) {
        $td_archive_title .= __('Daily Archives:', 'newspaper' ) . ' ' . get_the_date();
    } elseif (is_month()) {
        $td_archive_title .= __('Monthly Archives:', 'newspaper') . ' ' . get_the_date('F Y');
    } elseif (is_year()) {
        $td_archive_title .= __('Yearly Archives:', 'newspaper') . ' ' . get_the_date('Y');
    } else {
        $td_archive_title .= __('Infographics', 'newspaper');
    }

?>

    <div class="td-main-content-wrap td-container-wrap">
        <div class="td-container">
        <div class="entry-crumbs td-crumb-container breadcrum"><span><a title="" class="tdb-entry-crumb" href="#">Home</a></span> <i class="td-icon-right td-bread-sep"></i> <span><?php echo esc_html( $td_archive_title ) ?></span> </div>

<!--            <div class="td-crumb-container">
                <?php // echo tagdiv_page_generator::get_breadcrumbs(array(
//                    'template' => 'archive',
//                )); ?>
            </div>-->
            <div class="td-pb-row archive_news" >
                <div class="td-pb-span12">
                <h1 class="entry-title td-page-title">
                    <span><?php echo esc_html( $td_archive_title ) ?></span>
                </h1>
                </div>
            </div>
            <div class="td-pb-row">
                <div class="td-pb-span12 td-main-content">
                    <div class="td-ss-main-content">
                        <?php get_template_part('loop-whitepapers'); ?>
                    </div>
                </div>

            <!--    <div class="td-pb-span4 td-main-sidebar">
                    <div class="td-ss-main-sidebar">
                        <?php //dynamic_sidebar( 'infographics_sidebar' ) ?>
                        <?php //dynamic_sidebar( 'td-default' ) ?>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

<?php get_footer();