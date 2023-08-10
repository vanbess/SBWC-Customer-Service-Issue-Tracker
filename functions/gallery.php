<?php

/**
 * Gallery functionality for issues
 */
function sbwcit_gallery($post_id)
{

    // get gallery images
    $issue_gallery = maybe_unserialize(get_post_meta($post_id, 'issue_gallery', true));

    // if images present
    if (!empty($issue_gallery)) : ?>

        <div id="sbwcit-gallery-cont">

            <h4 style="font-style: italic;"><?php function_exists('pll_e') ? pll_e('Issue images:') : _e('Issue images:') ?></h4>

            <?php foreach ($issue_gallery as $img_url) : ?>

                <div class="sbwcit-add-gall-img col-20">

                    <div class="sbwcit-gall-img-cont">
                        <img src="<?php echo $img_url; ?>" alt="" style="width: 100%;">
                    </div>

                </div><!-- end .sbwcit-add-gall-img -->

            <?php endforeach; ?>
        </div><!-- end #sbwcit-gallery-cont -->


    <?php else : ?>

        <div id="sbwcit-gallery-cont">

            <h4 style="font-style: italic; text-transform: uppercase; text-decoration: underline;"><?php function_exists('pll_e') ? pll_e('Issue images:') : _e('Issue images:') ?></h4>

            <div class="sbwcit-add-gall-img">

                <!-- add/remove image -->
                <div class="sbwcit-add-rem-img">

                    <!-- img input -->
                    <div class="sbwcit-img-input">
                        <label for="sbwcit_gall_imgs" style="min-width: 140px;"><?php function_exists('pll_e') ? pll_e('Select gallery image:') : _e('Select gallery image:') ?></label>
                        <input type="file" name="sbwcit_gall_imgs[]" class="sbwcit_gall_imgs">
                    </div>

                    <!-- buttons -->
                    <div class="sbwcit-btns">
                        <button class="sbwcit-add-img button button-primary button-small" title="<?php function_exists('pll_e') ? pll_e('Add') : _e('Add') ?>">+</button>
                        <button class="sbwcit-rem-img rem-input button button-secondary button-small" title="<?php function_exists('pll_e') ? pll_e('Remove') : _e('Remove') ?>">-</button>
                    </div>

                </div>

            </div><!-- end .sbwcit-add-gall-img -->
        </div><!-- end #sbwcit-gallery-cont -->

<?php endif;
}
?>