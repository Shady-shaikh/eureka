<?php
use App\Models\backend\YearManagement;
use App\Models\backend\Company;
use App\Models\backend\AdminUsers;
use App\Models\backend\Financialyear;

if (request()->is('api/*')) {
$user_id = request()->segment(3, 'default');

$user = AdminUsers::where('admin_user_id', $user_id)->first();
} else {
$user_id = Auth()
->guard('admin')
->user()->id;

$user = Auth()
->guard('admin')
->user();
}


?>


<!-- BEGIN: Header-->
<nav
	class="header-navbar navbar-expand-md navbar-dark navbar-with-menu navbar-static-top navbar-light navbar-border navbar-brand-center">
	<div class="navbar-wrapper">
		<div class="navbar-header">
			<ul class="nav navbar-nav flex-row">
				<li class="nav-item mobile-menu d-lg-none mr-auto">
					<a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
						<i class="feather icon-menu font-large-1"></i>
					</a>
				</li>
				<li class="nav-item mr-auto">
					<a class="navbar-brand" href="<?php echo e(route('admin.dashboard')); ?>">
						<img class="brand-logo" alt="logo"
							src="<?php echo e(asset('public/backend-assets/images/logo-demo.png')); ?>">
						<h2 class="brand-text">EUREKA</h2>
					</a>
				</li>
				<li class="nav-item d-none d-lg-block nav-toggle">
					<a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
						<i class="toggle-icon feather icon-toggle-right font-medium-3 white"
							data-ticon="feather.icon-toggle-right"></i>
					</a>
				</li>
				<li class="nav-item d-lg-none">
					<a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile">
						<i class="fa fa-ellipsis-v"></i>
					</a>
				</li>
			</ul>
		</div>
		<div class="navbar-container content">
			<div class="collapse navbar-collapse" id="navbar-mobile">
				<div class="mr-auto"></div>

				<?php

				if(session('fy_year') != 0 && session('company_id') != 0){
				$financial_year = Financialyear::where([
				'year' =>session('fy_year'),'company_id'=>session('company_id')])->first();

				// If the financial year does not exist, create a new entry and deactivate other financial years
				if (empty($financial_year)) {
				Financialyear::where('active', 1)->update(['active' => 0]);

				$financial_year = new Financialyear();
				$financial_year->year = session('fy_year');
				$financial_year->company_id = session('company_id');
				$financial_year->active = 1;
				$financial_year->save();
				}else{
				$financial_year->active = 1;
				$financial_year->save();
				}
				}


				$year_data = Financialyear::groupBy('year')->get(['year', 'year']);

				?>

				<div class="mr-auto text-white"><?php if(session()->has('fy_year')): ?>
					Year : <?php echo e(session('fy_year')); ?>

					<?php endif; ?></div>
				<div class="float-right">
					<select name="login_year" id="login_year" class='form-control'>
						<?php $__currentLoopData = $year_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cyear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($cyear['year']); ?>" <?php if(session('fy_year')==$cyear['year']): ?> Selected <?php endif; ?>><?php echo e($cyear['year']); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<ul class="nav navbar-nav float-right">
					<li class="dropdown dropdown-user nav-item">
						<a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">

							<div class="avatar avatar-online">
								<img src="<?php echo e(asset('public/backend-assets/app-assets/images/demo-avatar.webp')); ?>"
									alt="avatar"><i></i>
							</div>
							<?php
							$company = Company::where('company_id', session('company_id'))->first();
							?>
							
							<span class="user-name"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

								
							</span>

						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="<?php echo e(route('admin.profile')); ?>">
								<i class="feather icon-user"></i> Edit Profile
							</a>
							<a class="dropdown-item" href="<?php echo e(route('admin.change_password')); ?>">
								<i class="feather icon-check-square"></i> Change Password
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo e(route('admin.logout')); ?>">
								<i class="feather icon-power"></i> Logout
							</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>

<script>
	$('document').ready(function() {
        $('#login_year').change(function() {
            let login_year = $(this).val();
            $.ajax({
                url: '<?php echo e(url("admin/loginyear")); ?>'
                , type: 'post'
                , data: {
                    year: login_year
                    , _token: "<?php echo e(csrf_token()); ?>"
                , }
                , success: function(data) {
                    location.reload();
                }
            });
        });


        //$.extend($.fn.dataTable.defaults, {
        //    "pageLength": 75
        //});

    });
</script>
<!-- END: Header--><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/includes/header.blade.php ENDPATH**/ ?>