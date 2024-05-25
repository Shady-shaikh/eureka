@extends('backend.layouts.app')
@section('title', 'PRICING LADDER')


@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">PRICING LADDER : {{$pricing->pricing_name}}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a href="{{ route('admin.pricingladder') }}" class="btn btn-outline-secondary">Back</a>

        </div>
    </div>
</div>

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-sm-12">
            <div class="card">
                <h4 class="px-2 py-1">{{$pricing->pricing_name}}</h4>
                <div class="card-content">
                    <div class="card-body table-responsive">



                        <table class="table table-bordered table-striped" id="tbl-datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>UAPL Code</th>
                                    <th>Product Desc</th>
                                    <th>Tag</th>
                                    <th>Channel</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Format</th>
                                    <th>Variant</th>
                                    <th>Format Pack Size</th>
                                    <th>Source Country</th>
                                    <th>Case/ Pallet</th>
                                    <th>Layer/ Pallet</th>
                                    <th>Units per Case</th>
                                    <th>Final Selling Price to Distributor</th>
                                    <th>Distributor margin (%)</th>
                                    <th>Distributor margin</th>
                                    <th>GST on total value addition</th>
                                    <th>Net billing price of Distributor</th>
                                    <th>TTS %</th>
                                    <th>TTS (w/o GST)</th>
                                    <th>TTS after Scheme %</th>
                                    <th>TTS (w/o GST) after Scheme</th>
                                    <th>Actual DT Margin (Net-Off Sub D excl. GST)</th>
                                    <th>Diff In Margin (Balance with DT)</th>
                                    <th>Sub-D Margin (Absolute excl GST)</th>
                                    <th>Sub-D Landing</th>
                                    <th>Sub-D margin (%)</th>
                                    <th>PTR after Sch</th>
                                    <th>Scheme (MD)</th>
                                    <th>Scheme (%)</th>
                                    <th>PTR</th>
                                    <th>Retailer Margin (%)</th>
                                    <th>Retailer Margin</th>
                                    <th>Derived MRP</th>
                                    <th>Intended MRP (Excl GST)</th>
                                    <th>Intended MRP</th>
                                </tr>
                            </thead>
                            <tbody>



                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
</div>




@endsection

@section('scripts')
<script src="{{ asset('public/backend-assets/js/DynamicDropdown.js') }}"></script>


<script>
    var table = $('#tbl-datatable').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: "{{ route('admin.pricingladder.form',['id'=>request('id')]) }}", // Replace with your server-side endpoint
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'item_code',
                    name: 'item_code'
                },
                {
                    data: 'product_desc',
                    name: 'product_desc'
                },
                {
                    data: 'visibility',
                    name: 'visibility'
                },
                {
                    data: 'bp_channel',
                    name: 'bp_channel'
                },

                {
                    data: 'brand_id',
                    name: 'brand_id'
                },
                {
                    data: 'category_id',
                    name: 'category_id'
                },
                {
                    data: 'sub_category_id',
                    name: 'sub_category_id'
                },
                {
                    data: 'variant',
                    name: 'variant'
                },
                {
                    data: 'buom_pack_size',
                    name: 'buom_pack_size'
                },
                {
                    data: 'sourcing',
                    name: 'sourcing'
                },
                {
                    data: 'case_pallet',
                    name: 'case_pallet'
                },
                {
                    data: 'layer_pallet',
                    name: 'layer_pallet'
                },
                {
                    data: 'unit_case',
                    name: 'unit_case'
                },
                {
                    data: 'final_sellp_dist',
                    name: 'final_sellp_dist'
                },
                {
                    data: 'dist_margin_perc',
                    name: 'dist_margin_perc'
                },
                {
                    data: 'dist_margin',
                    name: 'dist_margin'
                },
                {
                    data: 'gst_total_val_add',
                    name: 'gst_total_val_add'
                },
                {
                    data: 'net_bill_price_dist',
                    name: 'net_bill_price_dist'
                },
                {
                    data: 'tts',
                    name: 'tts'
                },
                {
                    data: 'tts_wo_gst',
                    name: 'tts_wo_gst'
                },
                {
                    data: 'tts_aft_sch',
                    name: 'tts_aft_sch'
                },
                {
                    data: 'tts_wo_gst_af_scheme',
                    name: 'tts_wo_gst_af_scheme'
                },
                {
                    data: 'actual_dt_margin',
                    name: 'actual_dt_margin'
                },
                {
                    data: 'diff_in_margin',
                    name: 'diff_in_margin'
                },
                {
                    data: 'sub_d_margin_abs_exc',
                    name: 'sub_d_margin_abs_exc'
                },
                {
                    data: 'sub_d_landing',
                    name: 'sub_d_landing'
                },
                {
                    data: 'sub_d_margin',
                    name: 'sub_d_margin'
                },
                {
                    data: 'ptr_af_sch',
                    name: 'ptr_af_sch'
                },
                {
                    data: 'scheme_md',
                    name: 'scheme_md'
                },
                {
                    data: 'scheme',
                    name: 'scheme'
                },
                {
                    data: 'ptr',
                    name: 'ptr'
                },
                {
                    data: 'margin',
                    name: 'margin'
                },
                {
                    data: 'retailer_margin',
                    name: 'retailer_margin'
                },
                {
                    data: 'derived_mrp',
                    name: 'derived_mrp'
                },
                {
                    data: 'intended_mrp_exc',
                    name: 'intended_mrp_exc'
                },
                {
                    data: 'mrp',
                    name: 'mrp'
                },
               
            ],
            buttons: [{
                extend: 'excel',
                exportOptions: {
                  columns: ':visible:not(.no_export)'
                },
                title: 'Pricing Ladder Data',               
            }],
            dom: 'lBfrtip',
            select: true,
            lengthMenu: [
                [-1],
                ['All']
            ],
            paging: true,
            pageLength: 10
    });

    function get_data(pricing_master_id){
        $.get('{{ route('admin.margin.fetch_margin') }}', {
            pricing_master_id: pricing_master_id,
            }, function(data) {
                var jsonData = JSON.parse(data);
                if(jsonData.length > 0){
                    // Loop through the received data and update margin text
                    jsonData.forEach(function(item) {
                        setTimeout(() => {
                            // Find the corresponding row with matching brand and sub-category IDs
                            var row = $('#tbl-datatable tbody').find('tr').filter(function() {
                                return $(this).find('td:eq(1)').text() == item.brand.brand_name && $(this).find('td:eq(2)').text() == item.format.subcategory_name;
                            });
                            // Update the margin text in the corresponding cell
                            row.find('td:eq(3)').text(item.margin);
                        }, 500);

                    });
                }else{
                    //reset all text of margins
                    $('#tbl-datatable tbody td:nth-child(4)').text(0);
                }
        });
    }

    var pricing_master_id = "{{ $pricing->pricing_master_id ?? null }}";
    if(pricing_master_id){
        get_data(pricing_master_id);
    }

    
   
</script>

@endsection