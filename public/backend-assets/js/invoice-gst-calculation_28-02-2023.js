function calculategst(elem){
    console.log('dffkjbmdbgfj');
    var name = $(elem).attr('name');
    var index = name.match(/(\d+)/);
    index = index[0];
    var qty = $("input[name='invoice_items["+index+"][qty]']").val();
    var taxable_amount = $("input[name='invoice_items["+index+"][taxable_amount]']").val();
    var cgst_rate = $("input[name='invoice_items["+index+"][cgst_rate]']").val();

    var sgst_utgst_rate = $("input[name='invoice_items["+index+"][sgst_utgst_rate]']").val();

    var igst_rate = $("input[name='invoice_items["+index+"][igst_rate]']").val();


    var calculated_cgst_amount = parseFloat(((taxable_amount*qty)*cgst_rate)/100);
    var calculated_sgst_utgst_amount = parseFloat(((taxable_amount*qty)*sgst_utgst_rate)/100);
    var calculated_igst_amount = parseFloat(((taxable_amount*qty)*igst_rate)/100);

    if(isNaN(calculated_cgst_amount)){
      calculated_cgst_amount = 0;
    }if(isNaN(calculated_sgst_utgst_amount)){
      calculated_sgst_utgst_amount = 0;
    }if(isNaN(calculated_igst_amount)){
      calculated_igst_amount = 0;
    }

    var total = taxable_amount*qty;

    $("input[name='invoice_items["+index+"][cgst_amount]']").val(calculated_cgst_amount.toFixed(2));
    $("input[name='invoice_items["+index+"][sgst_utgst_amount]']").val(calculated_sgst_utgst_amount.toFixed(2));
    $("input[name='invoice_items["+index+"][igst_amount]']").val(calculated_igst_amount.toFixed(2));
    $("input[name='invoice_items["+index+"][total]']").val(total.toFixed(2));
    calculate_grand_total();
  }

  function calculate_grand_total()
  {
    var taxable_amount_array = document.getElementsByClassName("total");
    var cgst_amount_array = document.getElementsByClassName("cgst_amount");
    var sgst_utgst_amount_array = document.getElementsByClassName("sgst_utgst_amount");
    var igst_amount_array = document.getElementsByClassName("igst_amount");

    var taxable_amount_total = 0;
    var cgst_amount_total = 0;
    var sgst_utgst_amount_total = 0;
    var igst_amount_total = 0;
    var gst_total = 0;
    var total_amount = 0;

    $(taxable_amount_array).each(function(input){
        var value = jQuery(this).val();
        taxable_amount_total = parseFloat(taxable_amount_total)+parseFloat(value);
    });
    $(cgst_amount_array).each(function(input){
        var value = jQuery(this).val();
        cgst_amount_total = parseFloat(cgst_amount_total)+parseFloat(value);
    });
    $(sgst_utgst_amount_array).each(function(input){
        var value = jQuery(this).val();
        sgst_utgst_amount_total = parseFloat(sgst_utgst_amount_total)+parseFloat(value);
    });
    $(igst_amount_array).each(function(input){
        var value = jQuery(this).val();
        igst_amount_total = parseFloat(igst_amount_total)+parseFloat(value);
    });

    gst_total = (parseFloat(cgst_amount_total) + parseFloat(sgst_utgst_amount_total) + parseFloat(igst_amount_total)).toFixed(2);
    total_amount = (parseFloat(gst_total) + parseFloat(taxable_amount_total)).toFixed(2);
    gst_total_rounded = Math.round(gst_total);
    var rounded_off = parseFloat(gst_total_rounded - gst_total).toFixed(2);
    $(".sub_total").html(taxable_amount_total.toFixed(2));
    $(".cgst_total").html(cgst_amount_total.toFixed(2));
    $(".sgst_utgst_total").html(sgst_utgst_amount_total.toFixed(2));
    $(".igst_total").html(igst_amount_total.toFixed(2));
    $(".gst_total").html(gst_total);
    $(".gst_rounded_off").html(rounded_off);
    $(".total_amount").html(Math.round(total_amount));

    amountWords(Math.round(total_amount));
  }

  function amountWords(finalamount)
{
    var value = Math.round(finalamount*100/100).toFixed(2);

                if(isNaN(finalamount))
                {
                    $(".total_amount_words").html('Nill');
                }
                else
                {
                $.get(APP_URL+'/admin/invoice/amountinwords/'+value,{}, function(reply){
                    //alert(reply);
                    if(reply != ''){
                    var total = "Rupees "+reply+" Only";
                    }else{
                        var total = "Zero";
                    }
                    $(".total_amount_words").html(total);
                });
                }
}
