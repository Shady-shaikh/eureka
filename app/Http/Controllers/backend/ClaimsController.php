<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\backend\BusinessPartnerCategory;
use App\Models\backend\Claims;
use App\Models\backend\ClaimsJourney;
use App\Models\backend\ClaimStatus;
use Yajra\DataTables\Facades\DataTables;
use App\Models\backend\Company;
use App\Models\backend\ExpenseTypes;
use App\Models\backend\Financialyear;

class ClaimsController extends Controller
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
        $company = [];
        if (Auth()->guard('admin')->user()->company_id) {
            $company = Company::where('company_id', Auth()->guard('admin')->user()->company_id)->first();
        }
        $company = Company::where('company_id', Auth()->guard('admin')->user()->company_id)->first();
        $is_subd = isset($company->is_subd) ? ($company->is_subd ? 1 : 0) : '';
        if ($request->ajax()) {
            $claims = Claims::with('get_group', 'get_category', 'get_company')
                ->when(!empty(Auth()->guard('admin')->user()), function ($q) use ($is_subd) {
                    if ($is_subd) {
                        $q->where('created_by', Auth()->guard('admin')->user()->admin_user_id);
                    } else {
                        if (Auth()->guard('admin')->user()->role == 41) {
                            $q->where(function ($query) {
                                $query->where('created_by', Auth()->guard('admin')->user()->admin_user_id)
                                    ->orWhere(function ($subQuery) {
                                        $subQuery->where('created_by', '!=', Auth()->guard('admin')->user()->admin_user_id)
                                            ->where('is_submitted', 1)
                                            ->where('status', '!=', 2)
                                            ->where('status', '!=', 3);
                                    });
                            });
                        } else if (Auth()->guard('admin')->user()->role == 42) {
                            $q->where('is_approved_by_dis', 1)
                                ->where('status', '!=', 2)
                                ->where('status', '!=', 3);
                        } else if (Auth()->guard('admin')->user()->role == 43) {
                            $q->where('is_approved_by_channel', 1)
                                ->where('status', '!=', 2)
                                ->where('status', '!=', 3);
                        } else if (Auth()->guard('admin')->user()->role == 44) {
                            $q->where('is_approved_by_head', 1)
                                ->where('status', '!=', 2)
                                ->where('status', '!=', 3);
                        }
                    }
                })
                ->orderBy('id', 'desc')->get();


            return DataTables::of($claims)
                ->addIndexColumn()
                ->addColumn('action', function ($claim) use ($is_subd) {
                    $actionBtn = '<div id="action_buttons">';

                    if (!$claim->is_submitted || $claim->status == 3) {
                        $actionBtn .= '<a href="' . route('admin.claims.attachments', ['id' => $claim->id]) . '"
                        class="btn btn-sm btn-warning" title="Attachments">
                        <i class="feather icon-paperclip"></i></a>';
                    }

                    $role = $is_subd ? 41 : 42;
                    if (!empty($claim->retailer_vendor_dn) && !$claim->is_submitted) {
                        $actionBtn .= '<a href="' . route('admin.claims.submission_approval', ['id' => $claim->id, 'role' => $role]) . '"
                    class="btn btn-sm btn-info" title="Submission">
                    <i class="feather icon-upload"></i></a>';
                    }

                    if (request()->user()->can('Update Claims') && !$claim->is_submitted || $claim->status == 3) {
                        // dd("yes");
                        $actionBtn .= '<a href="' . route('admin.claims.edit', ['id' => $claim->id]) . '
                     " class="btn btn-sm btn-primary" title="Edit"><i class="feather icon-edit"></i></a> ';
                    }
                    if (request()->user()->can('Delete Claims') && !$claim->is_submitted) {
                        $actionBtn .= '<a href="' . route('admin.claims.delete', ['id' => $claim->id]) . '"
                    class="btn btn-sm btn-danger" title="Delete" id="delete_btn" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)">
                    <i class="feather icon-trash"></i></a>';
                    }

                    if (request()->user()->can('View Claims') && $claim->is_submitted) {
                        $actionBtn .= '<a href="' . route('admin.claims.view', ['id' => $claim->id]) . '
                     " class="btn btn-sm btn-secondary" title="View"><i class="feather icon-eye"></i></a> ';
                    }



                    return $actionBtn;
                })
                ->addColumn('company_id', fn ($row) => $row->get_company->name ?? '')
                ->addColumn('party_id', fn ($row) => $row->get_party->bp_name ?? '')
                ->addColumn('bp_channel', fn ($row) => $row->get_category->business_partner_category_name ?? '')
                ->addColumn('bp_group', fn ($row) => $row->get_group->name ?? '')
                ->addColumn('status', function ($row) {
                    $user = AdminUsers::where('admin_user_id', $row->user)->first();
                    $company = Company::where('company_id', $user->company_id)->first();
                    $role = isset($company) ? ($company->is_subd ? 'Sub
                    Distributor' : $user->userrole->name) : $user->userrole->name;
                    $status = $row->get_status->name ?? 'Pending';
                    return $status . ' ( ' . $role . ' )';
                })
                ->addColumn('expense_type', fn ($row) => $row->expense->expense_type_name ?? '')
                ->addColumn('claim_type', function ($row) {
                    return strtoupper($row->claim_type);
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        $expense_type = ExpenseTypes::pluck('expense_type_name', 'expense_type_id');
        $claim_type = ['tts' => 'TTS'];

        // Fetch all data for exporting
        $allClaimsData = Claims::orderBy('id', 'desc')->get()
            ->map(function ($item) {

                return [
                    'Distributor' => $item->get_company->name,
                    'Document Date' => $item->doc_date,
                    'Customer/ Vendor Name' => $item->get_party->bp_name,
                    'Business Partner Channel ' => $item->get_category->business_partner_category_name ?? '',
                    'Business Partner group' => $item->get_group->name ?? '',
                    'Retailer/ Vendor DN Date' => $item->retail_dn_date,
                    'Retailer/ Vendor DN Number' => $item->retail_dn_no,
                    'Retailer/ Vendor DN Discription' => $item->retail_dn_desc,
                    'Distributor DN Number' => $item->doc_no,
                    'Distributor DN Date' => $item->importer_dn_date,
                    'Distributor DN Discription' => $item->importer_dn_desc,
                    'Bar Code' => $item->bar_code ?? '',
                    'Activity Month' => $item->activity_month,
                    'Retailer/ Vendor DN received date' => $item->ret_dn_rec_date,
                    'Location' => $item->location,
                    'Brand Name' => $item->brands->brand_name ?? '',
                    'Expense type' => $item->expense->expense_type_name,
                    'Claim Type' => ucfirst($item->claim_type),
                    'Description - Distributor Debit Note' => $item->importer_deb_note,
                    'Debit Value' => $item->debit_value,
                    'GST Value' => $item->gst_value,
                    'Total Debit note value' => $item->total_debit_note,
                ];
            })
            ->toArray();



        return view('backend.claims.index', compact('expense_type', 'claim_type', 'allClaimsData'));
    }


    public function attachments($id)
    {
        return view('backend.claims.attachments', compact('id'));
    }

    public function view($id)
    {
        $claim = Claims::find($id);
        return view('backend.claims.view', compact('claim'));
    }

    public function update_status(Request $request)
    {
        // dd($request->all());
        $claim = Claims::find($request->id);

        if (!empty($request->supporting_docs)) {
            $imageName = upload_docs($request->supporting_docs, 'supporting_doc');
            $claim->supporting_docs = $imageName;
        }
        $claim->fill($request->all());
        $claim->user = Auth()->guard('admin')->user()->admin_user_id;
        if ($request->role == 41) {
            $claim->is_approved_by_dis = 1;
        } else if ($request->role == 42) {
            $claim->is_approved_by_channel = 1;
        } else if ($request->role == 43) {
            $claim->is_approved_by_head = 1;
        } else if ($request->role == 44) {
            $claim->is_approved_by_finance = 1;
        }

        if ($claim->save()) {

            //update journey
            $journey = new ClaimsJourney();
            $journey->fill($claim->toArray());
            $journey->claim_id = $claim->id;
            $journey->save();

            return redirect()->route('admin.claims')->with('success', 'Claim Status Updated');
        } else {
            return redirect()->back()->with('error', 'Some Error Has Occurred');
        }
    }

    public function submission_approval($id, $role)
    {
        $claim = Claims::where('id', $id)->first();
        $claim->is_submitted = 1;
        if ($role == 42) {
            $claim->is_approved_by_dis = 1;
        }
        $claim->save();

        //update journey
        $journey = new ClaimsJourney();
        $journey->fill($claim->toArray());
        $journey->claim_id = $claim->id;
        $journey->save();

        return redirect()->back()->with('success', 'Claim submitted for approval');
    }

    public function attachments_store(Request $request)
    {

        $request->validate([
            'retailer_vendor_dn' => 'required|mimes:pdf,xls,xlsx,doc,docx,csv,txt',
            'distributor_dn' => 'required|mimes:pdf,xls,xlsx,doc,docx,csv,txt',
            'invoice_supp_docs' => 'required|mimes:pdf,xls,xlsx,doc,docx,csv,txt',
            'approval_supp_docs' => 'required|mimes:pdf,xls,xlsx,doc,docx,csv,txt',
        ]);



        $claims = Claims::where('id', $request->id)->first();

        if ($claims->status == 3) {
            $claims->status = 0;
            $claims->is_submitted = 0;
            $claims->is_approved_by_dis = 0;
            $claims->is_approved_by_channel = 0;
            $claims->is_approved_by_head = 0;
            $claims->is_approved_by_finance = 0;
        }

        if (!empty($request->retailer_vendor_dn)) {
            $imageName = upload_docs($request->retailer_vendor_dn, 'retailer_vendor');
            $claims->retailer_vendor_dn = $imageName;
        }
        if (!empty($request->distributor_dn)) {
            $imageName = upload_docs($request->distributor_dn, 'distributor');
            $claims->distributor_dn = $imageName;
        }
        if (!empty($request->invoice_supp_docs)) {
            $imageName = upload_docs($request->invoice_supp_docs, 'invoice');
            $claims->invoice_supp_docs = $imageName;
        }
        if (!empty($request->approval_supp_docs)) {
            $imageName = upload_docs($request->approval_supp_docs, 'approval');
            $claims->approval_supp_docs = $imageName;
        }


        if ($claims->save()) {
            $log = ['module' => 'Claims', 'action' => 'Claim Attachments Updated', 'description' => 'Claim Attachments Updated ' . $claims->doc_no];
            captureActivity($log);
            return redirect('/admin/claims')->with('success', 'Claim Attachments Updated');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function create()
    {

        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");
        $company = Company::pluck('name', 'company_id'); //28-02-2024//for distributor tagging
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $series_no = get_series_number($moduleName);
        return view('backend.claims.create', compact('company', 'business_partner_category', 'series_no'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'doc_date' => 'required',
            'party_id' => 'required',
            // 'bp_channel' => 'required',
            // 'bp_group' => 'required',
            // 'retail_dn_date' => 'required',
            // 'retail_dn_no' => 'required|max:50',
            // 'retail_dn_desc' => 'required|max:100',
            'importer_dn_date' => 'required',
            'importer_dn_desc' => 'required|max:100',
            'activity_month' => 'required|max:50',
            'ret_dn_rec_date' => 'required',
            'location' => 'required|max:100',
            // 'brand' => 'required',
            'expense_type' => 'required',
            'claim_type' => 'required|max:50',
            'importer_deb_note' => 'required|max:100',
            'debit_value' => 'required|max:50',
            'gst_value' => 'required|max:50',
            'total_debit_note' => 'required|max:50',
        ]);

        $Financialyear = get_fy_year($request->company_id);
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $series_no = get_series_number($moduleName, $request->company_id);
        if (empty($series_no)) {
            return redirect()->back()->with(['error' => 'Series Number Is Not Defind For This Module']);
        }

        $financial_year = Financialyear::where(['year' => $Financialyear, 'company_id' => $request->company_id])->first();
        $claim_counter = 0;
        if ($financial_year) {
            $claim_counter = $financial_year->claim_counter + 1;
        }
        $doc_no = $series_no . '-' . $financial_year->year . "-" . $claim_counter;



        $bussiness = new Claims();
        $bussiness->importer_dn_no = $doc_no;
        $bussiness->fill($request->all());
        $bussiness->created_by = Auth()->guard('admin')->user()->admin_user_id;
        $bussiness->user = Auth()->guard('admin')->user()->admin_user_id;
        if ($bussiness->save()) {

            $financial_year->claim_counter = $claim_counter;
            $financial_year->save();
            $log = ['module' => 'Claims', 'action' => 'Claim Created', 'description' => 'Claim Created ' . $bussiness->doc_no];
            captureActivity($log);
            return redirect('/admin/claims')->with('success', 'New Claim Added');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $data = Claims::where('id', $id)->first();

        if (!empty($data)) {
            if (Claims::where('id', $id)->delete()) {

                $log = ['module' => 'Claims', 'action' => 'Claim Deleted', 'description' => 'Claim Deleted ' . $data->doc_no];
                captureActivity($log);

                return redirect('/admin/claims')->with('success', 'Claim Has Been Deleted');
            }
        }
    }

    //edit details
    public function edit($id)
    {
        $model = Claims::where('id', $id)->first();
        $business_partner_category = BusinessPartnerCategory::pluck("business_partner_category_name", "business_partner_category_id");
        $company = Company::pluck('name', 'company_id'); //28-02-2024//for distributor tagging
        $routeName = Route::currentRouteName();
        $moduleName = explode('.', $routeName)[1] ?? null;
        $series_no = get_series_number($moduleName);
        return view('backend.claims.edit', compact('model', 'company', 'business_partner_category', 'series_no'));
    }

    public function update(Request $request, $id = null)
    {
        $request->validate([
            'company_id' => 'required',
            'doc_date' => 'required',
            'party_id' => 'required',
            // 'bp_channel' => 'required',
            // 'bp_group' => 'required',
            // 'retail_dn_date' => 'required',
            // 'retail_dn_no' => 'required|max:50',
            // 'retail_dn_desc' => 'required|max:100',
            'importer_dn_date' => 'required',
            'importer_dn_desc' => 'required|max:100',
            'activity_month' => 'required|max:50',
            'ret_dn_rec_date' => 'required',
            'location' => 'required|max:100',
            // 'brand' => 'required',
            'expense_type' => 'required',
            'claim_type' => 'required|max:50',
            'importer_deb_note' => 'required|max:100',
            'debit_value' => 'required|max:50',
            'gst_value' => 'required|max:50',
            'total_debit_note' => 'required|max:50',
        ]);

        // dd($data);
        $bussiness = Claims::where('id', $request->id)->first();
        $bussiness->fill($request->all());
        if ($bussiness->status == 3) {
            $bussiness->status = 0;
            $bussiness->is_submitted = 0;
            $bussiness->is_approved_by_dis = 0;
            $bussiness->is_approved_by_channel = 0;
            $bussiness->is_approved_by_head = 0;
            $bussiness->is_approved_by_finance = 0;
        }
        $bussiness->created_by = Auth()->guard('admin')->user()->admin_user_id;
        $bussiness->user = Auth()->guard('admin')->user()->admin_user_id;
        if ($bussiness->save()) {
            $log = ['module' => 'Claims', 'action' => 'Claim Updated', 'description' => 'Claim Updated ' . $bussiness->doc_no];
            captureActivity($log);
            return redirect('/admin/claims')->with('success', 'Claim Updated');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
} //end of class
