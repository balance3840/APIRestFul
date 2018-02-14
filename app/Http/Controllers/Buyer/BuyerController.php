<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
	public function __consctruct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only(['show']);
    }
    
    public function index()
    {
        $compradores = Buyer::has('transactions')->get();

        return $this->showAll($compradores);
    }

    
    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }

}
