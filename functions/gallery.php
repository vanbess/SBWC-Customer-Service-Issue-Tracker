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

            <h4><?php pll_e('Issue images:') ?></h4>

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

            <h4><?php pll_e('Issue images:') ?></h4>

            <div class="sbwcit-add-gall-img col-20">

                <!-- add/remove image -->
                <div class="sbwcit-add-rem-img">
                    <span class="sbwcit-add-img" title="<?php pll_e('Add') ?>">+</span>
                    <span class="sbwcit-rem-img rem-input" title="<?php pll_e('Remove') ?>">-</span>
                </div>

                <label for="sbwcit_gall_imgs"><?php pll_e('Select gallery image:') ?></label>
                <input type="file" name="sbwcit_gall_imgs[]" id="sbwcit_gall_imgs_1" class="sbwcit_gall_imgs">

            </div><!-- end .sbwcit-add-gall-img -->
        </div><!-- end #sbwcit-gallery-cont -->

<?php endif;
}

?>