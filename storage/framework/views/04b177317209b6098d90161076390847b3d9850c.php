<?php
use App\Models\Setting;
use App\Models\Page;

// Get settings data
$footerSettings = Setting::getSettings();

// Get privacy policy and terms pages
$privacyPolicy = Page::where('type', 2)->first(); // TYPE_PRIVACY_POLICY = 2
$termsConditions = Page::where('type', 1)->first(); // TYPE_TERMS_CONDITIONS = 1
?>

<footer class="footer">
    <div class="footer-container">

        <!-- Logo + Description -->
        <div class="footer-logo-section">
            <?php if($footerSettings): ?>
                <img src="<?php echo e($footerSettings->logo_url); ?>" alt="<?php echo e(config('app.name')); ?> Logo" class="footer-logo">
            <?php else: ?>
                <img src="<?php echo e(asset('assets_front/images/logo-white.png')); ?>" alt="<?php echo e(config('app.name')); ?> Logo" class="footer-logo">
            <?php endif; ?>
            
            <p class="footer-desc">
                <?php if($footerSettings && $footerSettings->text_under_logo_in_footer): ?>
                    <?php echo e($footerSettings->text_under_logo_in_footer); ?>

                <?php else: ?>
                    Lorem ipsum dolor sit amet consectetur. Porttitor molestie sapien dictum quam semper a sed auctor turpis.
                    Quam iaculis fringilla eros erat. Purus dui aliquet eget blandit enim nunc accumsan quis.
                <?php endif; ?>
            </p>

        </div>

        <!-- Column 1: Technical Support + Quick Links -->
        <div class="footer-column">
            <div>
                <h4><?php echo e(__('front.technical_support')); ?></h4>
                <ul>
                    <?php if($privacyPolicy): ?>
                        <li><a href="<?php echo e(route('page.privacy-policy')); ?>"><?php echo e(__('front.privacy_policy')); ?></a></li>
                    <?php endif; ?>
                    
                    <?php if($termsConditions): ?>
                        <li><a href="<?php echo e(route('page.terms-conditions')); ?>"><?php echo e(__('front.terms_conditions')); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div>
                <h4><?php echo e(__('front.quick_links')); ?></h4>
                <ul>
                    <li><a href="<?php echo e(route('international-programms')); ?>"><?php echo e(__('front.international_program')); ?></a></li>
                    <li><a href="<?php echo e(route('grades_basic-programm')); ?>"><?php echo e(__('front.basic_grades_program')); ?></a></li>
                    <li><a href="<?php echo e(route('universities-programm')); ?>"><?php echo e(__('front.universities_program')); ?></a></li>
                    <li><a href="<?php echo e(route('tawjihi-programm')); ?>"><?php echo e(__('front.tawjihi_program')); ?></a></li>
                    <li><a href="<?php echo e(route('packages-offers')); ?>"><?php echo e(__('front.packages_offers')); ?></a></li>
                </ul>
            </div>
        </div>

        <!-- Column 2: Contact Us + Follow Us -->
        <div class="footer-column">
            <div class="footer-column-Contact">
                <h4><?php echo e(__('front.contact_us')); ?></h4>
                <ul>
                    <?php if($footerSettings && $footerSettings->address): ?>
                        <li><?php echo e($footerSettings->address); ?></li>
                    <?php else: ?>
                        <li>Jordan – Amman</li>
                    <?php endif; ?>
                    
                    <?php if($footerSettings && $footerSettings->email): ?>
                        <li><?php echo e(__('front.technical_support_email')); ?>: <?php echo e($footerSettings->email); ?></li>
                    <?php else: ?>
                        <li><?php echo e(__('front.technical_support_email')); ?>: support@qdemy.com</li>
                    <?php endif; ?>
                    
                    <li><?php echo e(__('front.careers_email')); ?>: jobs@qdemy.com</li>
                    
                    <?php if($footerSettings && $footerSettings->phone): ?>
                        <li><?php echo e($footerSettings->phone); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div>
                <h4><?php echo e(__('front.follow_us_social_media')); ?></h4>
                <ul>
                    <li><a href="#" target="_blank"><?php echo e(__('front.facebook')); ?></a></li>
                    <li><a href="#" target="_blank"><?php echo e(__('front.instagram')); ?></a></li>
                    <li><a href="#" target="_blank"><?php echo e(__('front.twitter')); ?></a></li>
                    <li><a href="#" target="_blank"><?php echo e(__('front.youtube')); ?></a></li>
                </ul>
            </div>
        </div>

      

    </div>

   
</footer><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/layouts/footer.blade.php ENDPATH**/ ?>