// function getModuleNameFromUrl() {
//     var url = window.location.href;
//     var matches = url.match(/\/([^\/]+)\/edit\/\d+/);

//     if (matches && matches.length > 1) {
//         return matches[1];
//     }

//     return null;
// }

//usama_16-02-2024-to get gst percent and do caluclation dynamic
function getGstPercentage(gst, callback) {
    var getGstRoute = document
        .querySelector('meta[name="getGstRoute"]')
        .getAttribute("content");

    $.ajax({
        url: getGstRoute,
        method: "GET",
        data: { id: gst },
        success: function (response) {
            var gst_percent = parseFloat(response.gst_percent);
            callback(gst_percent);
        },
        error: function (error) {
            console.error("Error fetching GST percentage: ", error);
            callback(0); // Assuming default value is 0 in case of an error
        },
    });
}

function calculategst(elem) {
    // console.log("calculategst", elem);
    var name = $(elem).attr("name");
    var data_name = $(elem).data("name");
    var data_group = $(elem).data("group");

    // var bill_to_state = $('#bill_to_state').val();
    // var party_state =  $('#party_state').val();

    var index = name.match(/(\d+)/);
    index = index[0];

    // var gst_type = document.getElementsByClassName('gst_type');
    // var gst_type = $("input[name='"+data_group+"["+index+"][gst_type]']").val();

    // }

    if (data_group != "") {
        var bill_to_state = $("input[name='bill_to_state']")
            .val()
            .toUpperCase();
        var party_state = $("input[name='party_state']").val().toUpperCase();

        $("input[name='" + data_group + "[" + index + "][cgst_rate]']").val(0);
        $(
            "input[name='" + data_group + "[" + index + "][sgst_utgst_rate]']"
        ).val(0);
        $("input[name='" + data_group + "[" + index + "][igst_rate]']").val(0);

        // alert(bill_to_state);
        // alert(party_state);

        var gst = $(
            "select[name='" + data_group + "[" + index + "][gst_rate]']"
        ).val();
        if (gst == undefined) {
            var gst = $(
                "input[name='" + data_group + "[" + index + "][gst_rate]']"
            ).val();
        }

        var uom = $(
            "input[name='" + data_group + "[" + index + "][uom]']"
        ).val();

        var gst_amount = $(
            "input[name='" + data_group + "[" + index + "][gst_amount]']"
        );

        var qty = $(
            "input[name='" + data_group + "[" + index + "][qty]']"
        ).val();

        var taxable_amount = $(
            "input[name='" + data_group + "[" + index + "][taxable_amount]']"
        ).val();
        var discount_item = $(
            "input[name='" + data_group + "[" + index + "][discount_item]']"
        ).val();

        var cgst_rate = $(
            "input[name='" + data_group + "[" + index + "][cgst_rate]']"
        );
        var sgst_utgst_rate = $(
            "input[name='" + data_group + "[" + index + "][sgst_utgst_rate]']"
        );
        var igst_rate = $(
            "input[name='" + data_group + "[" + index + "][igst_rate]']"
        );

        var discount_item_amount = 0;
        var price_after_discount = 0;
        var gross_total = 0;
        var taxt_amount = 0;

        // fetch final qty
        var final_qty = 0;
        var unit_pack = $(
            "input[name='" + data_group + "[" + index + "][unit_pack]']"
        ).val();
        var pack_case = $(
            "input[name='" + data_group + "[" + index + "][pack_case]']"
        ).val();

        // alert(unit_pack);
        // alert(pack_case);

        if (uom == "case") {
            final_qty = pack_case * unit_pack * qty;
        } else {
            final_qty = qty;
        }
        $("input[name='" + data_group + "[" + index + "][final_qty]']").val(
            final_qty
        );

        // alert(taxable_amount);
        if (discount_item) {
            discount_item_amount = parseFloat(
                (taxable_amount * discount_item) / 100
            );
            price_after_discount = taxable_amount - discount_item_amount;
            taxable_amount = taxable_amount - discount_item_amount;
        }
        if (final_qty != 0) {
            var total = taxable_amount * final_qty;
        } else {
            var total = taxable_amount * qty;
        }

        if (isNaN(total)) {
            total = parseInt(
                $(
                    "input[name='" + data_group + "[" + index + "][amount]']"
                ).val()
            );
        }

        //get gst percent first
        getGstPercentage(gst, function (gst_percentage) {
            if (gst == 4 || taxable_amount == "") {
                taxt_amount = 0;
                gst_amount.val(0);
            } else {
                if (gst == 1) {
                    gst_percentage = 18;
                    taxt_amount = (total * gst_percentage) / 100;
                    // alert(taxt_amount);
                } else if (gst == 2) {
                    gst_percentage = 28;
                    taxt_amount = (total * gst_percentage) / 100;
                } else if (gst == 3) {
                    gst_percentage = 5;
                    taxt_amount = (total * gst_percentage) / 100;
                }
            }

            if (bill_to_state == party_state) {
                gst_percentage = gst_percentage / 2;
                cgst_rate.val(gst_percentage);
                sgst_utgst_rate.val(gst_percentage);
                var calculated_cgst_amount = parseFloat(
                    (total * gst_percentage) / 100
                );
                var calculated_sgst_utgst_amount = parseFloat(
                    (total * gst_percentage) / 100
                );
                var calculated_igst_amount = 0;
            } else {
                igst_rate.val(gst_percentage);
                var calculated_cgst_amount = 0;
                var calculated_sgst_utgst_amount = 0;
                var calculated_igst_amount = parseFloat(
                    (total * gst_percentage) / 100
                );
            }
            if (isNaN(calculated_cgst_amount)) {
                calculated_cgst_amount = 0;
            }
            if (isNaN(calculated_sgst_utgst_amount)) {
                calculated_sgst_utgst_amount = 0;
            }
            if (isNaN(calculated_igst_amount)) {
                calculated_igst_amount = 0;
            }

            gross_total = total + taxt_amount;

            $(
                "input[name='" + data_group + "[" + index + "][cgst_amount]']"
            ).val(calculated_cgst_amount);
            $(
                "input[name='" +
                    data_group +
                    "[" +
                    index +
                    "][sgst_utgst_amount]']"
            ).val(calculated_sgst_utgst_amount);
            $(
                "input[name='" + data_group + "[" + index + "][igst_amount]']"
            ).val(calculated_igst_amount);
            $("input[name='" + data_group + "[" + index + "][total]']").val(
                total.toFixed(2)
            );
            $(
                "input[name='" +
                    data_group +
                    "[" +
                    index +
                    "][price_af_discount]']"
            ).val(price_after_discount.toFixed(2));
            $(
                "input[name='" + data_group + "[" + index + "][gst_amount]']"
            ).val(taxt_amount.toFixed(2));

            $(
                "input[name='" + data_group + "[" + index + "][gross_total]']"
            ).val(gross_total.toFixed(2));
            $(
                "input[name='" + data_group + "[" + index + "][total_value]']"
            ).val(gross_total.toFixed(2));

            calculate_grand_total();
        });
    }
}

function calculate_grand_total() {
    // alert('gradn_total');
    var taxable_amount_array = document.getElementsByClassName("total");
    var discountElement = document.getElementById("discount");
    if (discountElement) {
        var discount = document.getElementById("discount").value;
    }

    var t_down_pmnt = document.getElementById("t_down_pmnt")
        ? document.getElementById("t_down_pmnt").value
        : 0;
    var applied_amt = document.getElementById("applied_amt")
        ? document.getElementById("applied_amt").value
        : 0;
    // alert(applied_amt);
    var cgst_amount_array = document.getElementsByClassName("cgst_amount");
    var sgst_utgst_amount_array =
        document.getElementsByClassName("sgst_utgst_amount");
    var igst_amount_array = document.getElementsByClassName("igst_amount");

    var taxable_amount_total = 0;
    var cgst_amount_total = 0;
    var sgst_utgst_amount_total = 0;
    var igst_amount_total = 0;
    var gst_total = 0;
    var total_amount = 0;
    var total_af_disc = 0;
    var final_amount = 0;
    var disc_amount = 0;

    // console.log(taxable_amount_array);
    $(taxable_amount_array).each(function (input) {
        var value = jQuery(this).val();

        if (value == "") {
            return true;
        }
        taxable_amount_total =
            parseFloat(taxable_amount_total) + parseFloat(value);
        // alert(rate);
    });

    $(cgst_amount_array).each(function (input) {
        var value = jQuery(this).val();
        // alert(value);
        if (value == "") {
            return true;
        }
        cgst_amount_total = parseFloat(cgst_amount_total) + parseFloat(value);
    });
    $(sgst_utgst_amount_array).each(function (input) {
        var value = jQuery(this).val();
        // alert(value);
        if (value == "") {
            return true;
        }
        sgst_utgst_amount_total =
            parseFloat(sgst_utgst_amount_total) + parseFloat(value);
    });
    $(igst_amount_array).each(function (input) {
        var value = jQuery(this).val();

        if (value == "") {
            return true;
        }
        igst_amount_total = parseFloat(igst_amount_total) + parseFloat(value);
    });

    gst_total = (
        parseFloat(cgst_amount_total) +
        parseFloat(sgst_utgst_amount_total) +
        parseFloat(igst_amount_total)
    ).toFixed(2);

    total_amount = parseFloat(taxable_amount_total).toFixed(2);
    if (discount != "" || discount != 0) {
        disc_amount = parseFloat((total_amount * discount) / 100);
        final_amount =
            (parseFloat(gst_total) + parseFloat(taxable_amount_total)).toFixed(
                2
            ) - disc_amount;
    } else {
        final_amount = (
            parseFloat(gst_total) + parseFloat(taxable_amount_total)
        ).toFixed(2);
    }

    total_af_disc = total_amount - disc_amount;

    // alert(gst_total);
    gst_total_rounded = Math.round(gst_total);
    var rounded_off = parseFloat(gst_total_rounded - gst_total).toFixed(2);
    $(".total_af_disc").html(total_af_disc.toFixed(2));
    $(".sub_total").html(taxable_amount_total.toFixed(2));
    $(".cgst_total").html(cgst_amount_total.toFixed(2));
    $(".sgst_utgst_total").html(sgst_utgst_amount_total.toFixed(2));
    $(".igst_total").html(igst_amount_total.toFixed(2));
    $(".gst_total").html(gst_total);
    $(".gross_total").html(
        (parseInt(total_amount) + parseInt(gst_total)).toFixed(2)
    );
    $(".gst_rounded_off").html(rounded_off);
    $(".total_amount").html(total_amount);
    $("#t_down_pmnt").on("change", function () {
        t_down_pmnt = $(this).val();
        if (t_down_pmnt != "") {
            $(".final_amount").html(
                Math.round(parseFloat(final_amount - t_down_pmnt)).toFixed(2)
            );
        }
    });

    $("#applied_amt").on("change", function () {
        applied_amt = $(this).val();
        if (applied_amt != "") {
            $(".balance_due").html(
                parseFloat(final_amount - applied_amt).toFixed(2)
            );
        }
    });

    if (applied_amt != "") {
        $(".balance_due").html(
            parseFloat(final_amount - applied_amt).toFixed(2)
        );
    } else {
        $(".balance_due").html(0);
    }

    if (t_down_pmnt != "") {
        $(".final_amount").html(
            Math.round(parseFloat(final_amount - t_down_pmnt)).toFixed(2)
        );
    } else {
        $(".final_amount").html(
            Math.round(parseFloat(final_amount).toFixed(2))
        );
    }
    $(".w_tax_total").html(parseFloat(final_amount).toFixed(2));
    $(".rounding").html((total_af_disc - Math.floor(total_af_disc)).toFixed(2));
    // alert(disc_amount);
    $(".discount_amt").val(disc_amount.toFixed(2));

    amountWords(Math.round(final_amount));
}

function amountWords(finalamount) {
    var value = Math.round((finalamount * 100) / 100).toFixed(2);

    if (isNaN(finalamount)) {
        $(".total_amount_words").html("Nill");
    } else {
        $.get(
            APP_URL + "/admin/invoice/amountinwords/" + value,
            {},
            function (reply) {
                //alert(reply);
                if (reply != "") {
                    var total = "Rupees " + reply + " Only";
                } else {
                    var total = "Zero";
                }
                $(".total_amount_words").html(total);
            }
        );
    }
}
