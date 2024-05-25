<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\ArInvoiceItems;
use App\Models\backend\BinManagement;
use App\Models\backend\Bintype;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Company;
use App\Models\backend\Country;
use App\Models\backend\GoodsServiceReceiptsItems;
use App\Models\backend\Gst;
use App\Models\backend\Inventory;
use App\Models\backend\Products;
use App\Models\backend\State;
use App\Models\backend\StorageLocations;
use App\Models\backend\Transaction;
use App\Models\backend\UoMs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
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
    public function reports(Request $request)
    {
        if (is_superAdmin()) {
            $data = [];
        } else {

            $data = Transaction::when(session('company_id') != 0 && session('fy_year') != 0, function ($query) {
                return $query->where(['company_id' => session('company_id')]);
            })
                ->orderBy('created_at', 'desc')
                ->orderByRaw('CASE WHEN created_at IN (SELECT created_at FROM transaction GROUP BY created_at HAVING COUNT(*) > 1) THEN id ELSE created_at END desc')
                ->get();
        }

        if ($request->filled('from_date') && $request->filled('to_date') && $request->filled('company_id')) {
            $company_id = $request->company_id;
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();

            $data = Transaction::whereBetween('created_at', [$fromDate, $toDate])
                ->when($company_id, function ($query) use ($company_id) {
                    return $query->where(['company_id' => $company_id]);
                })
                ->orderBy('created_at', 'desc')
                ->orderByRaw('CASE WHEN created_at IN (SELECT created_at FROM transaction GROUP BY created_at HAVING COUNT(*) > 1) THEN id ELSE created_at END desc')
                ->get();
        } else if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();

            if (is_superAdmin()) {
                $data = [];
            } else {
                $data = Transaction::whereBetween('created_at', [$fromDate, $toDate])
                    ->when(session('company_id') != 0 && session('fy_year') != 0, function ($query) {
                        return $query->where(['company_id' => session('company_id')]);
                    })
                    ->orderBy('created_at', 'desc')
                    ->orderByRaw('CASE WHEN created_at IN (SELECT created_at FROM transaction GROUP BY created_at HAVING COUNT(*) > 1) THEN id ELSE created_at END desc')
                    ->get();
            }
        } else if ($request->filled('company_id')) {
            $company_id = $request->company_id;
            $data = Transaction::where('company_id', $company_id)
                ->orderBy('created_at', 'desc')
                ->orderByRaw('CASE WHEN created_at IN (SELECT created_at FROM transaction GROUP BY created_at HAVING COUNT(*) > 1) THEN id ELSE created_at END desc')
                ->get();
        }

        return view('backend.reports.inventory', compact('data'));
    }


    public function purchase(Request $request)
    {
        // $data = GoodsServiceReceiptsItems::with('get_goodservice_receipt')->orderby('created_at', 'desc')->get();

        $companyId = session('company_id');
        $currentYear = session('fy_year');

        $data = GoodsServiceReceiptsItems::with('get_goodservice_receipt')
            ->join('goods_service_receipts', 'goods_service_receipts_items.goods_service_receipt_id', '=', 'goods_service_receipts.goods_service_receipt_id')
            ->whereHas('get_goodservice_receipt', function ($query) {
                return $query->where('is_inventory_updated', 1);
            })
            ->when(session('company_id') != 0 && session('fy_year') != 0, function ($query) use ($companyId, $currentYear) {
                return $query->whereHas('get_goodservice_receipt', function ($query) use ($companyId, $currentYear) {
                    $query->where('company_id', $companyId)->where('fy_year', $currentYear);
                });
            })
            ->orderByRaw("CAST(SUBSTRING_INDEX(goods_service_receipts.bill_no, '-', -1) AS UNSIGNED) ASC")
            ->orderBy('goods_service_receipts_items.created_at', 'desc')
            ->get();


        if ($request->filled('from_date') && $request->filled('to_date') && $request->filled('company_id')) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();
            $company_id = $request->company_id;

            $data = GoodsServiceReceiptsItems::with('get_goodservice_receipt')
                ->whereHas('get_goodservice_receipt', function ($query) {
                    return $query->where('is_inventory_updated', 1);
                })
                ->when($company_id, function ($query) use ($company_id) {
                    return $query->whereHas('get_goodservice_receipt', function ($query) use ($company_id) {
                        $query->where('company_id', $company_id);
                    });
                })
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();

            $data = GoodsServiceReceiptsItems::with('get_goodservice_receipt')
                ->whereHas('get_goodservice_receipt', function ($query) {
                    return $query->where('is_inventory_updated', 1);
                })
                ->when(session('company_id') != 0 && session('fy_year') != 0, function ($query) use ($companyId, $currentYear) {
                    return $query->whereHas('get_goodservice_receipt', function ($query) use ($companyId, $currentYear) {
                        $query->where('company_id', $companyId)->where('fy_year', $currentYear);
                    });
                })
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->filled('company_id')) {
            $company_id = $request->company_id;
            $data = GoodsServiceReceiptsItems::with('get_goodservice_receipt')
                ->whereHas('get_goodservice_receipt', function ($query) {
                    return $query->where('is_inventory_updated', 1);
                })
                ->when($company_id, function ($query) use ($company_id) {
                    return $query->whereHas('get_goodservice_receipt', function ($query) use ($company_id) {
                        $query->where('company_id', $company_id);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('backend.reports.purchase', compact('data'));
    }

    public function sales(Request $request)
    {
        $companyId = session('company_id');
        $currentYear = session('fy_year');

        $data = ArInvoiceItems::with('get_ar')
            ->when(session('company_id') != 0 && session('fy_year') != 0, function ($query) use ($companyId, $currentYear) {
                return $query->whereHas('get_ar', function ($query) use ($companyId, $currentYear) {
                    $query->where('company_id', $companyId)->where('fy_year', $currentYear);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($companyId,$currentYear);

        if ($request->filled('from_date') && $request->filled('to_date') && $request->filled('company_id')) {
            $company_id = $request->company_id;
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();

            $data = ArInvoiceItems::with('get_ar')
                ->when($company_id, function ($query) use ($company_id) {
                    return $query->whereHas('get_ar', function ($query) use ($company_id) {
                        $query->where('company_id', $company_id);
                    });
                })
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();

            $data = ArInvoiceItems::with('get_ar')
                ->when(session('company_id') != 0 && session('fy_year') != 0, function ($query) use ($companyId, $currentYear) {
                    return $query->whereHas('get_ar', function ($query) use ($companyId, $currentYear) {
                        $query->where('company_id', $companyId)->where('fy_year', $currentYear);
                    });
                })
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($request->filled('company_id')) {
            $company_id = $request->company_id;
            $data = ArInvoiceItems::with('get_ar')
                ->when($company_id, function ($query) use ($company_id) {
                    return $query->whereHas('get_ar', function ($query) use ($company_id) {
                        $query->where('company_id', $company_id);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $all_country = Country::pluck('name', 'country_id');
        $all_state = State::pluck('name', 'id');
        $all_uom = UoMs::pluck('uom_name', 'uom_id');

        $categories = DB::table('bp_category')->pluck('name', 'id');
        return view('backend.reports.sales', compact('all_uom', 'categories', 'data', 'all_country', 'all_state'));
    }
}
