
<?php $__env->startSection('title', __('front.Sale Points')); ?>

<?php $__env->startSection('content'); ?>
<section class="sp2-page">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2><?php echo e(__('front.Sale Points')); ?></h2>
        </div>
    </div>

    <div class="sp2-head">
        <div class="sp2-brand"><?php echo e(__('front.Qdemy Cards')); ?></div>
        <div class="sp2-sub"><?php echo e(__('front.Cards available in the following libraries:')); ?></div>
    </div>

    <div class="examx-filters">
        <form method="GET" action="<?php echo e(route('sale-point')); ?>" class="examx-search">
            <input type="text" 
                   name="search" 
                   placeholder="<?php echo e(__('front.Search')); ?>" 
                   value="<?php echo e(request('search')); ?>">
            <button type="submit" style="background: none; border: none;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <div class="sp2-box">
        <?php if($posGrouped->count() > 0): ?>
            <?php $__currentLoopData = $posGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $countryName => $locations): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="sp2-group <?php echo e($loop->first ? 'is-open' : ''); ?>">
                    <button class="sp2-group-head">
                        <i class="fa-solid <?php echo e($loop->first ? 'fa-minus' : 'fa-plus'); ?>"></i>
                        <span><?php echo e($countryName); ?></span>
                    </button>
                    <div class="sp2-panel">
                        <table class="sp2-table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('front.Library Name')); ?></th>
                                    <th><?php echo e(__('front.Address')); ?></th>
                                    <th><?php echo e(__('front.Phone Number')); ?></th>
                                    <th><?php echo e(__('front.Location')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($pos->name); ?></td>
                                        <td><?php echo e($pos->address); ?></td>
                                        <td>
                                            <a href="tel:<?php echo e($pos->phone); ?>"><?php echo e($pos->phone); ?></a>
                                        </td>
                                        <td>
                                            <?php if($pos->google_map_link): ?>
                                                <a href="<?php echo e($pos->google_map_link); ?>" 
                                                   target="_blank" 
                                                   class="sp2-loc">
                                                    <i class="fa-solid fa-location-dot"></i> 
                                                    <?php echo e(__('front.Library Location')); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="sp2-loc" style="color: #ccc;">
                                                    <i class="fa-solid fa-location-dot"></i> 
                                                    <?php echo e(__('front.Location Not Available')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="no-results" style="text-align: center; padding: 40px; color: #666;">
                <i class="fa-solid fa-search" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                <h3><?php echo e(__('front.No Results Found')); ?></h3>
                <p><?php echo e(__('front.No sale points found matching your search criteria')); ?></p>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('sale-point')); ?>" class="btn btn-primary" style="margin-top: 20px; display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                        <?php echo e(__('front.Show All Sale Points')); ?>

                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/sale-point.blade.php ENDPATH**/ ?>