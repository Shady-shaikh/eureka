<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Area;
use App\Models\backend\Beat;
use App\Models\backend\Bpgroup;
use Illuminate\Support\Facades\DB;

use App\Models\backend\InternalUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Illuminate\Validation\Rule;

use Spatie\Permission\Models\Role;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\BussinessMasterType;
use App\Models\backend\BussinessPartnerOrganizationType;
use App\Models\backend\TermPayment;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerContactDetails;
use App\Models\backend\BussinessPartnerBankingDetails;
use App\Models\backend\BusinessPartnerCategory;
use App\Models\backend\Pricings;
use App\Models\backend\Route;
use App\Models\backend\Country;
use App\Models\backend\State;
use App\Models\backend\City;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use App\Models\backend\Company;

class BussinessParatnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $controllerName = class_basename(request()->route()->getAction('controller'));
        session(['previous_controller' => $controllerName]);

        $bpCategory = DB::table('bp_category')->pluck('name', 'id');


        if ($request->ajax()) {
            $bussinesspartner = BussinessPartnerMaster::with('get_group', 'get_org_type', 'get_category', 'paymentterms', 'get_partner_type_name')
                ->when(Auth()->guard('admin')->user()->role == 41, function ($query) {
                    return $query->where('company_id', Auth()->guard('admin')->user()->company_id);
                })->orderBy('business_partner_id', 'desc')->get();

            // dd($bussinesspartner);

            return DataTables::of($bussinesspartner)
                ->addIndexColumn()
                ->addColumn('action', function ($bussinesspartner) {
                    $actionBtn = '<div id="action_buttons">';
                    if (request()->user()->can('Update Business Master')) {
                        // dd("yes");
                        $actionBtn .= '<a href="' . route('admin.bussinesspartner.edit', ['id' => $bussinesspartner->business_partner_id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete Business Master')) {
                        $actionBtn .= '<a href="' . route('admin.bussinesspartner.delete', ['id' => $bussinesspartner->business_partner_id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a>';
                    }

                    return $actionBtn;
                })
                ->addColumn('bp_organisation_type', fn ($row) => $row->get_org_type->bp_organisation_type ?? '')
                ->addColumn('bp_category', function ($row) use ($bpCategory) {
                    return $bpCategory[$row['bp_category']];
                })
                ->addColumn('term_type', fn ($row) => $row->paymentterms->term_type ?? '')
                ->addColumn('bp_channel', fn ($row) => $row->get_category->business_partner_category_name ?? '')
                ->addColumn('bp_group', fn ($row) => $row->get_group->name ?? '')
                ->addColumn('salesman', function ($row) {
                    $full_name = '';
                    if (!empty($row->get_salesman->first_name) && !empty($row->get_salesman->last_name)) {
                        $full_name = $row->get_salesman->first_name . " " . $row->get_salesman->last_name;
                    } else if (!empty($row->get_salesman->first_name)) {
                        $full_name = $row->get_salesman->first_name;
                    }
                    return $full_name;
                })
                ->addColumn('beat_id', fn ($row) => $row->get_beat->beat_name ?? '')

                ->rawColumns(['action'])
                ->make(true);
        }

        // Fetch all data for exporting
        $allBussinessPartnerDataArray = BussinessPartnerMaster::where('is_converted', 1)->when(Auth()->guard('admin')->user()->role == 41, function ($query) {
            return $query->where('company_id', Auth()->guard('admin')->user()->company_id);
        })->orderBy('business_partner_id', 'desc')
            ->get()
            ->map(function ($item)  use ($bpCategory) {

                $residentialStatus = DB::table('residential_status')
                    ->where('id', $item->residential_status)
                    ->first();
                $bp_category = DB::table('bp_category')
                    ->where('id', $item->bp_category)
                    ->first();
                $gst_reg_type = DB::table('gst_reg_type')
                    ->where('id', $item->gst_reg_type ?? '')
                    ->first();

                return [
                    'business_partner_type' => $item->get_partnerTypeName->bussiness_master_type,
                    'company_id' => $item->get_company->name??'',
                    'bp_name' => $item->bp_name,
                    'bp_organisation_type' => $item->get_org_type->bp_organisation_type,
                    'bp_category' => $bpCategory[$item->bp_category],
                    'residential_status' => $residentialStatus ? $residentialStatus->name : null,
                    'bp_channel' => $item->get_category->business_partner_category_name ?? '',
                    'bp_category' => $bp_category ? $bp_category->name : null,
                    'beat_id' => $item->get_beat->beat_name ?? '',
                    'bp_group' => $item->get_group->name ?? '',
                    'sales_manager' => isset($item->salesManager) ? ($item->salesManager->first_name ?? '' . " " . $item->salesManager->last_name) : '',
                    'ase' => isset($item->get_ase) ? ($item->get_ase->first_name ?? '' . " " . $item->get_ase->last_name) : '',
                    'sales_officer' => isset($item->salesOfficer) ? ($item->salesOfficer->first_name ?? '' . " " . $item->salesOfficer->last_name) : '',
                    'salesman' => isset($item->get_salesman) ? ($item->get_salesman->first_name ?? '' . " " . $item->get_salesman->last_name) : '',
                    'payment_terms_id' => $item->paymentterms->term_type,
                    'gst_details' => $item->gst_details ?? '',
                    'gst_reg_type' => $gst_reg_type ? $gst_reg_type->name : null,
                    'rcm_app' => $item->rcm_app == 1 ? 'Yes' : 'No',
                    'shelf_life' => $item->shelf_life ?? '',
                    'msme_reg' => $item->msme_reg == 1 ? 'Yes' : 'No',
                    'pricing_profile' => $item->get_pricing->pricing_name ?? '',
                ];
            })
            ->toArray();
        // dd($allBussinessPartnerDataArray);

        // Convert data to array

        $bussinesstype = BussinessMasterType::pluck('bussiness_master_type', 'bussiness_master_type_id');
        $bpOrgType = BussinessPartnerOrganizationType::pluck('bp_organisation_type', 'bp_organisation_type_id');
        $termPayment = TermPayment::pluck('term_type', 'payment_terms_id');
        $bpGroup = Bpgroup::pluck('name', 'id');



        return view('backend.bussinesspartner.index', compact('bpCategory', 'allBussinessPartnerDataArray', 'bpGroup', 'bussinesstype', 'bpOrgType', 'termPayment'));
    }


    public function updateBpmaster(Request $request)
    {

        // dd($request->all());
        // for import
        $excel_file = $request->file('file');
        try {

            $spreadsheet = IOFactory::load($excel_file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $row_limit = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range = range(1, $row_limit);
            $column_range = range('A', $column_limit);
            $startcount = 1;
            $srno = 0;
            $uploadedFile = $excel_file;
            $filename = date('Y-m-d_H-i-s') . '_' . str_replace(' ', '_', $uploadedFile->getClientOriginalName());

            if ($request->import_type == 'ven') {
                $uploadedFile->move(public_path('uploads/Vendors'), $filename);
            } else if ($request->import_type  == 'cus') {
                $uploadedFile->move(public_path('uploads/Customers'), $filename);
            }


            foreach ($row_range as $row) {
                $isEmptyRow = true;
                foreach ($column_range as $column) {
                    if (!empty($sheet->getCell($column . $row)->getValue())) {
                        $isEmptyRow = false;
                        break;
                    }
                }

                if ($srno > 0 &&  !$isEmptyRow) {
                    $bill_add = [];
                    $ship_add = [];
                    $contact_details = [];
                    $bank_details = [];
                    if ($request->import_type == 'ven') {
                        $data = [
                            'business_partner_type' => getOrCreateIdUnified(BussinessMasterType::class, 'bussiness_master_type', $sheet->getCell('A' . $row)->getValue(), 'bussiness_master_type_id'),
                            'bp_name' => trim(addslashes($sheet->getCell('B' . $row)->getValue())),
                            'bp_organisation_type' => getOrCreateIdUnified(BussinessPartnerOrganizationType::class, 'bp_organisation_type', $sheet->getCell('C' . $row)->getValue(), 'bp_organisation_type_id'),
                            'residential_status' => getOrCreateIdUnified('residential_status', 'name', $sheet->getCell('D' . $row)->getValue(), 'id', null, null, null, 1),
                            'bp_channel' => getOrCreateIdUnified(BusinessPartnerCategory::class, 'business_partner_category_name', $sheet->getCell('E' . $row)->getValue(), 'business_partner_category_id'),
                            'bp_category' => getOrCreateIdUnified("bp_category", 'name', $sheet->getCell('F' . $row)->getValue(), 'id'),
                            'bp_group' => getOrCreateIdUnified(Bpgroup::class, 'name', $sheet->getCell('G' . $row)->getValue(), 'id', getOrCreateIdUnified(BusinessPartnerCategory::class, 'business_partner_category_name', $sheet->getCell('E' . $row)->getValue(), 'business_partner_category_id')),
                            'payment_terms_id' => getOrCreateIdUnified(TermPayment::class, 'term_type', $sheet->getCell('H' . $row)->getValue(), 'payment_terms_id'),
                            'gst_details' => trim(addslashes($sheet->getCell('I' . $row)->getValue())),
                            'gst_reg_type' => getOrCreateIdUnified('gst_reg_type', 'name', $sheet->getCell('J' . $row)->getValue(), 'id'),
                            'rcm_app' => (int) trim(addslashes($sheet->getCell('K' . $row)->getValue() == 'Yes' ? 1 : 0)),
                            'pricing_profile' => getOrCreateIdUnified(Pricings::class, 'pricing_name', $sheet->getCell('L' . $row)->getValue(), 'pricing_master_id', $sheet->getCell('A' . $row)->getValue() == 'Vendor' ? 'purchase' : 'sale'),
                            'msme_reg' => (int) trim(addslashes($sheet->getCell('M' . $row)->getValue() == 'Yes' ? 1 : 0)),
                            'company_id' => getOrCreateIdUnified(Company::class, 'name', $sheet->getCell('N' . $row)->getValue(), 'company_id'),
                        ];

                        //to enter dependent data in database
                        $country = getOrCreateIdUnified(Country::class, 'name', $sheet->getCell('S' . $row)->getValue(), 'country_id');
                        $state = getOrCreateIdUnified(State::class, 'name', $sheet->getCell('T' . $row)->getValue(), 'id', $country);
                        //bill-address
                        if (!empty($sheet->getCell('O' . $row)->getValue())) {
                            $bill_add = [
                                'address_type' => 'Bill-To/ Bill-From',
                                'bp_address_name' => trim(addslashes($sheet->getCell('O' . $row)->getValue())),
                                'building_no_name' => trim(addslashes($sheet->getCell('P' . $row)->getValue())),
                                'street_name' => trim(addslashes($sheet->getCell('Q' . $row)->getValue())),
                                'landmark' => trim(addslashes($sheet->getCell('R' . $row)->getValue())),
                                'country' => $country,
                                'state' => !empty($country) ? $state : null,
                                'district' => !empty($state) ? getOrCreateIdUnified(City::class, 'city_name', $sheet->getCell('U' . $row)->getValue(), 'city_id', $state) : null,
                                'city' => trim(addslashes($sheet->getCell('V' . $row)->getValue())),
                                'pin_code' => (int) trim(addslashes($sheet->getCell('W' . $row)->getValue())),
                            ];
                        }

                        //to enter dependent data in database
                        $country1 = getOrCreateIdUnified(Country::class, 'name', $sheet->getCell('AB' . $row)->getValue(), 'country_id');
                        $state1 = getOrCreateIdUnified(State::class, 'name', $sheet->getCell('AC' . $row)->getValue(), 'id', $country);
                        //ship-address
                        if (!empty($sheet->getCell('X' . $row)->getValue())) {
                            $ship_add = [
                                'address_type' => 'Ship-To/ Ship-From',
                                'bp_address_name' => trim(addslashes($sheet->getCell('X' . $row)->getValue())),
                                'building_no_name' => trim(addslashes($sheet->getCell('Y' . $row)->getValue())),
                                'street_name' => trim(addslashes($sheet->getCell('Z' . $row)->getValue())),
                                'landmark' => trim(addslashes($sheet->getCell('AA' . $row)->getValue())),
                                'country' => $country1,
                                'state' => !empty($country1) ? $state1 : null,
                                'district' => !empty($state1) ? getOrCreateIdUnified(City::class, 'city_name', $sheet->getCell('AD' . $row)->getValue(), 'city_id', $state1) : null,
                                'city' => trim(addslashes($sheet->getCell('AE' . $row)->getValue())),
                                'pin_code' => trim(addslashes($sheet->getCell('AF' . $row)->getValue())),
                            ];
                        }

                        if (!empty($sheet->getCell('AG' . $row)->getValue())) {
                            // contact-data bill
                            $contact_details_bill = [
                                'type' => 'Bill-To/ Bill-From',
                                'contact_person' => trim(addslashes($sheet->getCell('AG' . $row)->getValue())),
                                'email_id' => trim(addslashes($sheet->getCell('AH' . $row)->getValue())),
                                'mobile_no' => trim(addslashes($sheet->getCell('AI' . $row)->getValue())),
                                'landline' => trim(addslashes($sheet->getCell('AJ' . $row)->getValue())),
                            ];
                        }

                        if (!empty($sheet->getCell('AK' . $row)->getValue())) {
                            // contact-data ship
                            $contact_details_ship = [
                                'type' => 'Ship-To/ Ship-From',
                                'contact_person' => trim(addslashes($sheet->getCell('AK' . $row)->getValue())),
                                'email_id' => trim(addslashes($sheet->getCell('AL' . $row)->getValue())),
                                'mobile_no' => trim(addslashes($sheet->getCell('AM' . $row)->getValue())),
                                'landline' => trim(addslashes($sheet->getCell('AN' . $row)->getValue())),
                            ];
                        }

                        if (!empty($sheet->getCell('AO' . $row)->getValue())) {
                            // bank-data
                            $bank_details = [
                                'acc_holdername' => trim(addslashes($sheet->getCell('AO' . $row)->getValue())),
                                'bank_name' => trim(addslashes($sheet->getCell('AP' . $row)->getValue())),
                                'bank_branch' => trim(addslashes($sheet->getCell('AQ' . $row)->getValue())),
                                'ifsc' => trim(addslashes($sheet->getCell('AR' . $row)->getValue())),
                                'ac_number' => trim(addslashes($sheet->getCell('AS' . $row)->getValue())),
                                'bank_address' => trim(addslashes($sheet->getCell('AT' . $row)->getValue())),
                            ];
                        }
                    } else if ($request->import_type  == 'cus') {

                        $asm_email = '';
                        if (!empty($sheet->getCell('I' . $row)->getValue()) && !empty($sheet->getCell('H' . $row)->getValue())) {
                            $asm_email = getOrCreateIdUnified(AdminUsers::class, 'email', $sheet->getCell('I' . $row)->getValue(), 'admin_user_id', 5, $sheet->getCell('H' . $row)->getValue());
                            $ase_email = '';
                            if (!empty($sheet->getCell('K' . $row)->getValue()) && !empty($sheet->getCell('J' . $row)->getValue())) {
                                $ase_email = getOrCreateIdUnified(AdminUsers::class, 'email', $sheet->getCell('K' . $row)->getValue(), 'admin_user_id', 4, $sheet->getCell('J' . $row)->getValue(), $asm_email);

                                $sales_officer_email = '';
                                if (!empty($sheet->getCell('M' . $row)->getValue()) && !empty($sheet->getCell('L' . $row)->getValue())) {
                                    $sales_officer_email = getOrCreateIdUnified(AdminUsers::class, 'email', $sheet->getCell('M' . $row)->getValue(), 'admin_user_id', 6, $sheet->getCell('L' . $row)->getValue(), $ase_email);
                                }
                            }
                        }
                        // dd($sheet->getCell('H' . $row)->getValue(),$asm_email);
                        $data = [
                            'business_partner_type' => getOrCreateIdUnified(BussinessMasterType::class, 'bussiness_master_type', $sheet->getCell('A' . $row)->getValue(), 'bussiness_master_type_id'),
                            'bp_name' => trim(addslashes($sheet->getCell('B' . $row)->getValue())),
                            'bp_organisation_type' => getOrCreateIdUnified(BussinessPartnerOrganizationType::class, 'bp_organisation_type', $sheet->getCell('C' . $row)->getValue(), 'bp_organisation_type_id'),
                            'residential_status' => getOrCreateIdUnified('residential_status', 'name', $sheet->getCell('D' . $row)->getValue(), 'id', null, null, null, 1),
                            'bp_channel' => getOrCreateIdUnified(BusinessPartnerCategory::class, 'business_partner_category_name', $sheet->getCell('E' . $row)->getValue(), 'business_partner_category_id'),
                            'bp_category' => getOrCreateIdUnified("bp_category", 'name', $sheet->getCell('F' . $row)->getValue(), 'id'),
                            'bp_group' => getOrCreateIdUnified(Bpgroup::class, 'name', $sheet->getCell('G' . $row)->getValue(), 'id', getOrCreateIdUnified(BusinessPartnerCategory::class, 'business_partner_category_name', $sheet->getCell('E' . $row)->getValue(), 'business_partner_category_id')),
                            'sales_manager' => $asm_email,
                            'ase' => !empty($asm_email) ? $ase_email : null,
                            'sales_officer' => !empty($ase_email) ? $sales_officer_email : null,
                            'salesman' => !empty($sales_officer_email && $sheet->getCell('N' . $row)->getValue()) ? getOrCreateIdUnified(AdminUsers::class, 'email', $sheet->getCell('O' . $row)->getValue(), 'admin_user_id', 8, $sheet->getCell('N' . $row)->getValue(), $sales_officer_email) : null,
                            'payment_terms_id' => getOrCreateIdUnified(TermPayment::class, 'term_type', $sheet->getCell('P' . $row)->getValue(), 'payment_terms_id'),
                            'gst_details' => trim(addslashes($sheet->getCell('Q' . $row)->getValue())),
                            'gst_reg_type' => getOrCreateIdUnified('gst_reg_type', 'name', $sheet->getCell('R' . $row)->getValue(), 'id'),
                            'rcm_app' => (int) trim(addslashes($sheet->getCell('S' . $row)->getValue() == 'Yes' ? 1 : 0)),
                            'pricing_profile' => getOrCreateIdUnified(Pricings::class, 'pricing_name', $sheet->getCell('T' . $row)->getValue(), 'pricing_master_id', $sheet->getCell('A' . $row)->getValue() == 'Vendor' ? 'purchase' : 'sale'),
                            'msme_reg' => (int) trim(addslashes($sheet->getCell('U' . $row)->getValue() == 'Yes' ? 1 : 0)),
                            'company_id' => getOrCreateIdUnified(Company::class, 'name', $sheet->getCell('V' . $row)->getValue(), 'company_id'),
                            'beat_id' => getOrCreateIdUnified(Beat::class, 'beat_name', $sheet->getCell('BC' . $row)->getValue(), 'beat_id', null, null, null, 1),
                        ];

                        // dd($data);
                        //to enter dependent data in database
                        $country = getOrCreateIdUnified(Country::class, 'name', $sheet->getCell('AA' . $row)->getValue(), 'country_id');
                        $state = getOrCreateIdUnified(State::class, 'name', $sheet->getCell('AB' . $row)->getValue(), 'id', $country);
                        //bill-address
                        if (!empty($sheet->getCell('W' . $row)->getValue())) {
                            $bill_add = [
                                'address_type' => 'Bill-To/ Bill-From',
                                'bp_address_name' => trim(addslashes($sheet->getCell('W' . $row)->getValue())),
                                'building_no_name' => trim(addslashes($sheet->getCell('X' . $row)->getValue())),
                                'street_name' => trim(addslashes($sheet->getCell('Y' . $row)->getValue())),
                                'landmark' => trim(addslashes($sheet->getCell('Z' . $row)->getValue())),
                                'country' => $country,
                                'state' => !empty($country) ? $state : null,
                                'district' => !empty($state) ? getOrCreateIdUnified(City::class, 'city_name', $sheet->getCell('AC' . $row)->getValue(), 'city_id', $state) : null,
                                'city' => trim(addslashes($sheet->getCell('AD' . $row)->getValue())),
                                'pin_code' => (int) trim(addslashes($sheet->getCell('AE' . $row)->getValue())),
                            ];
                        }

                        //to enter dependent data in database
                        $country1 = getOrCreateIdUnified(Country::class, 'name', $sheet->getCell('AJ' . $row)->getValue(), 'country_id', 1);
                        $state1 = getOrCreateIdUnified(State::class, 'name', $sheet->getCell('AK' . $row)->getValue(), 'id', $country, 1);
                        //ship-address
                        if (!empty($sheet->getCell('AF' . $row)->getValue())) {
                            $ship_add = [
                                'address_type' => 'Ship-To/ Ship-From',
                                'bp_address_name' => trim(addslashes($sheet->getCell('AF' . $row)->getValue())),
                                'building_no_name' => trim(addslashes($sheet->getCell('AG' . $row)->getValue())),
                                'street_name' => trim(addslashes($sheet->getCell('AH' . $row)->getValue())),
                                'landmark' => trim(addslashes($sheet->getCell('AI' . $row)->getValue())),
                                'country' => $country1,
                                'state' => !empty($country1) ? $state1 : null,
                                'district' => !empty($state1) ? getOrCreateIdUnified(City::class, 'city_name', $sheet->getCell('AL' . $row)->getValue(), 'city_id', 1) : null,
                                'city' => trim(addslashes($sheet->getCell('AM' . $row)->getValue())),
                                'pin_code' => trim(addslashes($sheet->getCell('AN' . $row)->getValue())),
                            ];
                        }

                        if (!empty($sheet->getCell('AO' . $row)->getValue())) {
                            // contact-data bill
                            $contact_details_bill = [
                                'type' => 'Bill-To/ Bill-From',
                                'contact_person' => trim(addslashes($sheet->getCell('AO' . $row)->getValue())),
                                'email_id' => trim(addslashes($sheet->getCell('AP' . $row)->getValue())),
                                'mobile_no' => trim(addslashes($sheet->getCell('AQ' . $row)->getValue())),
                                'landline' => trim(addslashes($sheet->getCell('AR' . $row)->getValue())),
                            ];
                        }

                        if (!empty($sheet->getCell('AS' . $row)->getValue())) {
                            // contact-data ship
                            $contact_details_ship = [
                                'type' => 'Ship-To/ Ship-From',
                                'contact_person' => trim(addslashes($sheet->getCell('AS' . $row)->getValue())),
                                'email_id' => trim(addslashes($sheet->getCell('AT' . $row)->getValue())),
                                'mobile_no' => trim(addslashes($sheet->getCell('AU' . $row)->getValue())),
                                'landline' => trim(addslashes($sheet->getCell('AV' . $row)->getValue())),
                            ];
                        }

                        if (!empty($sheet->getCell('AW' . $row)->getValue())) {
                            // bank-data
                            $bank_details = [
                                'acc_holdername' => trim(addslashes($sheet->getCell('AW' . $row)->getValue())),
                                'bank_name' => trim(addslashes($sheet->getCell('AX' . $row)->getValue())),
                                'bank_branch' => trim(addslashes($sheet->getCell('AY' . $row)->getValue())),
                                'ifsc' => trim(addslashes($sheet->getCell('AZ' . $row)->getValue())),
                                'ac_number' => trim(addslashes($sheet->getCell('BA' . $row)->getValue())),
                                'bank_address' => trim(addslashes($sheet->getCell('BB' . $row)->getValue())),
                            ];
                        }
                    }


                    // dd($data, $bill_add, $ship_add, $contact_details_bill,$contact_details_ship, $bank_details);
                    // $pricings = PricingItem::where(['pricing_master_id' => $request->pricing_master_id, 'sku' => $data['sku'], 'item_code' => $data['item_code']])->first();

                    // if (!empty($pricings)) {
                    //     $pricings->selling_price = $data['selling_price'];
                    // } else {
                    $pricings = new BussinessPartnerMaster();
                    $pricings->fill($data);
                    $pricings->company_id = $request->company_id;
                    // }
                    $pricings->save();

                    foreach (['bill_add', 'ship_add', 'contact_details_bill', 'contact_details_ship', 'bank_details'] as $detail) {
                        if (!empty($$detail)) {
                            $className = '';
                            if ($detail == 'bill_add' || $detail == 'ship_add') {
                                $className = BussinessPartnerAddress::class;

                                // $log = ['module' => 'Bussiness Partner Address', 'action' => 'Bussiness Partner Address Created', 'description' => 'Address Created For Bussiness Partner: ' . $data['bp_name']];
                                // captureActivity($log);
                            } else if ($detail == 'contact_details_bill' || $detail == 'contact_details_ship') {
                                $className = BussinessPartnerContactDetails::class;

                                // $log = ['module' => 'Bussiness Partner Contact Details', 'action' => 'Bussiness Partner Contact Details Created', 'description' => 'Contact Details Created For Bussiness Partner: ' . $data['contact_person']];
                                // captureActivity($log);
                            } else if ($detail == 'bank_details') {
                                $className = BussinessPartnerBankingDetails::class;

                                // $log = ['module' => 'Bussiness Partner Bank Details', 'action' => 'Bussiness Partner Bank Details Created', 'description' => 'Bank Details Created For Bussiness Partner: ' . $data['bank_name']];
                                // captureActivity($log);
                            }
                            if (!empty($className)) {
                                $address = new $className();
                                $address->fill($$detail);
                                $address->bussiness_partner_id = $pricings->business_partner_id;
                                $address->save();
                            }
                        }
                    }

                    // array_push($imported_data, $data);
                }

                $log = ['module' => 'Bussiness Partner Master', 'action' => 'Bussiness Partner Created', 'description' => 'Bussiness Partner Created: ' . $data['bp_name']];
                captureActivity($log);

                $startcount++;
                $srno++;
                // $data = User::all();
            }
            // dd($imported_data);
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return redirect()->back()->with('error', $error_code);
        }

        return redirect()->back()->with('success', 'Bussiness Partners Updated');
        // return redirect('/admin/pricings')->with('success', 'Pricing Updated');
    }

    // //create new user
    public function create()
    {
        $bussinesstype = BussinessMasterType::pluck('bussiness_master_type', 'bussiness_master_type_id');
        // $sales_manager = AdminUsers::where('department_id', '7')->pluck("first_name", "admin_user_id");
        // dd($sales_manager);
        // $sales_officer = AdminUsers::where('department_id', '6')->pluck("first_name", "admin_user_id");

        //fetch asm
        $salesman_manager_ids = Role::where('department_id', 5)->pluck('id')->toArray();
        // dd($salesman_manager_ids);
        $sales_manager = AdminUsers::where('role', $salesman_manager_ids)->get()->pluck('first_name', 'admin_user_id'); //('company_id', session('company_id'))->whereIn//27-02-2024
        //fetch ase
        $ase_ids = Role::where('department_id', 4)->pluck('id')->toArray();
        $ase = AdminUsers::whereIn('role', $ase_ids)->get()->pluck('first_name', 'admin_user_id');

        //fetch sales officers
        $sales_officer_ids = Role::where('department_id', 6)->pluck('id')->toArray();
        $sales_officer = AdminUsers::whereIn('role', $sales_officer_ids)->get()->pluck('first_name', 'admin_user_id');

        //fetch salesman
        $salesman_ids = Role::where('department_id', 8)->pluck('id')->toArray();
        $salesman = AdminUsers::whereIn('role', $salesman_ids)->get()->pluck('first_name', 'admin_user_id');

        $area_data = Area::pluck('area_name', 'area_id');
        $route_data = Route::pluck('route_name', 'route_id');
        $beat_data = Beat::get();

        $pricing_data = Pricings::pluck('pricing_name', 'pricing_master_id');
        // dd($pricing_data);
        $bpOrgType = BussinessPartnerOrganizationType::pluck('bp_organisation_type', 'bp_organisation_type_id');
        $termPayment = TermPayment::pluck('term_type', 'payment_terms_id');
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");
        $company = Company::pluck('name', 'company_id'); //28-02-2024//for distributor tagging
        return view('backend.bussinesspartner.create', compact('area_data', 'ase', 'sales_officer', 'salesman', 'route_data', 'pricing_data', 'beat_data', 'bussinesstype', 'sales_manager', 'bpOrgType', 'termPayment', 'business_partner_category', 'company'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'business_partner_type' => 'required',
            // 'bp_code' => 'required',
            'bp_name' => 'required|unique:business_partner_master,bp_name',
            'bp_organisation_type' => 'required',
            'bp_category' => 'required',
            // 'pricing_profile' => 'required',
            // 'shelf_life' => 'required',
            // 'bp_group' => 'required',
            // 'sales_manager' => 'required_if',
            // 'sales_officer' => 'required',
            // 'salesman' => 'required',
            'payment_terms_id' => 'required',
            // 'credit_limit' => 'required',
            // 'gst_details' => 'required',
            // 'residential_status' => 'required',
            // 'gst_reg_type' => 'required',
            // 'msme_reg' => 'required',
            // 'area_id' => 'required',
            // 'route_id' => 'required',
            // 'beat_id' => 'required',
        ]);

        $data = $request->all();
        // dd($data);



        $bussiness = new BussinessPartnerMaster;
        $bussiness->fill($request->all());
        if ($bussiness->save()) {
            // dd('sdgdfg');
            $bid = $bussiness->business_partner_id;
            $uid = ['bussiness_partner_id' => $bid];
            $full_data = array_merge($uid, $data);
            // dd($bid);
            if ($bid != "") {
                if ($data['address_type'] == 'Bill-To/ Bill-From') {
                    $address = new BussinessPartnerAddress;
                    $address->fill($full_data);
                    // dd($address);
                    $address->save();
                }

                if ($data['address_type1'] == 'Ship-To/ Ship-From') {
                    $address1 = new BussinessPartnerAddress;
                    $arr_data = [
                        'bussiness_partner_id' => $bid,
                        'gst_no'  => $data['gst_no1'],
                        'bp_address_name'  => $data['bp_address_name1'],
                        'address_type' => $data['address_type1'],
                        'building_no_name'  => $data['building_no_name1'],
                        'street_name' => $data['street_name1'],
                        'landmark' => $data['landmark1'],
                        'city' => $data['city1'],
                        'pin_code' => $data['pin_code1'],
                        'district'   => $data['district1'],
                        'state'  => $data['state1'],
                        'country' => $data['country1']
                    ];

                    $address1->fill($arr_data);
                    $address1->save();
                }

                //for contact
                if ($data['type'] == 'Bill-To/ Bill-From') {
                    $address = new BussinessPartnerContactDetails;
                    $address->email_id = $data['email_ids'];
                    unset($full_data['email_id']);
                    $address->fill($full_data);
                    $address->save();
                }

                if ($data['type1'] == 'Ship-To/ Ship-From') {
                    $address1 = new BussinessPartnerContactDetails;
                    $arr_data = [
                        'type' => $data['type1'],
                        'bussiness_partner_id' => $bid,
                        'contact_person'  => $data['contact_person1'],
                        'email_id'  => $data['email_ids1'],
                        'mobile_no'  => $data['mobile_no1'],
                        'landline'  => $data['landline1'],
                    ];

                    $address1->fill($arr_data);
                    $address1->save();
                }


                if (!empty($data['acc_holdername']) && !empty($data['bank_name']) && !empty($data['bank_branch'])) {
                    $banking = new BussinessPartnerBankingDetails;
                    $banking->fill($full_data);
                    $banking->save();
                }


                $log = ['module' => 'Bussiness Partner Master', 'action' => 'Bussiness Partner Created', 'description' => 'Bussiness Partner Created: ' . $request->bp_name];
                captureActivity($log);
            }

            return redirect('/admin/bussinesspartner')->with('success', 'New Bussiness Partner Added');
        }
    }


    public function delete($id)
    {
        $data = BussinessPartnerMaster::where('business_partner_id', $id)->first();

        if (!empty($data)) {
            if (BussinessPartnerMaster::where('business_partner_id', $id)->delete()) {
                BussinessPartnerAddress::where('bussiness_partner_id', $id)->delete();
                BussinessPartnerContactDetails::where('bussiness_partner_id', $id)->delete();
                BussinessPartnerBankingDetails::where('bussiness_partner_id', $id)->delete();

                $log = ['module' => 'Bussiness Partner Master', 'action' => 'Bussiness Partner Deleted', 'description' => 'Bussiness Partner Deleted: ' . $data->bp_name];
                captureActivity($log);

                return redirect('/admin/bussinesspartner')->with('success', 'Partner Has Been Deleted');
            }
        }
    }

    //edit details
    public function edit($id)
    {
        $controllerName = class_basename(request()->route()->getAction('controller'));
        session(['previous_controller' => $controllerName]);

        $bussinesstype = BussinessMasterType::all(['bussiness_master_type_id', 'bussiness_master_type']);
        $admin_users = AdminUsers::all(['first_name', 'admin_user_id', 'role']);
        $bussiness_master_type = BussinessMasterType::pluck('bussiness_master_type', 'bussiness_master_type_id');
        // dd($admin_users);
        $bpOrgType = BussinessPartnerOrganizationType::pluck('bp_organisation_type', 'bp_organisation_type_id');
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");
        $termPayment = TermPayment::all();
        $model = BussinessPartnerMaster::where('business_partner_id', $id)->with('paymentterms')->first();
        // dd($bussinesspartner);
        $business_partner_address = BussinessPartnerAddress::where('bussiness_partner_id', $id)->get();
        $business_partner_contact = BussinessPartnerContactDetails::where('bussiness_partner_id', $id)->get();
        $business_partner_banking = BussinessPartnerBankingDetails::where('bussiness_partner_id', $id)->first();
        // dd($business_partner_contact->toArray());
        $area_data = Area::pluck('area_name', 'area_id');
        $route_data = Route::pluck('route_name', 'route_id');
        $beat_data = Beat::get();
        $pricing_data = Pricings::pluck('pricing_name', 'pricing_master_id');

        //fetch asm
        $salesman_manager_ids = Role::where('department_id', 5)->pluck('id')->toArray();
        $sales_manager = AdminUsers::whereIn('role', $salesman_manager_ids)->get()->pluck('first_name', 'admin_user_id');

        // dd($sales_manager);
        //fetch ase
        $ase_ids = Role::where('department_id', 4)->pluck('id')->toArray();
        $ase = AdminUsers::whereIn('role', $ase_ids)->get()->pluck('first_name', 'admin_user_id');

        //fetch sales officers
        $sales_officer_ids = Role::where('department_id', 6)->pluck('id')->toArray();
        $sales_officer = AdminUsers::whereIn('role', $sales_officer_ids)->get()->pluck('first_name', 'admin_user_id');

        //fetch salesman
        $salesman_ids = Role::where('department_id', 8)->pluck('id')->toArray();
        $salesman = AdminUsers::whereIn('role', $salesman_ids)->get()->pluck('first_name', 'admin_user_id');

        $officer_users = [];
        $ase_users = [];
        $asm_users = [];
        if (!empty($model->salesman)) {
            $officer_users = AdminUsers::where(['admin_user_id' => $model->salesman])->first();
            if (!empty($officer_users)) {
                $ase_users = AdminUsers::when(!empty($officer_users->parent_users), function ($q) use ($officer_users) {
                    $q->where([
                        'admin_user_id' => $officer_users->parent_users
                    ]);
                })->first();
                $asm_users = AdminUsers::when(!empty($ase_users->parent_users), function ($q) use ($ase_users) {
                    $q->where([
                        'admin_user_id' => $ase_users->parent_users
                    ]);
                })->first();
            }
        }
        $company = Company::pluck('name', 'company_id'); //28-02-2024//for distributor tagging


        return view('backend.bussinesspartner.edit', compact('officer_users', 'company', 'ase_users', 'asm_users', 'sales_manager', 'ase', 'sales_officer', 'salesman', 'area_data', 'pricing_data', 'route_data', 'beat_data', 'model', 'business_partner_category', 'bussiness_master_type', 'admin_users', 'bussinesstype', 'bpOrgType', 'termPayment', 'business_partner_address', 'business_partner_contact', 'business_partner_banking'));
    }

    public function update(Request $request, $id = null)
    {
        // dd($request->all());
        $request->validate([
            'business_partner_type' => 'required',
            // 'bp_code' => 'required',
            // 'bp_name' => 'required|unique:business_partner_master,bp_name',
            'bp_name' => [
                'required',
                Rule::unique('business_partner_master', 'bp_name')->ignore($id, 'business_partner_id'),
            ],
            'bp_organisation_type' => 'required',
            'bp_category' => 'required',
            // 'pricing_profile' => 'required',
            // 'shelf_life' => 'required',
            // 'bp_group' => 'required',
            // 'sales_manager' => 'required',
            // 'sales_officer' => 'required',
            // 'salesman' => 'required',
            'payment_terms_id' => 'required',
            // 'credit_limit' => 'required',
            // 'gst_details' => 'required',
            // 'residential_status' => 'required',
            // 'gst_reg_type' => 'required',
            // 'msme_reg' => 'required',
            // 'area_id' => 'required',
            // 'route_id' => 'required',
            // 'beat_id' => 'required',
        ]);

        $data = $request->all();
        // dd($data);
        $bussiness = BussinessPartnerMaster::where('business_partner_id', $request->business_partner_id)->first();
        $bussiness->fill($request->all());
        $bussiness->is_converted = 1;
        if ($bussiness->save()) {

            $bid = $bussiness->business_partner_id;
            $uid = ['bussiness_partner_id' => $bid];
            $full_data = array_merge($uid, $data);
            if ($bid != "") {
                if ($data['address_type'] == 'Bill-To/ Bill-From') {
                    BussinessPartnerAddress::updateOrCreate(
                        ['address_type' => 'Bill-To/ Bill-From', 'bussiness_partner_id' => $request->business_partner_id],
                        $full_data
                    );
                }

                if ($data['address_type1'] == 'Ship-To/ Ship-From') {



                    $arr_data = [
                        'bussiness_partner_id' => $bid,
                        'gst_no'  => $data['gst_no1'],
                        'bp_address_name'  => $data['bp_address_name1'],
                        'address_type' => $data['address_type1'],
                        'building_no_name'  => $data['building_no_name1'],
                        'street_name' => $data['street_name1'],
                        'landmark' => $data['landmark1'],
                        'city' => $data['city1'],
                        'pin_code' => $data['pin_code1'],
                        'district'   => $data['district1'],
                        'state'  => $data['state1'],
                        'country' => $data['country1']
                    ];

                    BussinessPartnerAddress::updateOrCreate(
                        ['address_type' => 'Ship-To/ Ship-From', 'bussiness_partner_id' => $request->business_partner_id],
                        $arr_data
                    );
                }

                //for contact
                if (isset($data['type']) && $data['type'] == 'Bill-To/ Bill-From') {
                    $contact = BussinessPartnerContactDetails::where(
                        ['type' => 'Bill-To/ Bill-From', 'bussiness_partner_id' => $request->business_partner_id],
                    )->first();
                    $contact->email_id = $data['email_ids'];
                    unset($full_data['email_id']);
                    $contact->fill($full_data);
                    $contact->save();
                }

                if (isset($data['type1']) && $data['type1'] == 'Ship-To/ Ship-From') {
                    $arr_data = [
                        'type' => $data['type1'],
                        'bussiness_partner_id' => $bid,
                        'contact_person'  => $data['contact_person1'],
                        'email_id'  => $data['email_ids1'],
                        'mobile_no'  => $data['mobile_no1'],
                        'landline'  => $data['landline1'],
                    ];

                    $contact = BussinessPartnerContactDetails::where(
                        ['type' => 'Ship-To/ Ship-From', 'bussiness_partner_id' => $request->business_partner_id],
                    )->first();
                    if ((!empty($contact))) {
                        $contact->fill($arr_data);
                        $contact->save();
                    }
                }



                $banking = BussinessPartnerBankingDetails::firstOrNew(
                    ['bussiness_partner_id' => $request->business_partner_id]
                );
                $banking->fill($full_data);
                $banking->save();



                if ($bussiness->getChanges()) {
                    $new_changes = $bussiness->getChanges();
                    $log = ['module' => 'Bussiness Partner Master', 'action' => 'Bussiness Partner Updated', 'description' => 'Bussiness Partner Updated: Name=>' . $bussiness->bp_name];
                    captureActivityupdate($new_changes, $log);
                }
            }



            return redirect('/admin/bussinesspartner')->with('success', 'Partner Has Been Updated');
        }
    }



    public function address($id)
    {
        $addresses = BussinessPartnerAddress::where('bussiness_partner_id', $id)->get();
        // dd($addresses->toArray());
        return view('backend.bussinesspartner.address', compact('addresses', 'id'));
    }

    //show new address form
    public function addaddress($id)
    {
        return view('backend.bussinesspartner.add_address', compact('id'));
    }

    public function storeaddress(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'address_type' => 'required',
            'bp_address_name' => 'required',
            'building_no_name' => 'required',
            'street_name' => 'required',
            'landmark' => 'required',
            'city' => 'required',
            'pin_code' => 'required|digits:6|min:6|max:6',
            'district' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        //check bp_address id set or not
        if ($request->has('bp_address_id')) {
            //update code
            $address = BussinessPartnerAddress::where('bp_address_id', $request->bp_address_id)->first();
            $address->fill($request->all());
            if ($address->save()) {


                if ($address->getChanges()) {
                    $new_changes = $address->getChanges();
                    $log = ['module' => 'Bussiness Partner Address', 'action' => 'Bussiness Partner Address Updated', 'description' => 'Bussiness Partner Address Updated: ' . $address->bp_address_name];
                    captureActivityupdate($new_changes, $log);
                }



                return redirect()->route('admin.bussinesspartner.address', ['id' => $address->bussiness_partner_id])->with('success', 'Address Has Been updated');
                // return redirect('admin/bussinesspartner/address/'.$id)->with('success', 'New Address Has Been Added');
            } else {
                return redirect()->route('admin.bussinesspartner.address', ['id' => $address->bussiness_partner_id])->with('error', 'Failed to Rupdate Address');
            }
        } else {
            //Insert Code
            $address = new BussinessPartnerAddress;
            $address->fill($request->all());
            $id = $request->bussiness_partner_id;
            if ($address->save()) {

                $log = ['module' => 'Bussiness Partner Address', 'action' => 'Bussiness Partner Address Created', 'description' => 'Bussiness Partner Address Created: ' . $request->city];
                captureActivity($log);

                return redirect()->route('admin.bussinesspartner.address', ['id' => $id])->with('success', 'New Address Has Been Added');
                // return redirect('admin/bussinesspartner/address/'.$id)->with('success', 'New Address Has Been Added');
            } else {
                return redirect()->route('admin.bussinesspartner.address', ['id' => $id])->with('error', 'Failed to Register Address');
            }
        }
    }

    public function editaddress($addressid)
    {
        $address = BussinessPartnerAddress::where('bp_address_id', $addressid)->first();
        // dd($address);
        return view('backend.bussinesspartner.updateaddress', compact('address'));
    }

    //function for delete address
    public function deleteaddress($id)
    {
        $address = BussinessPartnerAddress::where('bp_address_id', $id)->first();

        if (!empty($address)) {
            $addresddata = BussinessPartnerAddress::where('bp_address_id', $id)->delete();

            $log = ['module' => 'Bussiness Partner Address', 'action' => 'Bussiness Partner Address Deleted', 'description' => 'Bussiness Partner Address Deleted: ' . $address->bp_address_name];
            captureActivity($log);

            return redirect()->route('admin.bussinesspartner.address', ['id' => $address->bussiness_partner_id])->with('success', 'Bussiness Partner Address Deleted');
        }
    }

    public function contactdetails($id)
    {
        $contact = BussinessPartnerContactDetails::where('bussiness_partner_id', $id)->get();
        return view('backend.bussinesspartner.contact', compact('contact', 'id'));
    }

    public function createcontact($id)
    {
        return view('backend.bussinesspartner.add_contact', compact('id'));
    }

    public function storecontact(Request $request)
    {

        $request->validate([
            'type' => 'required',
            'contact_person' => 'required',
            'email_id' => 'required|email',
            'mobile_no' => 'required|digits:10|min:10',
        ]);


        if ($request->has('contact_details_id') && $request->contact_details_id != "") {
            //update the data
            $contact = BussinessPartnerContactDetails::where('contact_details_id', $request->contact_details_id)->first();
            $contact->fill($request->all());
            if ($contact->save()) {

                if ($contact->getChanges()) {
                    $new_changes = $contact->getChanges();
                    $log = ['module' => 'Bussiness Partner Contact', 'action' => 'Bussiness Partner Contact Updated', 'description' => 'Bussiness Partner Contact Updated: ' . $contact->contact_person];
                    captureActivityupdate($new_changes, $log);
                }

                return redirect()->route('admin.bussinesspartner.contact', ['id' => $contact->bussiness_partner_id])->with('success', 'Contact Has Been Updates');
            } else {
                return redirect()->route('admin.bussinesspartner.contact', ['id' => $contact->bussiness_partner_id])->with('error', 'Failed to Update Contact');
            }
        } else {
            //inset the data
            $contact = new BussinessPartnerContactDetails;
            $contact->fill($request->all());
            if ($contact->save()) {
                $log = ['module' => 'Bussiness Partner Contact', 'action' => 'Bussiness Partner Contact Created', 'description' => 'Bussiness Partner Contact Created: ' . $request->contact_person];
                captureActivity($log);

                return redirect()->route('admin.bussinesspartner.contact', ['id' => $request->bussiness_partner_id])->with('success', 'Contact Has Been Added');
            } else {
                return redirect()->route('admin.bussinesspartner.contact', ['id' => $request->bussiness_partner_id])->with('error', 'Unable to add contact details');
            }
        }
    }


    public function editcontact($id)
    {
        $contact = BussinessPartnerContactDetails::where('contact_details_id', $id)->first();
        return view('backend.bussinesspartner.update_contact', compact('contact', 'id'));
    }

    //delete contact
    public function deletecontact($id)
    {
        $contact = BussinessPartnerContactDetails::where('contact_details_id', $id)->first();
        if (isset($contact) && count($contact->toArray()) > 0) {
            $deleted = BussinessPartnerContactDetails::where('contact_details_id', $id)->delete();

            if ($deleted) {
                $log = ['module' => 'Bussiness Partner Contact', 'action' => 'Bussiness Partner Contact Deleted', 'description' => 'Bussiness Partner Contact Deleted: ' . $contact->contact_person];
                captureActivity($log);
                return redirect()->route('admin.bussinesspartner.contact', ['id' => $contact->bussiness_partner_id])->with('success', 'Address Has Been Removed');
            } else {
                return redirect()->route('admin.bussinesspartner.contact', ['id' => $contact->bussiness_partner_id])->with('error', 'Unable to remove Address');
            }
        }
    }
    //banking details
    public function banking($id)
    {

        $banking_data = BussinessPartnerBankingDetails::where('bussiness_partner_id', $id)->get();
        return view('backend.bussinesspartner.bank_detail', compact('banking_data', 'id'));
    }

    public function addbank($id)
    {
        return view('backend.bussinesspartner.add_bank', compact('id'));
    }

    public function bankstore(Request $request)
    {

        $request->validate([
            'bank_name' => 'required',
            'bank_branch' => 'required',
            'ifsc' => 'required',
            'ac_number' => 'required|integer',
            'bank_address' => 'required',
        ]);

        if (isset($request->banking_details_id) && $request->banking_details_id != "") {
            $bankdetails = BussinessPartnerBankingDetails::where('banking_details_id', $request->banking_details_id)->first();
            $bankdetails->fill($request->all());
            if ($bankdetails->save()) {


                if ($bankdetails->getChanges()) {
                    $new_changes = $bankdetails->getChanges();
                    $log = ['module' => 'Bussiness Partner Bank', 'action' => 'Bussiness Partner Bank Updated', 'description' => 'Bussiness Partner Bank Updated: ' . $bankdetails->bank_name];
                    captureActivityupdate($new_changes, $log);
                }

                return redirect()->route('admin.bussinesspartner.banking', ['id' => $bankdetails->bussiness_partner_id])->with('success', 'bank Details Has Been Updated');
            } else {
                return redirect()->route('admin.bussinesspartner.banking', ['id' => $bankdetails->bussiness_partner_id])->with('error', 'Unable to Update bank details');
            }
        } else {
            //fresh request
            $bank = new BussinessPartnerBankingDetails;
            $bank->fill($request->all());

            if ($bank->save()) {

                $log = ['module' => 'Bussiness Partner Bank', 'action' => 'Bussiness Partner Bank Created', 'description' => 'Bussiness Partner Bank Created: ' . $request->bank_name];
                captureActivity($log);

                return redirect()->route('admin.bussinesspartner.banking', ['id' => $request->bussiness_partner_id])->with('success', 'bank Details Has Been Added');
            } else {
                return redirect()->route('admin.bussinesspartner.banking', ['id' => $request->bussiness_partner_id])->with('error', 'Unable to Add bank details');
            }
        }
    }

    public function editbank($id)
    {
        $bankdetails = BussinessPartnerBankingDetails::where('banking_details_id', $id)->first();
        return view('backend.bussinesspartner.edit_bank', compact('id', 'bankdetails'));
    }

    public function deletebank($id)
    {
        $bank = BussinessPartnerBankingDetails::where('banking_details_id', $id)->first();
        if (isset($bank) &&  count($bank->toArray()) > 0) {
            $delete = BussinessPartnerBankingDetails::where('banking_details_id', $id)->delete();
            if ($delete) {
                $log = ['module' => 'Bussiness Partner Bank', 'action' => 'Bussiness Partner Bank Deleted', 'description' => 'Bussiness Partner Bank Deleted: ' . $bank->bank_name];
                captureActivity($log);

                return redirect()->route('admin.bussinesspartner.banking', ['id' => $bank->bussiness_partner_id])->with('error', 'bank Details Has Been Added');
            }
        }
    }
} //end of class
