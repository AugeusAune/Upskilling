<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use PDF;

class ApiHistoryContoller extends Controller
{
    public function index(Request $request, History $history) {
        Paginator::useBootstrap();
        $data = $history->with('user')->get();
        $todayIncome = $history->getIncome($data->whereBetween('created_at', [Carbon::today()->format('Y-m-d') . " 00:00:00", Carbon::today()->format('Y-m-d') . " 23:59:59"]));
        $thisWeekIncome = $history->getIncome($data->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d') . " 00:00:00", Carbon::now()->endOfWeek()->format('Y-m-d') . " 23:59:59"]));
        $thisMonthIncome = $history->getIncome($data->whereBetween('created_at', [Carbon::now()->startOfMonth()->format('Y-m-d') . " 00:00:00", Carbon::now()->endOfMonth()->format('Y-m-d') . " 23:59:59"]));
        $lastMonthIncome = $history->getIncome($data->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d') . " 00:00:00", Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d') . " 23:59:59"]));

        switch($request->filter) {
            case 1 :
                $histories = $data;
                break;
            case 2 :
                $histories = $data->whereBetween('created_at', [Carbon::today()->format('Y-m-d') . " 00:00:00", Carbon::today()->format('Y-m-d') . " 23:59:59"]);
                break; 
            case 3 :
                $histories = $data->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d') . " 00:00:00", Carbon::now()->endOfWeek()->format('Y-m-d') . " 23:59:59"]);
                break;
            case 4 :
                $histories = $data->whereBetween('created_at', [Carbon::now()->startOfMonth()->format('Y-m-d') . " 00:00:00", Carbon::now()->endOfMonth()->format('Y-m-d') . " 23:59:59"]);
                break;
            case 5 :
                $histories = $data->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d') . " 00:00:00", Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d') . " 23:59:59"]);
                break;
            default :
                $histories = $data;
                break;
        }
        return response()->json([
            'success' => true,
            'title' => 'History',
            'histories' => $histories,
            'todayIncome' => $todayIncome,
            'thisWeekIncome' => $thisWeekIncome,
            'thisMonthincome' => $thisMonthIncome,
            'lastMonthIncome' => $lastMonthIncome,
        ], 200);
    }

    public function store(Request $request, History $history) {
        $user = Auth::user();

        $data = [];
        foreach($request->data as $reqHis) {
            $data[] = $reqHis['id'];
        }
        $products = Product::whereIn('id', $data)->get();
        $newHis = [];
        foreach($products as $product) {
            $dataHis = [];
            foreach($request->data as $reqHis) {
                if($product->id == $reqHis['id']){
                    $dataHis = [
                        'name' => $product->name,
                        'price' => $product->price,
                        'amount' => $reqHis['amount']
                    ];
                }
            }
            $newHis[] = $dataHis;
        }
         $create = $history->create([
            'orders' => json_encode($newHis),
            'user_id' => $user->id
        ]);
        if($create) {
            return [
                'message' => 'Success Checkout',
                'data' => $create
            ];
        } else {
            throw "Error";
        }
    }

    public function preview(Request $request, History $history) {
        $data = $history->with('user')->get();
        switch($request->filter) {
            case 1 :
                $histories = $data;
                break;
            case 2 :
                $histories = $data->whereBetween('created_at', [Carbon::today()->format('Y-m-d') . " 00:00:00", Carbon::today()->format('Y-m-d') . " 23:59:59"]);
                break; 
            case 3 :
                $histories = $data->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d') . " 00:00:00", Carbon::now()->endOfWeek()->format('Y-m-d') . " 23:59:59"]);
                break;
            case 4 :
                $histories = $data->whereBetween('created_at', [Carbon::now()->startOfMonth()->format('Y-m-d') . " 00:00:00", Carbon::now()->endOfMonth()->format('Y-m-d') . " 23:59:59"]);
                break;
            case 5 :
                $histories = $data->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d') . " 00:00:00", Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d') . " 23:59:59"]);
                break;
            default :
                $histories = $data;
                break;
        }
        $time = date('d-m-Y');
        $data = [
            'title' => "Exports - $time PDF",
            'histories' => $histories,
            'time' => date('d F Y'),
        ];
        $pdf = PDF::loadView('exports.pdf', $data);
        return $pdf->stream("exports_$time.pdf");
    }

}
