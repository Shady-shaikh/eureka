
<?php $__env->startSection('title', 'Edit Business Partner'); ?>
<?php
use Spatie\Permission\Models\Role;
use App\Models\backend\Beat;
use App\Models\backend\AdminUsers;
use App\Models\backend\Country;
use App\Models\backend\Bpgroup;

$sales_manager_dep = AdminUsers::where('admin_user_id', $sales_manager->keys()->first())->first();
$ase_dep = AdminUsers::where('admin_user_id', $ase->keys()->first())->first();
$sales_officer_dep = AdminUsers::where('admin_user_id', $sales_officer->keys()->first())->first();
$salesman_dep = AdminUsers::where('admin_user_id', $salesman->keys()->first())->first();

$dep = Role::where('id',Auth()->guard('admin')->user()->role)->first();
$pricing_access = [1,2,5,11];

// dd($dep);

?>
<?php $__env->startSection('content'); ?>
<style>
    #emailList,
    #emailList1 {
        list-style-type: none;
        padding: 0;
    }

    .email-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .email-item-text {
        margin-right: 5px;
    }

    .remove-btn {
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: red;
        font-size: 1.2em;
    }

    .remove-btn:hover {
        color: darkred;
    }
</style>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Edit Business Partner</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a href=<?php echo e(route('admin.bussinesspartner.address', ['id'=> $model->business_partner_id])); ?>

                    class="btn btn-outline-primary" title="Address"><i class="feather icon-map"></i></a>
                <a href=<?php echo e(route('admin.bussinesspartner.contact', ['id'=> $model->business_partner_id])); ?>

                    class="btn btn-outline-primary" title="Contact"><i class="feather icon-mail"></i></a>
                <a href=<?php echo e(route('admin.bussinesspartner.banking', ['id'=> $model->business_partner_id])); ?>

                    class="btn btn-outline-primary" title="Banking"><i class="feather icon-dollar-sign"></i></a>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.bussinesspartner')); ?>">
                    <i class="feather icon-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo e(Form::open(['url' => 'admin/bussinesspartner/update/' . $model->business_partner_id])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('Business Partner Type', 'Business Partner Type *')); ?>


                                        <?php echo e(Form::select('business_partner_type', $bussiness_master_type,
                                        $model->business_partner_type, [
                                        'class' => 'form-control',
                                        'id' => 'business_partner_type',
                                        'placeholder' => 'Select Pricing Profile',
                                        'required' => true,
                                        ])); ?>


                                        <?php echo e(Form::hidden('business_partner_id', $model->business_partner_id, ['class' =>
                                        'form-control'])); ?>



                                    </div>
                                </div>

                                <!-- added to tagg distributor for thier bp only 28-02-2024 -->
                                <?php if(Auth()->guard('admin')->user()->role != 41): ?>
                                <div class="col-md-6 col-12 company_drp">
                                    <div class="form-group">
                                        <?php echo e(Form::label('company_id', 'Distributor *')); ?>

                                        <?php echo e(Form::select('company_id', $company, $model->company_id, [ 'class' =>
                                        'form-control', 'placeholder' => 'Select Distributor',])); ?>

                                    </div>
                                </div>
                                <?php else: ?>
                                <?php echo e(Form::hidden('company_id', Auth()->guard('admin')->user()->company_id ?? '', ['id' =>
                                'company_id'])); ?>

                                <?php endif; ?>


                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('bp_name', 'Business Partner Name *')); ?>

                                        <?php echo e(Form::text('bp_name', $model->bp_name, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Business Partner Name',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label(
                                        'Business Partner Organization Type',
                                        'Business Partner
                                        Organization Type *',
                                        )); ?>


                                        <?php echo e(Form::select('bp_organisation_type', $bpOrgType,
                                        $model->bp_organisation_type, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Business Partner Organization Type',
                                        'required' => true,
                                        ])); ?>


                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('residential_status', 'Residential status')); ?>

                                        <?php echo e(Form::select(
                                        'residential_status',
                                        DB::table('residential_status')->pluck('name','id'),
                                        $model->residential_status,
                                        ['class' => 'form-control',
                                        'placeholder' => 'Select Residential status'],
                                        )); ?>

                                    </div>
                                </div>


                                <div class="col-md-6 col-12 d-none bp_channel">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('bp_channel', 'Business Partner Channel *')); ?>

                                        <?php echo e(Form::select('bp_channel', $business_partner_category, $model->bp_channel,
                                        [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Business Partner Channel',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('bp_category', 'Business Partner Category *')); ?>

                                        <?php echo e(Form::select('bp_category', [], $model->bp_category,
                                        [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Business Partner Category',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12 d-none bp_group">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('bp_group', 'Business Partner group *')); ?>

                                        <?php echo e(Form::select('bp_group',
                                        [],
                                        $model->bp_group, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Select Business Partner Group',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12 d-none sm_dynamic">

                                    <div class="form-label-group">
                                        <?php echo e(Form::label('sales_manager', 'RKE *')); ?>

                                        <?php echo e(Form::select('sales_manager', $sales_manager, $model->sales_manager, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'RKE',
                                        ])); ?>

                                    </div>
                                </div>


                                <div class="col-md-6 col-12 d-none sm_dynamic">

                                    <div class="form-label-group">
                                        <?php echo e(Form::label('ase', 'KAM *')); ?>

                                        <?php echo e(Form::select('ase', [], $model->ase, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'KAM',
                                        ])); ?>

                                    </div>

                                </div>


                                <div class="col-md-6 col-12 d-none sm_dynamic">

                                    <div class="form-label-group">
                                        <?php echo e(Form::label('sales_officer', 'Sales Officer *')); ?>

                                        <?php echo e(Form::select('sales_officer', [], $model->sales_officer, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Sales Officer',
                                        ])); ?>

                                    </div>
                                </div>


                                <div class="col-md-6 col-12 d-none  not_for_subd">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('salesman', 'Salesman *')); ?>

                                        <?php echo e(Form::select('salesman', [], $model->salesman, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Salesman',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('terms_peyment', 'Terms of Payment *')); ?>

                                        <select name="payment_terms_id" id="payment_terms_id" class="form-control"
                                            placeholder='Select Terms of Payment' required>
                                            <?php $__currentLoopData = $termPayment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $terms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($terms->payment_terms_id); ?>" <?php if($model->payment_terms_id
                                                == $terms->payment_terms_id): ?> Selected = selected <?php endif; ?>>
                                                <?php echo e($terms->term_type); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 d-none show_credit_days">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('credit_limit', 'Credit Limit *')); ?>

                                        <?php echo e(Form::text('credit_limit', $model->credit_limit, ['class' => 'form-control',
                                        'placeholder' => 'Enter Days'])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('gst_details', 'GST Number')); ?>

                                        <?php echo e(Form::text('gst_details', $model->gst_details, [
                                        'class' => 'form-control',
                                        'placeholder' => 'GST Number',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('gst_reg_type', 'GST Registration Type')); ?>

                                        <?php echo e(Form::select('gst_reg_type',DB::table('gst_reg_type')->pluck('name','id'),
                                        $model->gst_reg_type, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select GST Registration Type',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('rcm_app', 'RCM Application')); ?>

                                        <?php echo e(Form::select('rcm_app', ['1' => 'Yes', '0' => 'No'], $model->rcm_app, [
                                        'class' => 'form-control',
                                        ])); ?>

                                    </div>
                                </div>


                                <div class="col-md-6 col-12 d-none shelf_left">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('shelf_life', 'Freshness Requirement')); ?>

                                        <?php echo e(Form::text('shelf_life', $model->shelf_life, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Freshness Requirement',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12 ">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('msme_reg', 'MSME registration')); ?>

                                        <?php echo e(Form::select('msme_reg', ['1' => 'Yes', '0' => 'No'], $model->msme_reg, [
                                        'class' => 'form-control',
                                        ])); ?>

                                    </div>
                                </div>



                                <?php if(in_array($dep->department_id,$pricing_access)): ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('pricing_profile', 'Pricing Profile')); ?>

                                        <?php echo e(Form::select('pricing_profile', [], $model->pricing_profile, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Pricing Profile',
                                        ])); ?>

                                    </div>
                                </div>
                                <?php endif; ?>

                                <hr>

                                
                                <?php if($model->business_partner_type == 1 && ($dep->department_id == 1 ||
                                $dep->department_id ==2)): ?>
                                <div class="col-md-12 col-12 mt-3 coordi">
                                    <h4><strong>Coordinates Details</strong></h4>
                                </div>

                                <div class="col-md-6 coordi">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('latitude', 'Latitude')); ?>

                                        <?php echo e(Form::text('latitude', $model->latitude??'', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Latitude',
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 coordi">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('longitude', 'Longitude')); ?>

                                        <?php echo e(Form::text('longitude', $model->longitude??'', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Longitude',
                                        ])); ?>

                                    </div>
                                </div>
                                <?php endif; ?>

                                
                                <div class="col-md-12 col-12 mt-3">
                                    <h4><strong>Address Details</strong></h4>
                                </div>
                                <div class="ml-3 mt-2 mb-2">
                                    
                                    <?php echo e(Form::checkbox('filladdress', null, null, ['id' => 'filladdress'])); ?>

                                    <span><b>Copy Same Address To Ship Address</b></span>
                                </div>
                                <div class="col-sm-12">

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class=" col-12">

                                                <select name="address_type" id="address_type"
                                                    class='form-control d-none'>
                                                    <option value="Bill-To/ Bill-From" selected>Bill-To/ Bill-From
                                                    </option>
                                                </select>

                                                <h4>Bill-To/ Bill-From</h4>
                                            </div>


                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('gst_no', 'GST Number ')); ?>

                                                    <?php echo e(Form::text('gst_no', $business_partner_address[0]->gst_no, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'GST Number',
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('bp_address_name', 'Address Name ')); ?>

                                                    <?php echo e(Form::text('bp_address_name',
                                                    $business_partner_address[0]->bp_address_name, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Address Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('building_no_name', 'Building No and Name ')); ?>

                                                    <?php echo e(Form::text('building_no_name',
                                                    $business_partner_address[0]->building_no_name, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Building No and Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('street_name', 'Street Name ')); ?>

                                                    <?php echo e(Form::text('street_name',
                                                    $business_partner_address[0]->street_name, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Street Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>
                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('landmark', 'Landmark ')); ?>

                                                    <?php echo e(Form::text('landmark', $business_partner_address[0]->landmark,
                                                    ['class' => 'form-control', 'placeholder' => 'Landmark'])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('country', 'Country')); ?>

                                                    <?php echo e(Form::select('country', Country::pluck('name', 'country_id'),
                                                    $business_partner_address[0]->country, [
                                                    'class' => 'form-control ',
                                                    'required' => true,
                                                    'placeholder' => 'Select Country',
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('state', 'State')); ?>

                                                    <?php echo e(Form::select('state', [], $business_partner_address[0]->state,
                                                    ['class' => 'form-control ', 'required' => true])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('district', 'District ')); ?>

                                                    <?php echo e(Form::select('district', [],
                                                    $business_partner_address[0]->district, ['class' => 'form-control ',
                                                    'required' => true])); ?>

                                                </div>
                                            </div>
                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('city', 'Name of City ')); ?>

                                                    <?php echo e(Form::text('city', $business_partner_address[0]->city, ['class'
                                                    => 'form-control', 'placeholder' => 'Name of City', 'required' =>
                                                    true])); ?>

                                                </div>
                                            </div>


                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('pin_code', 'Pin Code ')); ?>

                                                    <?php echo e(Form::number('pin_code', $business_partner_address[0]->pin_code,
                                                    [
                                                    'class' => 'form-control',
                                                    'onkeypress' => 'return event.charCode === 0
                                                    ||/\d/.test(String.fromCharCode(event.charCode));',
                                                    'placeholder' => 'Pin Code',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class=" col-12">



                                                <select name="address_type1" id="address_type1"
                                                    class='form-control d-none'>
                                                    <option value="Ship-To/ Ship-From" selected>Ship-To/ Ship-From
                                                    </option>
                                                </select>

                                                <h4>Ship-To/ Ship-From</h4>

                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('gst_no1', 'GST Number ')); ?>

                                                    <?php echo e(Form::text('gst_no1', $business_partner_address[1]->gst_no, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'GST Number',
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('bp_address_name1', 'Address Name ')); ?>

                                                    <?php echo e(Form::text('bp_address_name1',
                                                    $business_partner_address[1]->bp_address_name, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Address Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('building_no_name1', 'Building No and Name ')); ?>

                                                    <?php echo e(Form::text('building_no_name1',
                                                    $business_partner_address[1]->building_no_name, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Building No and Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('street_name1', 'Street Name ')); ?>

                                                    <?php echo e(Form::text('street_name1',
                                                    $business_partner_address[1]->street_name, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Street Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>
                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('landmark1', 'Landmark ')); ?>

                                                    <?php echo e(Form::text('landmark1', $business_partner_address[1]->landmark,
                                                    ['class' => 'form-control', 'placeholder' => 'Landmark'])); ?>

                                                </div>
                                            </div>
                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('country1', 'Country')); ?>

                                                    <?php echo e(Form::select('country1', Country::pluck('name', 'country_id'),
                                                    $business_partner_address[1]->country, [
                                                    'class' => 'form-control ',
                                                    'required' => true,
                                                    'placeholder' => 'Select Country',
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('state1', 'State')); ?>

                                                    <?php echo e(Form::select('state1', [], $business_partner_address[1]->state,
                                                    ['class' => 'form-control ', 'required' => true])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('district1', 'District ')); ?>

                                                    <?php echo e(Form::select('district1', [],
                                                    $business_partner_address[1]->district, ['class' => 'form-control ',
                                                    'required' => true])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('city1', 'Name of City ')); ?>

                                                    <?php echo e(Form::text('city1', $business_partner_address[1]->city, ['class'
                                                    => 'form-control', 'placeholder' => 'Name of City', 'required' =>
                                                    true])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('pin_code1', 'Pin Code ')); ?>

                                                    <?php echo e(Form::number('pin_code1', $business_partner_address[1]->pin_code,
                                                    [
                                                    'class' => 'form-control',
                                                    'onkeypress' => 'return event.charCode === 0
                                                    ||/\d/.test(String.fromCharCode(event.charCode));',
                                                    'placeholder' => 'Pin Code',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>





                                        </div>
                                    </div>

                                    

                                </div> 
                                <?php if(!$business_partner_contact->isEmpty()): ?>
                                <div class="col-md-12 col-12 mt-3">
                                    <h4><strong>Contact Details</strong></h4>
                                    <div class="ml-3 mt-2 mb-2">
                                        <?php echo e(Form::checkbox('fillcontactInfo', null, null, ['id' => 'fillcontactInfo'])); ?>

                                        <span><b>Copy Same Contact Details To Ship-To/Ship-From</b></span>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="col-sm-12">

                                    
                                    <?php if(!$business_partner_contact->isEmpty()): ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class=" col-12">
                                                <?php echo e(Form::select(
                                                'type',
                                                [
                                                'Bill-To/ Bill-From' => 'Bill-To/ Bill-From',
                                                'Ship-To/ Ship-From' => 'Ship-To/Ship-From',
                                                ],
                                                null,
                                                ['class' => 'form-control d-none', 'required' => true],
                                                )); ?>

                                                <h5>Bill-To/ Bill-From</h5>

                                            </div>
                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('contact_person', 'Contact Person Name')); ?>

                                                    <?php echo e(Form::text('contact_person',
                                                    $business_partner_contact[0]->contact_person??'', [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Contact Person Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('email_id', 'Email')); ?>

                                                    <?php echo e(Form::text('email_id', null, ['class' => 'form-control',
                                                    'placeholder' => 'Email'])); ?>

                                                    <?php echo e(Form::hidden('email_ids',
                                                    $business_partner_contact[0]->email_id??null, ['class' =>
                                                    'form-control', 'id' => 'email_ids'])); ?>


                                                </div>
                                                <ul id="emailList"></ul>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('mobile_no', 'Mobile No')); ?>

                                                    <?php echo e(Form::number('mobile_no',
                                                    $business_partner_contact[0]->mobile_no??'', [
                                                    'class' => 'form-control',
                                                    'onkeypress' => 'return event.charCode === 0
                                                    ||/\d/.test(String.fromCharCode(event.charCode));',
                                                    'placeholder' => 'Mobile No',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('landline', 'Landline')); ?>

                                                    <?php echo e(Form::text('landline',
                                                    $business_partner_contact[0]->landline??'', ['class' =>
                                                    'form-control', 'placeholder' => 'Landline'])); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class=" col-12">
                                                <?php echo e(Form::select(
                                                'type1',
                                                [
                                                'Ship-To/ Ship-From' => 'Ship-To/Ship-From',
                                                'Bill-To/ Bill-From' => 'Bill-To/ Bill-From',
                                                ],
                                                null,
                                                ['class' => 'form-control d-none', 'required' => true],
                                                )); ?>

                                                <h5>Ship-To/Ship-From</h5>

                                            </div>


                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('contact_person1', 'Contact Person Name')); ?>

                                                    <?php echo e(Form::text('contact_person1',
                                                    $business_partner_contact[1]->contact_person??null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Contact Person Name',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('email_id1', 'Email')); ?>

                                                    <?php echo e(Form::text('email_id1', null, ['class' => 'form-control',
                                                    'placeholder' => 'Email'])); ?>

                                                    <?php echo e(Form::hidden('email_ids1',
                                                    $business_partner_contact[1]->email_id??null, ['class' =>
                                                    'form-control', 'id' => 'email_ids1'])); ?>


                                                </div>
                                                <ul id="emailList1"></ul>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('mobile_no1', 'Mobile No')); ?>

                                                    <?php echo e(Form::number('mobile_no1',
                                                    $business_partner_contact[1]->mobile_no??null, [
                                                    'class' => 'form-control',
                                                    'onkeypress' => 'return event.charCode === 0
                                                    ||/\d/.test(String.fromCharCode(event.charCode));',
                                                    'placeholder' => 'Mobile No',
                                                    'required' => true,
                                                    ])); ?>

                                                </div>
                                            </div>

                                            <div class=" col-12">
                                                <div class="form-label-group">
                                                    <?php echo e(Form::label('landline1', 'Landline')); ?>

                                                    <?php echo e(Form::text('landline1',
                                                    $business_partner_contact[1]->landline??null, ['class' =>
                                                    'form-control', 'placeholder' => 'Landline'])); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="col-md-12 col-12 mt-3">
                                        <h4><strong>Banking Details</strong></h4>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('acc_holdername', 'Account Holder Name')); ?>

                                            <?php echo e(Form::text('acc_holdername',
                                            $business_partner_banking->acc_holdername??'', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Account Holder Name',
                                            ])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('bank_name', 'Bank Name')); ?>

                                            <?php echo e(Form::text('bank_name', $business_partner_banking->bank_name??'',
                                            ['class' => 'form-control', 'placeholder' => 'Bank Name'])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('bank_branch', 'Branch Name')); ?>

                                            <?php echo e(Form::text('bank_branch', $business_partner_banking->bank_branch??'', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Branch Name',
                                            ])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('ifsc', 'IFSC Code')); ?>

                                            <?php echo e(Form::text('ifsc', $business_partner_banking->ifsc??'', [
                                            'class' => 'form-control',
                                            'maxlength' => '15',
                                            'placeholder' => 'IFSC Code',
                                            ])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('ac_number', 'Account No')); ?>

                                            <?php echo e(Form::text('ac_number', $business_partner_banking->ac_number??'', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Account No',
                                            ])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('bank_address', 'Bank Address')); ?>

                                            <?php echo e(Form::text('bank_address', $business_partner_banking->bank_address??'', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Bank Address',
                                            ])); ?>

                                        </div>
                                    </div>

                                    

                                    <div class="col-md-12 col-12 mt-3 d-none  not_for_subd">
                                        <h4><strong>Beat Details</strong></h4>
                                    </div>





                                    <div class="col-md-6 col-12 d-none  not_for_subd">
                                        <div class="form-group">
                                            <?php echo e(Form::label('beat_id', 'Beat *')); ?>


                                            <?php echo e(Form::select('beat_id', Beat::pluck('beat_name', 'beat_id'),
                                            $model->beat_id??'', [
                                            'class' => 'form-control select2',
                                            'placeholder' => 'Select Beat',
                                            'required'=>true,
                                            ])); ?>


                                        </div>
                                    </div>


                                </div>






                            </div> 
                        </div>
                        <div class="col md-12 text-center mt-3">

                            <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1'])); ?>

                            <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>

                        </div>

                    </div>


                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>
    </div>
</section>




<div class="modal fade text-left" id="add_bp_cat_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Business Partner Channel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('bp_channel', 'Add Channel *')); ?>

                            <?php echo e(Form::text('bp_channel', null, [
                            'class' => 'form-control',
                            'id' => 'bp_category_modal',
                            'placeholder' => 'Business Partner Channel',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_bp_cat">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="add_bp_group_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Business Partner Group</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('bp_channel', 'Business Partner Channel *')); ?>

                            <?php echo e(Form::select('bp_channel', $business_partner_category, null, [
                            'class' => 'form-control ',
                            'id'=>'bp_channel_modal',
                            'placeholder' => 'Business Partner Channel',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('bp_group', 'Add Group *')); ?>

                            <?php echo e(Form::text('bp_group', null, [
                            'class' => 'form-control',
                            'id' => 'bp_group_modal',
                            'placeholder' => 'Business Partner Group',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_bp_group">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- area modal -->
<div class="modal fade text-left " id="add_area_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Area</h4>
                <button type="button" class="close btn_cut" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('area_name', 'Area Name *')); ?>

                            <?php echo e(Form::text('area_name', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Area
                            Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 btn_cut" id="submit_area">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- route modal -->
<div class="modal fade text-left" id="add_route_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Route</h4>
                <button type="button" class="close btn_cut" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('area_id', 'Select Area *')); ?>

                            
                            <?php echo e(Form::select('area_id', $area_data, null, [
                            'class' => 'form-control select2 area_id',
                            'placeholder' => 'Select Area',
                            ])); ?>


                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('route_name', 'Route Name *')); ?>

                            <?php echo e(Form::text('route_name', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Route
                            Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                </div>
                <div class="col-12 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary mr-1 mb-1 btn_cut" id="submit_route">Submit</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- beat modal -->
<div class="modal fade text-left " id="add_beat_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Beat</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('beat_name', 'Beat Name *')); ?>

                            <?php echo e(Form::text('beat_name', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Beat
                            Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>


                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('area_id', 'Select Area *')); ?>

                            <?php echo e(Form::select('area_id', $area_data, null, [
                            'class' => 'form-control select2 area_id',
                            'placeholder' => 'Select Area',
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('route_id', 'Select Route *')); ?>

                            <select name="route_id" id="route_id" class="form-control select2"></select>

                        </div>
                    </div>


                </div>
                <div class="col-12 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_beat">Submit</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="add_bp_cat_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Business Partner Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('bp_category', 'Add Category *')); ?>

                            <?php echo e(Form::text('bp_category', null, [
                            'class' => 'form-control',
                            'id' => 'bp_category_modal',
                            'placeholder' => 'Business Partner Category',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_bp_cat">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade text-left" id="add_sales_manager_modal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Sales Manager</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('first_name', 'First Name *')); ?>

                            <?php echo e(Form::text('first_name', null, [
                            'class' => 'form-control',
                            'id' => 'first_name_modal',
                            'placeholder' => 'First Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('last_name', 'Last Name *')); ?>

                            <?php echo e(Form::text('last_name', null, [
                            'class' => 'form-control',
                            'id' => 'last_name_modal',
                            'placeholder' => 'Last Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('email', 'Email *')); ?>

                            <?php echo e(Form::email('email', null, [
                            'class' => 'form-control',
                            'id' => 'email_modal',
                            'placeholder' => 'email',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1"
                            id="submit_sales_manager">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="add_ase_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add ASE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('first_name', 'First Name *')); ?>

                            <?php echo e(Form::text('first_name', null, [
                            'class' => 'form-control',
                            'id' => 'first_name_modal',
                            'placeholder' => 'First Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('last_name', 'Last Name *')); ?>

                            <?php echo e(Form::text('last_name', null, [
                            'class' => 'form-control',
                            'id' => 'last_name_modal',
                            'placeholder' => 'Last Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('email', 'Email *')); ?>

                            <?php echo e(Form::email('email', null, [
                            'class' => 'form-control',
                            'id' => 'email_modal',
                            'placeholder' => 'email',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('sales_manager', 'ASM *')); ?>

                            <?php echo e(Form::select('sales_manager', $sales_manager, null, [
                            'class' => 'form-control tags',
                            'id' => 'salesManager_ase',
                            'placeholder' => 'Sales Manager',
                            ])); ?>

                        </div>
                    </div>



                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_ase">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="add_sales_officer_modal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Sales Officer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('first_name', 'First Name *')); ?>

                            <?php echo e(Form::text('first_name', null, [
                            'class' => 'form-control',
                            'id' => 'first_name_modal',
                            'placeholder' => 'First Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('last_name', 'Last Name *')); ?>

                            <?php echo e(Form::text('last_name', null, [
                            'class' => 'form-control',
                            'id' => 'last_name_modal',
                            'placeholder' => 'Last Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('email', 'Email *')); ?>

                            <?php echo e(Form::email('email', null, [
                            'class' => 'form-control',
                            'id' => 'email_modal',
                            'placeholder' => 'email',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('ase', 'ASE *')); ?>

                            <?php echo e(Form::select('ase', $ase, null, [
                            'class' => 'form-control tags',
                            'id' => 'ase_salesoff',
                            'placeholder' => 'ASE',
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1"
                            id="submit_sales_officer">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="add_salesman_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Salesman</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('first_name', 'First Name *')); ?>

                            <?php echo e(Form::text('first_name', null, [
                            'class' => 'form-control',
                            'id' => 'first_name_modal',
                            'placeholder' => 'First Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('last_name', 'Last Name *')); ?>

                            <?php echo e(Form::text('last_name', null, [
                            'class' => 'form-control',
                            'id' => 'last_name_modal',
                            'placeholder' => 'Last Name',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('email', 'Email *')); ?>

                            <?php echo e(Form::email('email', null, [
                            'class' => 'form-control',
                            'id' => 'email_modal',
                            'placeholder' => 'email',
                            'required' => true,
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <?php echo e(Form::label('sales_officer', 'Sales Officer *')); ?>

                            <?php echo e(Form::select('sales_officer', $sales_officer, null, [
                            'class' => 'form-control tags',
                            'id' => 'salesOfficer',
                            'placeholder' => 'Sales Officer',
                            ])); ?>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_salesman">Submit</button>
                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php echo $__env->make('backend.multiple-email_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('public/backend-assets/js/MasterHandler.js')); ?>"></script>
<script src="<?php echo e(asset('public/backend-assets/js/DynamicDropdown.js')); ?>"></script>


<script>
    $(document).ready(function() {


            
   // usama_19-02-2024_get states

   var country = '<?php echo e($business_partner_address[0]->country); ?>';
   var state = '<?php echo e($business_partner_address[0]->state); ?>';
   var district = '<?php echo e($business_partner_address[0]->district); ?>';

   var country1 = '<?php echo e($business_partner_address[1]->country); ?>';
   var state1 = '<?php echo e($business_partner_address[1]->state); ?>';
   var district1 = '<?php echo e($business_partner_address[1]->district); ?>';

   var bp_channel = '<?php echo e($model->bp_channel); ?>';
   var bp_group = '<?php echo e($model->bp_group); ?>';

   if(bp_channel){
        new DynamicDropdown('<?php echo e(route('admin.getGroups')); ?>', 
        bp_channel, '#bp_group',bp_group);
   }

   if(country){
        new DynamicDropdown('<?php echo e(route('admin.getStates')); ?>', 
        country, '#state',state);
   }

   if(state){
        new DynamicDropdown('<?php echo e(route('admin.getCities')); ?>', 
        state, '#district',district);
   }


   if(country1){
        new DynamicDropdown('<?php echo e(route('admin.getStates')); ?>', 
        country1, '#state1',state1);
   }

   if(state1){
        new DynamicDropdown('<?php echo e(route('admin.getCities')); ?>', 
        state1, '#district1',district1);
   }

   // usama_15-03-2024-fetch bp-group from channel
   $('#bp_channel').change(function() {
        var selectedValue = $(this).val();
        new DynamicDropdown('<?php echo e(route('admin.getGroups')); ?>',
            selectedValue, '#bp_group')
        // fetch channel data in group modal
        fetchmodaldropdown('<?php echo e(route('admin.getChannels')); ?>',selectedValue,
        selectedValue,'#bp_channel_modal')
        
    });
    // if new channel added then trigger it in group modal
    $('#submit_bp_cat').click(function() {
        setTimeout(() => {
            // fetch channel data in group modal
            fetchmodaldropdown('<?php echo e(route('admin.getChannels')); ?>',$('#bp_channel').val(),
            $('#bp_channel').val(),'#bp_channel_modal')
        }, 1000);
       
    });

   $('#country').change(function() {       
   new DynamicDropdown('<?php echo e(route('admin.getStates')); ?>', 
   $(this).val(), '#state',null,'#district');
   });

   $('#state').change(function() {       
   new DynamicDropdown('<?php echo e(route('admin.getCities')); ?>', 
   $(this).val(), '#district',null);
   });

   $('#country1').change(function() {       
   new DynamicDropdown('<?php echo e(route('admin.getStates')); ?>', 
   $(this).val(), '#state1',null,'#district1');
   });

   $('#state1').change(function() {       
   new DynamicDropdown('<?php echo e(route('admin.getCities')); ?>', 
   $(this).val(), '#district1',null);
   });

});
</script>

<script>
    function fetchmodaldropdown(route,id,selectedValue,append_id,parent_id=null){
            var id = id;
            if(parent_id != null){
                id = parent_id;
            }
            $.ajax({
                    url: route,
                    type: 'get',
                    data: {
                        id: id,
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        var html = '';
                        for (var index in data) {
                            if (data.hasOwnProperty(index)) {
                                if(selectedValue == index) {
                                    html += '<option value="' + index + '" selected>' + data[index] + '</option>';
                                }else{
                                    html += '<option value="' + index + '">' + data[index] + '</option>';
                                }
                            }
                        }
                        $(append_id).html(html);
                    }
                });
        } 
    //fetch  asm->ase->sales officer->salesman dependent data and for modal also
        $(document).ready(function() {

            var salesManager = '<?php echo e($model->sales_manager); ?>';
            var ase = '<?php echo e($model->ase); ?>';
            var salesOfficer = '<?php echo e($model->sales_officer); ?>';
            var salesman = '<?php echo e($model->salesman); ?>';

            if(salesman){
               var manager_id = "<?php echo e($asm_users->parent_users??''); ?>";
               var ase_id = "<?php echo e($ase_users->parent_users??''); ?>";
               var officer_id = "<?php echo e($officer_users->parent_users??''); ?>";
               salesManager = manager_id;
               ase = ase_id;
               salesOfficer = officer_id;
               $('#sales_manager').val(manager_id).trigger('change');
               $('#ase').val(ase_id).trigger('change');
               $('#sales_officer').val(officer_id).trigger('change');
            }


            //get ase from asm
            if(salesManager){
                new DynamicDropdown('<?php echo e(route('admin.getAse')); ?>', 
                salesManager, '#ase',ase);
            }

            //get ase from asm
            $('#sales_manager').change(function() {     
                var selectedValue = $(this).val();

                new DynamicDropdown('<?php echo e(route('admin.getAse')); ?>', 
                selectedValue, '#ase',null,'#sales_officer','#salesman');
                // fetch asm data in ase modal
                fetchmodaldropdown('<?php echo e(route('admin.getAsm')); ?>','<?php echo e($sales_manager_dep->role ?? ''); ?>',
                  selectedValue,'#salesManager_ase')
            });

            // fetch asm data in ase modal and show default selected
            $('#submit_sales_manager').click(function(){
                setTimeout(() => {
                    fetchmodaldropdown('<?php echo e(route('admin.getAsm')); ?>','<?php echo e($sales_manager_dep->role ?? ''); ?>',
                        $('#sales_manager').val(),'#salesManager_ase')
                }, 500);
            });

            //get sales officers from ase
            if(ase){
                new DynamicDropdown('<?php echo e(route('admin.getSalesOfficers')); ?>', 
                ase, '#sales_officer',salesOfficer);
            }

            //get sales officers from ase
            $('#ase').change(function() {  
                var selectedValue = $(this).val();
                new DynamicDropdown('<?php echo e(route('admin.getSalesOfficers')); ?>', 
                 $(this).val(), '#sales_officer',null,'#salesman');

                 // fetch ase data in sales officer modal
                 fetchmodaldropdown('<?php echo e(route('admin.getAse')); ?>','<?php echo e($ase_dep->role ?? ''); ?>',
                        selectedValue,'#ase_salesoff',$('#sales_manager').val())
            });

            // fetch ase data in sales officer modal and show default selected
            $('#submit_ase').click(function(){
                setTimeout(() => {
                    fetchmodaldropdown('<?php echo e(route('admin.getAse')); ?>','<?php echo e($ase_dep->role ?? ''); ?>',
                        $('#ase').val(),'#ase_salesoff',$('#sales_manager').val())
                }, 800);
            });

            //get salesmans from sales officer
            if(salesOfficer){
                new DynamicDropdown('<?php echo e(route('admin.getSalesmen')); ?>', 
                salesOfficer, '#salesman',salesman);
            }

            //get salesmans from sales officer
            $('#sales_officer').change(function() {  
                var selectedValue = $(this).val();
                new DynamicDropdown('<?php echo e(route('admin.getSalesmen')); ?>', 
                 $(this).val(), '#salesman');

                // fetch sales officer data in salesman modal
                fetchmodaldropdown('<?php echo e(route('admin.getSalesOfficers')); ?>','<?php echo e($sales_officer_dep->role ?? ''); ?>',
                     selectedValue,'#salesOfficer',$('#ase').val())
            });

            // fetch sales officer data in salesman modal and show default selected
            $('#submit_sales_officer').click(function(){
                setTimeout(() => {
                    fetchmodaldropdown('<?php echo e(route('admin.getSalesOfficers')); ?>','<?php echo e($sales_officer_dep->role ?? ''); ?>',
                        $('#sales_officer').val(),'#salesOfficer',$('#ase').val())
                }, 800);
            });
           

            new MasterHandler('#bp_channel', '#add_bp_cat_modal', '#submit_bp_cat',
                '<?php echo e(url('admin/master/store_category')); ?>', '', '#bp_category_modal');

            new MasterHandler('#bp_group', '#add_bp_group_modal', '#submit_bp_group',
                '<?php echo e(url('admin/master/store_group')); ?>', null, '#bp_channel_modal','#bp_group_modal');

                
            var sales_manader_role = "<?php echo e($sales_manager_dep->role ?? ''); ?>";
            // for sales manager
            new MasterHandler('#sales_manager', '#add_sales_manager_modal', '#submit_sales_manager',
                '<?php echo e(url('admin/master/store_users')); ?>', sales_manader_role,
                '#first_name_modal',
                '#last_name_modal', '#email_modal');

            var ase_dep = "<?php echo e($ase_dep->role ?? ''); ?>";
            // for ase
            new MasterHandler('#ase', '#add_ase_modal', '#submit_ase',
                '<?php echo e(url('admin/master/store_users')); ?>', ase_dep,
                '#first_name_modal',
                '#last_name_modal', '#email_modal', '#salesManager_ase');

            var sales_officer_dep = "<?php echo e($sales_officer_dep->role ?? ''); ?>";
            // for sales officer
            new MasterHandler('#sales_officer', '#add_sales_officer_modal', '#submit_sales_officer',
                '<?php echo e(url('admin/master/store_users')); ?>', sales_officer_dep,
                '#first_name_modal',
                '#last_name_modal', '#email_modal', '#ase_salesoff');

            var salesman_dep = "<?php echo e($salesman_dep->role ?? ''); ?>";
            // for salesman
            new MasterHandler('#salesman', '#add_salesman_modal', '#submit_salesman',
                '<?php echo e(url('admin/master/store_users')); ?>', salesman_dep,
                '#first_name_modal',
                '#last_name_modal', '#email_modal', '#salesOfficer');

            new MasterHandler('.area_id', '#add_area_modal', '#submit_area',
                '<?php echo e(url('admin/master/store_area')); ?>', '',
                '#area_name');

            new MasterHandler('#route_id', '#add_route_modal', '#submit_route',
                '<?php echo e(url('admin/master/store_route')); ?>', '',
                '.area_id', '#route_name');

            new MasterHandler('#beat_id', '#add_beat_modal', '#submit_beat',
                '<?php echo e(url('admin/master/store_beat')); ?>', '',
                '#beat_name', '.area_id', '#route_id');



        });
</script>


<script>
    $(document).ready(function() {
            var bptype = $('#business_partner_type').find('option:selected').text().trim();
            var distributor = $('#company_id').find('option:selected').text().trim();
            
            var bp_category = '<?php echo e($model->bp_category); ?>';
            // if retails then remove requires attr from gst number
            if(bp_category == 2){
                $('.not_for_subd').removeClass('d-none');
                $('#gst_details').removeAttr('required');
                $('.coordi').removeClass('d-none');
            }else if(bp_category == 1){
                $('.not_for_subd').addClass('d-none');
                $('#beat_id').removeAttr('required');
                $('#gst_details').attr('required', true);
                $('.coordi').addClass('d-none');
            }else{
                $('#gst_details').attr('required', true);
                $('.coordi').addClass('d-none');
            }

            $('#bp_category').change(function() {
                if($(this).val() == 2){
                    $('.not_for_subd').removeClass('d-none');
                    $('#gst_details').removeAttr('required');
                    $('.coordi').removeClass('d-none');
                }else if($(this).val() == 1){
                    $('.not_for_subd').addClass('d-none');
                    $('#gst_details').attr('required', true);
                    $('.coordi').addClass('d-none');
                }else{
                    $('.not_for_subd').removeClass('d-none');
                    $('#gst_details').attr('required', true);
                    $('.coordi').addClass('d-none');
                }
            });

            if( $('#business_partner_type').val()){
                new DynamicDropdown('<?php echo e(route('admin.get_categories')); ?>',
                    $('#business_partner_type').val(), '#bp_category',<?php echo e($model->bp_category); ?>);
            }
            $('#business_partner_type').change(function() {
                // fetch categories dynamic
                new DynamicDropdown('<?php echo e(route('admin.get_categories')); ?>',
                    $(this).val(), '#bp_category');
            });

            if($('#company_id').val()){
                if ($('#business_partner_type').val()) {
                    if ($('#business_partner_type').val() == 1) {
                        showElements();
                        setTimeout(() => {
                            new DynamicDropdown('<?php echo e(route('admin.getPricingSale')); ?>', $('#company_id').val(), '#pricing_profile','<?php echo e($model->pricing_profile); ?>');
                        }, 500);
                    
                        } else {
                        hideElements();
                        setTimeout(() => {
                            new DynamicDropdown('<?php echo e(route('admin.getPricingPurchase')); ?>', $('#company_id').val(), '#pricing_profile','<?php echo e($model->pricing_profile); ?>');
                        }, 500);
                    }
                }
            }

            $('#company_id, #business_partner_type').change(function() {

                if ($('#business_partner_type').val()) {
                    if ($('#business_partner_type').val() == 1) {
                        showElements();
                        new DynamicDropdown('<?php echo e(route('admin.getPricingSale')); ?>', $('#company_id').val(), '#pricing_profile');
                    } else {
                        hideElements();
                        new DynamicDropdown('<?php echo e(route('admin.getPricingPurchase')); ?>', $('#company_id').val(), '#pricing_profile');
                    }
                }
            });


            function showElements() {

                $('.sm_dynamic, .shelf_left, .beat_det').removeClass('d-none');
                $('.bp_group, .bp_channel').removeClass('d-none');
                $('#bp_group,#bp_channel').attr('required', true);
            }

            function hideElements() {
                $('.sm_dynamic, .shelf_left, .beat_det').addClass('d-none');
                $('.bp_group, .bp_channel').addClass('d-none');
                $('#bp_group,#bp_channel,#beat_id').removeAttr('required');
            }

            if(distributor && bptype){
            if (bptype == 'Customer') {
                $('.sm_dynamic').removeClass('d-none');
                $('.shelf_left').removeClass('d-none');
                $('.beat_det').removeClass('d-none');
                new DynamicDropdown('<?php echo e(route('admin.getPricingPurchase')); ?>',
                    distributor, '#pricing_profile','<?php echo e($model->pricing_profile); ?>');
            } else {
                $('.sm_dynamic').addClass('d-none');
                $('.shelf_left').addClass('d-none');
                $('.beat_det').addClass('d-none');
                new DynamicDropdown('<?php echo e(route('admin.getPricingSale')); ?>',
                    distributor, '#pricing_profile','<?php echo e($model->pricing_profile); ?>');
            }
         }

            var terms_of_payment = $('#payment_terms_id').find('option:selected').text().trim();
            if (terms_of_payment == 'On Credit') {
                $('.show_credit_days').removeClass('d-none');
            } else {
                $('.show_credit_days').addClass('d-none');
            }
        });

        // $('#business_partner_type').on('change', function() {
        //     var bptype = $(this).find('option:selected').text().trim();
        //     if (bptype == 'Customer') {
        //         $('.sm_dynamic').removeClass('d-none');
        //         $('.shelf_left').removeClass('d-none');
        //         $('.beat_det').removeClass('d-none');
        //         new DynamicDropdown('<?php echo e(route('admin.getPricing')); ?>',
        //             'sale', '#pricing_profile');
        //     } else {
        //         $('.sm_dynamic').addClass('d-none');
        //         $('.shelf_left').addClass('d-none');
        //         $('.beat_det').addClass('d-none');
        //         new DynamicDropdown('<?php echo e(route('admin.getPricing')); ?>',
        //             'purchase', '#pricing_profile');
        //     }
        // });

        $('#payment_terms_id').on('change', function() {
            var terms_of_payment = $(this).find('option:selected').text().trim();
            if (terms_of_payment == 'On Credit') {
                $('.show_credit_days').removeClass('d-none');
            } else {
                $('.show_credit_days').addClass('d-none');
            }
        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/bussinesspartner/edit.blade.php ENDPATH**/ ?>