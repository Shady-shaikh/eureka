<script type="text/javascript">
function inittypeahead()
{
    var path = "{{ route('autocomplete') }}";

    $('input.typeahead').typeahead(

    {
        hint: true,
        highlight: true,
        minLength: 3,
        source:  function (query, process) { 
        return $.get(path, { query: query }, function (data) {

                return process(data);

            });

        },
        updater: function(item) {
          var name = this.$element.attr('name');
          var index = name.match(/(\d+)/);
          index = index[0];
          var bill_to_state = $(".bill_to_state").val();
          var party_state = $(".party_state").val();
          var cgst_rate=sgst_utgst_rate=igst_rate=0;
          var taxable_amount = item.taxable_amount;
          var hsn_sac = item.hsn_sac;
          var gst_percent = item.gst_percent;
          $("input[name='invoice_items["+index+"][taxable_amount]']").val(taxable_amount);
          $("input[name='invoice_items["+index+"][hsn_sac]']").val(hsn_sac);
          $("input[name='invoice_items["+index+"][qty]']").val(1);
          if(bill_to_state == party_state){
            var divided_percent = gst_percent/2;
            $("input[name='invoice_items["+index+"][cgst_rate]']").val(divided_percent);
            $("input[name='invoice_items["+index+"][sgst_utgst_rate]']").val(divided_percent);
            $("input[name='invoice_items["+index+"][igst_rate]']").val(0);
          }else if(bill_to_state != party_state){
            $("input[name='invoice_items["+index+"][cgst_rate]']").val(0);
            $("input[name='invoice_items["+index+"][sgst_utgst_rate]']").val(0);
            $("input[name='invoice_items["+index+"][igst_rate]']").val(gst_percent);
          }else{
            $("input[name='invoice_items["+index+"][cgst_rate]']").val(0);
            $("input[name='invoice_items["+index+"][sgst_utgst_rate]']").val(0);
            $("input[name='invoice_items["+index+"][igst_rate]']").val(0);
          }

          $("input[name='invoice_items["+index+"][taxable_amount]']").trigger('change');
          
          return item;
    }

    });
} // init typeahead ends
inittypeahead();
</script>