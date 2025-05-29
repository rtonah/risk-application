<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseRequestController extends Controller
{
    public function index() {
        $purchaseRequests = PurchaseRequest::with('user')->orderBy('created_at', 'desc')->get();
        return view('purchase.index', compact('purchaseRequests'));
    }

    public function create()
    {
        return view('purchase.create'); // à adapter selon ton formulaire
    }

}
