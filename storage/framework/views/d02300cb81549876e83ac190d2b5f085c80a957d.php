
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
</script><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/export_pagination_script.blade.php ENDPATH**/ ?>