<!-- In your Blade view file -->
<meta name="getGstRoute" content="{{ route('admin.getGst') }}">


<script type="text/javascript">
    var typeaheadElement;
    var selectedItemData;
    // Ensure jQuery is loaded before this script
    function initTypeaheadForElement(element) {


        var path = "{{ route('admin.autocomplete') }}";
        var type= '';
        var globalCounter;

        // $('input.typeahead').typeahead({
        // console.log(element);
        typeaheadElement = $(element);
        typeaheadElement.typeahead({
            hint: true,
            highlight: true,
            minLength: 3,
            source: function(query, process) {

                var elementId = $(element).data('name');
                // console.log(elementId);
                if (elementId === 'item_name') {
                    type = 'name';
                } else if (elementId === 'item_code') {
                    type = 'code';
                }    
    
                // console.log('Element ID:', this.$element.data-id);
                var elements = document.querySelectorAll('.table-responsive');

                setTimeout(() => {
                    Array.from(elements).forEach((e) => {
                        e.scrollTop = e.scrollHeight;
                    });
                }, 500);


                return $.get(path, {
                    query: query,
                    type: type,
                }, function(data) {
                    // console.log(data);
                    globalCounter = data.counter;
                    process(data.data);
                });

            },
            updater: function(item) {
                selectedItemData = item;
                // console.log(item);
                var counter = globalCounter;
                var name = this.$element.attr('name');
                var group = name.substring(0, name.indexOf('['));
                var index = name.match(/(\d+)/);
                index = index[0];
                var bill_to_state = $(".bill_to_state").val();
                var party_state = $(".party_state").val();
                var cgst_rate = sgst_utgst_rate = igst_rate = 0;
                var taxable_amount = item.mrp;
                var hsn_sac = item.hsncode_id;
                var sku = item.sku;
                var gst_percent = item.gst_percent;

                var item_code = '';

                if (item && item.consumer_desc) {
                    item_code = item.name;
                } else if (item && item.item_code) {
                    item_code = item.item_code;
                }

                var customer_id = $('#party_id').val();
                var inv_type = $('#inv_type').val()??'';

                if (customer_id || customer_id != '') {
                    if ($('#party_id').length != 0) {
                        $.ajax({
                            url: '{{ route('admin.get_customer') }}', // Replace with your actual API endpoint
                            method: 'GET',
                            data: {
                                customer_id: customer_id,
                                inv_type: inv_type,
                                'item_code': item_code,
                                'sku': sku
                            },
                            success: function(response) {
                                // alert(response);
                                if (response == -1) {
                                    // alert(`Please Set Pricing For Product ${item.name}`);
                                    Swal.fire({
                                        icon: "error",
                                        title: "Oops...",
                                        title: `Please Set Pricing For Product ${item.name}`,
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                    $("input[name='" + group + "[" + index +
                                            "][taxable_amount]']")
                                        .val('');
                                } else {
                                    $("input[name='" + group + "[" + index +
                                            "][taxable_amount]']")
                                        .val(response);
                                }
                            },
                            error: function() {
                                console.error('Error fetching customer data');
                            }
                        });
                    }


                    $("input[name='" + group + "[" + index + "][unit_pack]']").val(item.dimensions_unit_pack);
                    $("input[name='" + group + "[" + index + "][pack_case]']").val(item.unit_case);


                    // for default bacth number
                    var def_batch_no = counter + '-Batch-' + sku;
                    if ($("#def_batch_no").length) {
                        // $("#def_batch_no").val(def_batch_no);
                        $("input[name='" + group + "[" + index + "][batch_no]']").val(def_batch_no);
                        counter++;
                    }


                    $("input[name='" + group + "[" + index + "][hsn_sac]']").val(hsn_sac);
                    $("input[name='" + group + "[" + index + "][taxable_amount]']").val(taxable_amount);
                    $("input[name='" + group + "[" + index + "][mrp]']").val(taxable_amount);
                    $("select[name='" + group + "[" + index + "][gst_rate]']").val(item.gst_id);

                    if (name == '' + group + '[' + index + '][item_code]' || name == '' + group + '[' +
                        index + '][item_code]') {
                        $("input[name='" + group + "[" + index + "][item_name]']").val(item.consumer_desc);
                    } else if (name == '' + group + '[' + index + '][item_name]' || name ==
                        '' + group + '[' + index + '][item_name]') {
                        $("input[name='" + group + "[" + index + "][item_code]']").val(item.item_code);
                    }

                    if (bill_to_state == party_state) {
                        var divided_percent = gst_percent / 2;
                        $("input[name='" + group + "[" + index + "][cgst_rate]']").val(divided_percent);
                        $("input[name='" + group + "[" + index + "][sgst_utgst_rate]']").val(
                            divided_percent);
                        $("input[name='" + group + "[" + index + "][igst_rate]']").val(0);
                    } else if (bill_to_state != party_state) {
                        $("input[name='" + group + "[" + index + "][cgst_rate]']").val(0);
                        $("input[name='" + group + "[" + index + "][sgst_utgst_rate]']").val(0);
                        $("input[name='" + group + "[" + index + "][igst_rate]']").val(gst_percent);
                    } else {
                        $("input[name='" + group + "[" + index + "][cgst_rate]']").val(0);
                        $("input[name='" + group + "[" + index + "][sgst_utgst_rate]']").val(0);
                        $("input[name='" + group + "[" + index + "][igst_rate]']").val(0);
                    }
                    $("input[name='" + group + "[" + index + "][taxable_amount]']").trigger('change');


                    return item;
                } else {
                    // alert('Please Select Customer/Vendor First');
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Please Select Customer/Vendor First",
                        showConfirmButton: true,
                    });

                }
            }
        });
    }


    // Function to load the library
    function loadLibrary() {
        // Load the library (for example, Typeahead.js)
        $.getScript('{{ asset('public/backend-assets/js/bootstrap3-typeahead.min.js') }}', function() {
            // Initialize Typeahead or any other library here
            // initTypeaheadForElement(this);
            $('input.typeahead').each(function() {
                initTypeaheadForElement(this);
            });
        });
    }



    $(document).on('click', '.add_btn_rep', function() {
        updateSerialNumber();
        loadLibrary();

    });

    $(document).ready(function() {
        loadLibrary();
    });
</script>