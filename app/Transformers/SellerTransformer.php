<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
       return [
            'identificador' => (int)$seller->id,
            'nombre' => (string)$seller->name,
            'correo' => (string)$seller->correo,
            'fechaCreacion' => (string)$seller->created_at,
            'fechaActualización' => (string)$seller->updated_at,
            'fechaEliminación' => isset($seller->updated_at) ? (string)$seller->deleted_at : null,
        ];
    }
}
