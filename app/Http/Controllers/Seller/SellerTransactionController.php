<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
{
    public function __consctruct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $transactions = $seller
            ->products()
            ->whereHas('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();

        return $this->ShowAll($transactions);    
    }

   
}
