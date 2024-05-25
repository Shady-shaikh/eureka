$(document).ready(function () {
    // Countries

    var country_arr = new Array(
        "Select Country",
        "AUSTRALIA",
        "INDIA",
        "NEW ZEALAND",
        "USA",
        "UAE",
        "MAURITIUS"
    );

    $.each(country_arr, function (i, item) {
        var country_data = "";

        if (country_selected == i) {
            country_data = $(
                "<option>",
                {
                    value: i,
                    text: item,
                    selected: true,
                },
                "</option>"
            );
        } else {
            country_data = $(
                "<option>",
                {
                    value: i,
                    text: item,
                },
                "</option>"
            );
        }
        $("#country").append(country_data);

        $("#country1").append(
            $(
                "<option>",
                {
                    value: i,
                    text: item,
                },
                "</option>"
            )
        );
    });

    // States
    var s_a = new Array();
    s_a[0] = "Select State";
    s_a[1] = "Select State|QUEENSLAND|VICTORIA";
    s_a[2] =
        "Select State|ANDHRA PRADESH|ARUNACHAL PRADESH|ASSAM|BIHAR|CHHATTISGARH|GOA|GUJARAT|HARYANA|HIMACHAL PRADESH|JHARKHAND|KARNATAKA|KERALA|MADHYA PRADESH|MAHARASHTRA|MANIPUR|MEGHALAYA|MIZORAM|NAGALAND|ODISHA|PUNJAB|RAJASTHAN|SIKKIM|TAMIL NADU|TELANGANA|TRIPURA|UTTARAKHAND|UTTAR PRADESH|WEST BENGAL";

    s_a[3] = "Select State|AUCKLAND";
    s_a[4] = "Select State|NEWJERSEY|ILLINOIS";
    s_a[5] = "Select State|DUBAI";
    s_a[6] = "Select State|MAURITIUS";

    // Cities
    var c_a = new Array();
    c_a["QUEENSLAND"] = "BRISBANE";
    c_a["VICTORIA"] = "MELBOURNE";
    c_a["ANDHRA PRADESH"] =
        "ANANTAPUR|CHITTOOR|EAST GODAVARI|GUNTUR|KRISHNA|KURNOOL|NELLORE|PRAKASAM|SRIKAKULAM|VISAKHAPATNAM|VIZIANAGARAM|WEST GODAVARI|YSR KADAPA";
    c_a["ARUNACHAL PRADESH"] =
        "ANJAW|CHANGLANG|DIBANG VALLEY|EAST KAMENG|EAST SIANG|KAMLE|KRA DAADI|KURUNG KUMEY|LEPAA RADA|LOHIT|LONGDING|LOWER DIBANG VALLEY|LOWER SIANG|LOWER SUBANSIRI|NAMSAI|PAPUM PARE|SHI YOMI|SIANG|TAWANG|TIRAP|UPPER DIBANG VALLEY|UPPER SIANG|UPPER SUBANSIRI|WEST KAMENG|WEST SIANG";
    c_a["ASSAM"] =
        "BAKSA|BARPETA|BISWANATH|BONGAIGAON|CACHAR|CHARAIDEO|CHIRANG|DARRANG|DHEMAJI|DHUBRI|DIBRUGARH|DIMA HASAO|GOALPARA|GOLAGHAT|HAILAKANDI|HOJAI|JORHAT|KAMRUP|KAMRUP METROPOLITAN|KARBI ANGLONG|KARIMGANJ|KOKRAJHAR|LAKHIMPUR|MAJULI|MORIGAON|NAGAON|NALBARI|SIVASAGAR|SONITPUR|SOUTH SALMARA-MANKACHAR|TINSUKIA|UDALGURI|WEST KARBI ANGLONG";
    c_a["BIHAR"] =
        "ARARIA|ARWAL|AURANGABAD|BANKA|BEGUSARAI|BHAGALPUR|BHOJPUR|BUXAR|DARBHANGA|EAST CHAMPARAN|GOPALGANJ|JAMUI|JEHANABAD|KAIMUR|KATIHAR|KHAGARIA|KISHANGANJ|LAKHISARAI|MADHEPURA|MADHUBANI|MUNGER|MUZAFFARPUR|NALANDA|NAWADA|PASHCHIM CHAMPARAN|PATNA|PURBI CHAMPARAN|PURNIA|ROHTAS|SAHARSA|SAMASTIPUR|SARAN|SHEIKHPURA|SHEOHAR|SITAMARHI|SIWAN|SUPAUL|VAISHALI";
    c_a["CHHATTISGARH"] =
        "BALOD|BALODA BAZAR|BALRAMPUR|BAMETARA|BASTAR|BIJAPUR|BILASPUR|DANTEWADA|DHAMTARI|DURG|GARIABAND|GAURELA PENDRA MARWAHI|JANJGIR-CHAMPA|JASHPUR|KABIRDHAM|KANKER|KONDAGAON|KORBA|KOREA|MAHASAMUND|MUNGELI|NARAYANPUR|RAIGARH|RAIPUR|RAJNANDGAON|SUKMA|SURAJPUR";
    c_a["GOA"] = "NORTH GOA|SOUTH GOA";
    c_a["GUJARAT"] =
        "AHMEDABAD|AMRELI|ANAND|ARAVALLI|BANASKANTHA|BHARUCH|BHAVNAGAR|BOTAD|CHHOTA UDAIPUR|DAHOD|DANG|DEVSAR|DWARKA|GANDHINAGAR|GIR SOMNATH|JAMNAGAR|JUNAGADH|KHEDA|KUTCH|MAHISAGAR|MEHSANA|MORBI|NARMADA|NAVSARI|PANCHMAHAL|PATAN|PORBANDAR|RAJKOT|SABARKANTHA|SURAT|SURENDRANAGAR|TAPI|VADODARA|VALSAD";
    c_a["HARYANA"] =
        "AMBALA|BHIWANI|CHARKHI DADRI|FARIDABAD|FATEHABAD|GURUGRAM|HISAR|JHAJJAR|JIND|KAITHAL|KARNAL|KURUKSHETRA|MAHENDRAGARH|NUH|PALWAL|PANCHKULA|PANIPAT|REWARI|ROHTAK|SIRSA|SONIPAT|YAMUNANAGAR";
    c_a["HIMACHAL PRADESH"] =
        "BILASPUR|CHAMBA|HAMIRPUR|KANGRA|KINNAUR|KULLU|LAHAUL AND SPITI|Mandi|SHIMLA|SIRMAUR|SOLAN|UNA";
    c_a["JHARKHAND"] =
        "BOKARO|CHATRA|DEOGHAR|DUMKA|EAST SINGHBUM|GARHWA|GIRIDIH|GODDA|GUMLA|HAZARIBAGH|JAMTARA|KHUNTI|KODERMA|LATEHAR|LOHARDAGA|PAKUR|PALAMU|RAMGARH|RANCHI|SAHEBGANJ|SERAIKELA KHARSAWAN|SIMDEGA|WEST SINGHBHUM";
    c_a["KARNATAKA"] =
        "BAGALKOT|BALLARI|BELAGAVI|BENGALURU RURAL|BENGALURU URBAN|BIDAR|CHAMARAJANAGAR|CHIKKABALLAPURA|CHIKKAMAGALURU|CHITRADURGA|DAKSHINA KANNADA|DAVANGERE|DHARWAD|GADAG|HASSAN|HAVERI|KALABURAGI|KODAGU|KOLAR|KOPPAL|MADHUGIRI|MANDYA|MYSURU|RAICHUR|RAMANAGARA|SHIVAMOGGA|TUMAKURU|UDUPI|UTTARA KANNADA|VIJAYAPURA|YADGIR";
    c_a["KERALA"] =
        "ALAPPUZHA|ERNAKULAM|IDUKKI|KANNUR|KASARAGOD|KOLLAM|KOTTAYAM|KOZHIKODE|MALAPPURAM|PALAKKAD|PATHANAMTHITTA|THIRUVANANTHAPURAM|THRISSUR|WAYANAD";
    c_a["MADHYA PRADESH"] =
        "AGAR MALWA|ALIRAJPUR|ANUPPUR|ASHOKNAGAR|BALAGHAT|BARWANI|BETUL|BHIND|BHOPAL|BURHANPUR|CHHATARPUR|CHHINDWARA|DAMOH|DATIA|DEWAS|DHAR|DINDORI|GUNA|GWALIOR|HARDA|HOSHANGABAD|INDORE|JABALPUR|JHABUA|KATNI|KHANDWA|KHARGONE|MADHYA BHARAT|MANDLA|MANDSAUR|MORENA|NARSINGHPUR|NEEMUCH|PANNA|RAISEN|RAJGARH|RATLAM|REWA|SAGAR|SATNA|SEHORE|SEONI|SHAHDOL|SHAJAPUR|SHEOPUR|SHIVPURI|SINGRAULI|TIKAMGARH|UJJAIN|UMARIA|VIDISHA";
    c_a["MAHARASHTRA"] =
        "AHMEDNAGAR|AKOLA|AMRAVATI|BEED|BHANDARA|BULDHANA|CHANDRAPUR|DHULE|GADCHIROLI|GONDIA|HINGOLI|JALGAON|JALNA|KOLHAPUR|LATUR|NAGPUR|NANDED|NANDURBAR|NASHIK|OSMANABAD|PALGHAR|PARBHANI|PUNE|RAIGAD|RATNAGIRI|SANGLI|SATARA|SINDHUDURG|SOLAPUR|THANE|MUMBAI|WARDHA|WASHIM|YAVATMAL";
    c_a["MANIPUR"] =
        "BISHNUPUR|CHANDEL|CHURACHANDPUR|IMPHAL EAST|IMPHAL WEST|JIRIBAM|KAKCHING|KAMJONG|KANGPOKPI|NONEY|PHERZAWL|SENAPATI|TAMENGLONG|TENGNOUPAL|THOUBAL|UKHRUL";
    c_a["MEGHALAYA"] =
        "EAST GARO HILLS|EAST JAINTIA HILLS|EAST KHASI HILLS|NORTH GARO HILLS|RI BHOI|SOUTH GARO HILLS|SOUTH WEST GARO HILLS|SOUTH WEST KHASI HILLS|WEST GARO HILLS|WEST JAINTIA HILLS|WEST KHASI HILLS";
    c_a["MIZORAM"] =
        "AIZAWL|CHAMPHAI|HNAHTHIAL|KHAWZAWL|KOLASIB|LAWNGTLAI|LUNGLEI|MAMIT|SAIHA|SERCHHIP";
    c_a["NAGALAND"] =
        "DIMAPUR|KIPHIRE|KOHIMA|LONGLENG|MOKOKCHUNG|MON|PEREN|PHEK|TUENSANG|WOKHA|ZUNHEBOTO";
    c_a["ODISHA"] =
        "ANGUL|BALANGIR|BALESHWAR|BARGARH|BHADRAK|BOUDH|CUTTACK|DEOGARH|DHENKANAL|GAJAPATI|GANJAM|JAGATSINGHAPUR|JAJPUR|JHARSUGUDA|KALAHANDI|KANDHAMAL|KENDRAPARA|KENDUJHAR|KHORDHA|KORAPUT|MALKANGIRI|MAYURBHANJ|NABARANGPUR|NAYAGARH|NUAPADA|PURI|RAYAGADA|SAMBALPUR|SONEPUR|SUNDARGARH";
    c_a["PUNJAB"] =
        "AMRITSAR|BARNALA|BATHINDA|FARIDKOT|FATEHGARH SAHIB|FEROZEPUR|GURDASPUR|HOSHIARPUR|JALANDHAR|KAPURTHALA|LUDHIANA|MANSA|MOGA|MUKTSAR|PATHANKOT|PATIALA|RUPNAGAR|S.A.S NAGAR|SANGRUR|SHAHEED BHAGAT SINGH NAGAR|SRI MUKTSAR SAHIB|TARN TARAN";
    c_a["RAJASTHAN"] =
        "AJMER|ALWAR|BANSWARA|BARAN|BARMER|BHARATPUR|BHILWARA|BIKANER|BUNDI|CHITTORGARH|CHURU|DAUSA|DHOLPUR|DUNGARPUR|GANGANAGAR|HANUMANGARH|JAIPUR|JAISALMER|JALORE|JHALAWAR|JHUNJHUNU|JODHPUR|KARAULI|KOTA|NAGAUR|PALI|PRATAPGARH|RAJSAMAND|SAWAI MADHOPUR|SIKAR|SIROHI|TONK|UDAIPUR";
    c_a["SIKKIM"] = "EAST SIKKIM|NORTH SIKKIM|SOUTH SIKKIM|WEST SIKKIM";
    c_a["TAMIL NADU"] =
        "ARIYALUR|CHENGALPATTU|CHENNAI|COIMBATORE|CUDDALORE|DHARMAPURI|DINDIGUL|ERODE|KALLAKURICHI|KANCHIPURAM|KANNIYAKUMARI|KARUR|KRISHNAGIRI|MADURAI|MAYILADUTHURAI|NAGAPATTINAM|NAMAKKAL|PERAMBALUR|PUDUKKOTTAI|RAMANATHAPURAM|RANIPET|SALEM|SIVAGANGA|TENKASI|THANJAVUR|THENI|THIRUVALLUR|THIRUVARUR|TIRUCHIRAPPALLI|TIRUNELVELI|TIRUPATHUR|TIRUPPUR|TIRUVANNAMALAI|TOOTHUKUDI|VELLORE|VILUPPURAM|VIRUDHUNAGAR";
    c_a["TELANGANA"] =
        "ADILABAD|BHADRADRI KOTHAGUDEM|HYDERABAD|JAGTIAL|JANGOAN|JAYASHANKAR BHUPALPALLY|JOGULAMBA GADWAL|KAMAREDDY|KARIMNAGAR|KHAMMAM|KUMURAM BHEEM|MAHABUBABAD|MAHABUBNAGAR|MALKAJGIRI|MEDAK|MEDCHAL|MULUGU|NAGARKURNOOL|NALGONDA|NARAYANPET|NIRMAL|NIZAMABAD|PEDDAPALLI|RAJANNA SIRCILLA|RANGA REDDY|SANGAREDDY|SIDDIPET|SURYAPET|VIKARABAD|WANAPARTHY|WARANGAL RURAL|WARANGAL URBAN|YADADRI BHUVANAGIRI";
    c_a["TRIPURA"] =
        "DHALAI|GOMATI|KHOWAI|NORTH TRIPURA|SEPÀHIJALA|SOUTH TRIPURA|UNAKOTI";
    c_a["UTTARAKHAND"] =
        "ALMORA|BAGESHWAR|CHAMOLI|CHAMPAWAT|DEHRADUN|HARIDWAR|NAINITAL|PAURI GARHWAL|PITHORAGARH|RUDRAPRAYAG|TEHRI GARHWAL|UDAM SINGH NAGAR|UTTARKASHI";
    c_a["UTTAR PRADESH"] =
        "AGRA|ALIGARH|AMBEDKAR NAGAR|AMETHI|AMROHA|AURAIYA|AYODHYA|AZAMGARH|BAGHPAT|BAHRAICH|BALLIA|BALRAMPUR|BANDA|BARABANKI|BAREILLY|BASTI|BHADOHI|BIJNOR|BUDAUN|BULANDSHAHR|CHANDAULI|CHITRAKOOT|DEORIA|ETAH|ETAWAH|FAIZABAD|FARRUKHABAD|FATEHPUR|FIROZABAD|GAUTAM BUDDHA NAGAR|GHAZIABAD|GHAZIPUR|GONDA|GORAKHPUR|HAMIRPUR|HAPUR|HARDOI|HATHRAS|JALAUN|JAUNPUR|JHANSI|KANNAUJ|KANPUR DEHAT|KANPUR NAGAR|KASGANJ|KAUSHAMBI|KHERI|KUSHINAGAR|LALITPUR|LUCKNOW|MAHARAJGANJ|MAHOBA|MAINPURI|MATHURA|MAU|MEERUT|MIRZAPUR|MORADABAD|MUZAFFARNAGAR|PILIBHIT|PRATAPGARH|RAE BARELI|RAMPUR|SAHARANPUR|SAMBHAL|SANT KABEER NAGAR|SANT RAVIDAS NAGAR|SHAHJAHANPUR|SHAMLI|SHRAVASTI|SIDDHARTH NAGAR|SITAPUR|SONBHADRA|SULTANPUR|UNNAO|VARANASI";
    c_a["WEST BENGAL"] =
        "ALIPURDUAR|BANKURA|BIRBHUM|COOCH BEHAR|DARJEELING|DINAJPUR DAKSHIN|DINAJPUR UTTAR|HOOGHLY|HOWRAH|JALPAIGURI|JHARGRAM|KALIMPONG|KOLKATA|MALDAH|MURSHIDABAD|NADIA|PASCHIM BARDHAMAN|PURBA BARDHAMAN|PURI|PUSHKAR|PURULIA|SOUTH 24 PARGANAS|UTTAR DINAJPUR";

    c_a["AUCKLAND"] = "AUCKLAND";
    c_a["NEWJERSEY"] = "EDISON";
    c_a["ILLINOIS"] = "CHICAGO";
    c_a["MAURITIUS"] = "MAURITIUS";
    c_a["DUBAI"] = "DUBAI";

    $("#filladdress").change(function () {
        // If the checkbox is checked, copy values
        if ($(this).prop("checked")) {
            $("#bp_address_name1").val($("#bp_address_name").val());
            $("#building_no_name1").val($("#building_no_name").val());
            $("#street_name1").val($("#street_name").val());
            $("#landmark1").val($("#landmark").val());
            $("#city1").val($("#city").val());
            $("#pin_code1").val($("#pin_code").val());

            $("#country1").val($("#country").val());
            $("#country1").trigger("change");
            $("#state1").val($("#state").val());
            $("#state1").trigger("change");
            $("#district1").val($("#district").val());
        }
    });

    $("#country").change(function () {
        var c = $(this).val();
        var state_arr = s_a[c].split("|");
        get_states(c, state_arr,"change");
    });

    if (country_selected) {
        var state_arr = s_a[country_selected].split("|");
        get_states(country_selected, state_arr);
    }
    $("#country1").change(function () {
        var c = $(this).val();
        var state_arr = s_a[c].split("|");
        $("#state1").empty();
        $("#district1").empty();
        if (c == 0) {
            $("#state1").append(
                $(
                    "<option>",
                    {
                        value: "0",
                        text: "Select State",
                    },
                    "</option>"
                )
            );
        } else {
            $.each(state_arr, function (i, item_state) {
                $("#state1").append(
                    $(
                        "<option>",
                        {
                            value: item_state,
                            text: item_state,
                        },
                        "</option>"
                    )
                );
            });
        }
        $("#district1").append(
            $(
                "<option>",
                {
                    value: "0",
                    text: "Select district",
                },
                "</option>"
            )
        );
    });

    $("#state").change(function () {
        var s = $(this).val();
        get_districts(s);
    });
    if (state_selected) {
        get_districts(state_selected);
    }

    $("#state1").change(function () {
        var s = $(this).val();
        if (s == "Select State") {
            $("#district1").empty();
            $("#district1").append(
                $(
                    "<option>",
                    {
                        value: "0",
                        text: "Select district",
                    },
                    "</option>"
                )
            );
        }
        var district_arr = c_a[s].split("|");
        $("#district1").empty();

        $.each(district_arr, function (j, item_district) {
            $("#district1").append(
                $(
                    "<option>",
                    {
                        value: item_district,
                        text: item_district,
                    },
                    "</option>"
                )
            );
        });
    });

    function get_states(c, state_arr,type=null) {
        $("#state").empty();
        $("#district").empty();
        if (c == 0) {
            $("#state").append(
                $(
                    "<option>",
                    {
                        value: "",
                        text: "Select State",
                    },
                    "</option>"
                )
            );
        } else {
            $.each(state_arr, function (i, item_state) {
                var state_data = "";
                if (state_selected == item_state && type!="change") {
                    state_data = $(
                        "<option>",
                        {
                            value: item_state,
                            text: item_state,
                            selected: true,
                        },
                        "</option>"
                    );
                } else {
                    state_data = $(
                        "<option>",
                        {
                            value: item_state,
                            text: item_state,
                        },
                        "</option>"
                    );
                }
                $("#state").append(state_data);
            });
        }
        $("#district").append(
            $(
                "<option>",
                {
                    value: "",
                    text: "Select district",
                },
                "</option>"
            )
        );
    }

    function get_districts(s) {
        if (s == "Select State") {
            $("#district").empty();
            $("#district").append(
                $(
                    "<option>",
                    {
                        value: "",
                        text: "Select district",
                    },
                    "</option>"
                )
            );
        }else{

            var district_arr = c_a[s].split("|");
            $("#district").empty();

            $.each(district_arr, function (j, item_district) {

                var distrcit_data = "";
                if (district_selected == item_district) {
                    distrcit_data = $(
                        "<option>",
                        {
                            value: item_district,
                            text: item_district,
                            selected: true,
                        },
                        "</option>"
                    );
                } else {
                    distrcit_data = $(
                        "<option>",
                        {
                            value: item_district,
                            text: item_district,
                        },
                        "</option>"
                    );
                }

                $("#district").append(distrcit_data);


            });

        }

        // $("#district").append(
        //     $(
        //         "<option>",
        //         {
        //             value: item_district,
        //             text: item_district,
        //         },
        //         "</option>"
        //     )
        // );

    }
});
