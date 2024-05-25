{{-- apply pagination and export in every list --}}
<script>
    $('#tbl-datatable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        exportOptions: {
                            columns: ':visible:not(.no_export)'
                        },
                        autoSize: true // Automatically adjust column width
                    }
                ],
                paging: true, // Enable pagination
                pageLength: 10  
    });
</script>