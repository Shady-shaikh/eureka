<script type="text/javascript">
    // for select box
    $('#select-all-checkbox').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.select-row').prop('checked', isChecked);
    });
    $(document).on('change', '.select-row', function() {
        var allChecked = $('.select-row:checked').length === $('.select-row').length;
        $('#select-all-checkbox').prop('checked', allChecked);
    });


    function empty_totals() {
        $('.netTotal').val(0.0);
        $('.netTotal').text(0.0);
        $('.taxTotal').text(0.0);
        $('.taxTotal').val(0.0);
        $('.grossTotal').text(0.0);
        $('.grossTotal').val(0.0);
    }

    // Function to calculate and update the totals
    function updateTotals() {

        var netTotal = 0;
        var taxTotal = 0;
        var grossTotal = 0;
        // Loop through the selected rows (based on the checkboxes)
        $('input.select-row:checked').each(function() {
            var $row = $(this).closest('tr');
            var amount = parseFloat($row.find('td:eq(8)').text()); // Assuming column 7 contains the amount

            netTotal += amount;
            // taxTotal += (cgstAmount + sgstAmount + igstAmount);
            // grossTotal += totalValue;
        });
        // Update the content of the <span> elements
        $('.netTotal').text(netTotal.toFixed(2));
        $('.netTotal').val(netTotal.toFixed(2));

    }

    function get_series(party_id, edit_series_id = null) {
        // alert(party_id);
        if (party_id) {
            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ route('admin.get_series') }}', // Replace with your server endpoint
                data: {
                    party_id: party_id,
                },
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    if (data && typeof data === 'object' && Object.keys(data).length > 0) {
                        var html = '<option value="">Select Series Number</option>';
                        Object.entries(data).forEach(function([key, value]) {
                            if (edit_series_id == value) {
                                html += '<option value="' + value + '" selected>' + key +
                                    '</option>';
                                $("#series_no").html(html);
                            } else {
                                html += '<option value="' + value + '" >' + key + '</option>';
                                $("#series_no").html(html);
                            }
                        });
                    } else {
                        var html = '<option value="">Select Series Number</option>';
                        $("#series_no").html(html);
                        empty_totals();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors if needed
                    console.error(xhr, status, error);
                }
            });
        } else {
            $('#series_no').attr('readonly', 'readonly');
            $('#doc_number').attr('readonly', 'readonly');
            var series_number_def_opt = '<option value="">Select Series Number</option>';
            $('#series_no').html(series_number_def_opt);
            var doc_number_def_opt = '<option value="">Select Doc Number</option>';
            $('#doc_number').html(doc_number_def_opt);
            $('#table-body').empty();
            empty_totals();
        }
    }

    function get_doc_numbers(party_id, selectedTransactionType, edit_doc_no = null) {
        if (party_id && selectedTransactionType) {
            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ route('admin.get_doc_numbers') }}', // Replace with your server endpoint
                data: {
                    party_id: party_id,
                    series_no: selectedTransactionType
                },
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        // console.log(data);
                        var html = '<option value="">Select Doc Number</option>';
                        // $("#doc_number").html(html);
                        Object.entries(data).forEach(function([key, value]) {
                            if (edit_doc_no == key) {
                                html += '<option value="' + value + '" selected>' + key +
                                    '</option>';
                                $("#doc_number").html(html);
                            } else {
                                html += '<option value="' + value + '" >' + key + '</option>';
                                $("#doc_number").html(html);
                            }

                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors if needed
                    console.error(xhr, status, error);
                }
            });
        } else {
            var html = '<option value="">Select Doc Number</option>';
            $("#doc_number").html(html);
            $('#table-body').empty();
            empty_totals();
        }
    }

    function get_bill_bookings(selectedTransactionType, bill_booking_item_ids = null, netTotal = null, taxTotal = null,
        grossTotal = null) {
        // alert(selectedTransactionType);
        var check_boxes = '';
        if (bill_booking_item_ids != null) {
            check_boxes = bill_booking_item_ids.split(',');
        }

        if (selectedTransactionType) {
            // Make an AJAX request to fetch data based on the selected value
            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ route('admin.get_bill_bookings') }}', // Replace with your server endpoint
                data: {
                    doc_number: selectedTransactionType
                },
                dataType: 'json',
                success: function(data) {
                    if (netTotal) {
                        $('.netTotal').text(netTotal);
                        $('.netTotal').val(netTotal);
                        $('.taxTotal').text(taxTotal);
                        $('.taxTotal').val(taxTotal);
                        $('.grossTotal').text(grossTotal);
                        $('.grossTotal').val(grossTotal);
                    }

                    $('#table-body').empty();
                    data.forEach(function(item, index) {
                        // alert(data[0].billbooking_items.length);

                        // console.log(item);
                        // for all checkboxes
                        // console.log(check_boxes[index]);
                        if (check_boxes && data.length == check_boxes.length) {
                            $('#select-all-checkbox').prop('checked', true);
                        }

                        var newRow = '<tr>';

                        var check_box_check = '';
                        if (check_boxes.length == 1) {
                            check_box_check = check_boxes;
                        } else {
                            check_box_check = check_boxes[index];
                        }
                        // console.log(check_box_check);
                        // Add a checkbox column
                        if (check_boxes && item.billbooking_items[0].bill_booking_item_id ==
                            check_box_check) {
                            newRow +=
                                '<td><input type="checkbox" class="select-row" name="selected_rows[]" value="' +
                                item.billbooking_items[0].bill_booking_item_id + '" checked></td>';
                        } else {
                            newRow +=
                                '<td><input type="checkbox" class="select-row" name="selected_rows[]" value="' +
                                item.billbooking_items[0].bill_booking_item_id + '" ></td>';
                        }
                        // Add the "Sr No." column
                        newRow += '<td>' + (index + 1) + '</td>';
                        // Add other columns based on your data
                        newRow += '<td>' + item.doc_no + '</td>';
                        newRow += '<td>' + item.billbooking_items[0].description + '</td>';
                        newRow += '<td>' + item.billbooking_items[0].get_expense_name.expense_name +
                            '</td>';
                        newRow += '<td>' + item.billbooking_items[0].get_type.bspl_type_name +
                            '</td>';
                        newRow += '<td>' + item.billbooking_items[0].get_sub_cat.bspl_subcat_name +
                            '</td>';
                        newRow += '<td>' + item.billbooking_items[0].get_gl.gl_code + '</td>';
                        newRow += '<td>' + item.billbooking_items[0].amount + '</td>';

                        newRow += '</tr>';
                        // Append the new row to the table body
                        $('#table-body').append(newRow);


                        if (netTotal == '') {
                            $('#select-all-checkbox').prop('checked', true);
                            $('input.select-row').prop('checked', true);
                            updateTotals();
                        }

                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors if needed
                    console.error(xhr, status, error);
                }
            });
        } else {
            $('#table-body').empty();
            empty_totals();
        }
    }



    // Attach the updateTotals function to the change event of the checkboxes
    $(document).on('change', 'input.select-row', updateTotals);
    // Handle the "Select All" checkbox
    $('#select-all-checkbox').change(function() {
        var isChecked = $(this).prop('checked');
        $('input.select-row').prop('checked', isChecked).change();
        updateTotals(); // Call the updateTotals function when "Select All" is clicked
    });

    $('#party_id,#party_code').change(function() {
        var party_id = $(this).val();
        $('#table-body').empty();
        $('#series_no').removeAttr('readonly');
        $('#doc_number').removeAttr('readonly');
        var doc_number_def_opt = '<option value="">Select Doc Number</option>';
        $('#doc_number').html(doc_number_def_opt);

        // get_series(party_id);
        get_bill_bookings(party_id);

    });



    $(document).ready(function() {

        $('#party_id,#party_code').change(function() {
            var party_id = $(this).val();
            $('#table-body').empty();
            $('#series_no').removeAttr('readonly');
            $('#doc_number').removeAttr('readonly');
            var doc_number_def_opt = '<option value="">Select Doc Number</option>';
            $('#doc_number').html(doc_number_def_opt);

            // get_series(party_id);
            get_bill_bookings(party_id);

        });

        var party_id = $('#party_id').val();
        var edit_series_id = '{{ $model->series_no ?? '' }}';
        var edit_doc_no = '{{ $model->doc_no ?? '' }}';
        var bill_booking_item_ids = '{{ $model->bill_booking_item_ids ?? '' }}';

        // alert(bill_booking_item_ids);

        var netTotal = $('input.netTotal').val() == 0 ? '{{ $model->net_total ?? '' }}' : $('input.netTotal')
            .val();
        var taxTotal = $('input.taxTotal').val() == 0 ? '{{ $model->tax_total ?? '' }}' : $('input.taxTotal')
            .val();
        var grossTotal = $('input.grossTotal').val() == 0 ? '{{ $model->gorss_total ?? '' }}' : $(
            'input.grossTotal').val();


        if (netTotal) {
            $('.netTotal').text(netTotal);
            $('.netTotal').val(netTotal);
            $('.taxTotal').text(taxTotal);
            $('.taxTotal').val(taxTotal);
            $('.grossTotal').text(grossTotal);
            $('.grossTotal').val(grossTotal);
        }


        get_bill_bookings(party_id, bill_booking_item_ids);


        // Event listener for the bill booking items list which we get from doc number
        $('#doc_number').on('change', function() {
            var selectedTransactionType = $(this).val();
            // alert(selectedTransactionType);

            get_bill_bookings(selectedTransactionType, bill_booking_item_ids, netTotal, taxTotal,
                grossTotal);
        });

    });
</script>
