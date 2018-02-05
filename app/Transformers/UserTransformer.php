<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identificador' => (int)$user->id,
            'nombre' => (string)$user->name,
            'correo' => (string)$user->correo,
            'esVerificado' => (int)$user->verified,
            'esAdministrador' => ($user->admin === 'true'),
            'fechaCreacion' => (string)$user->created_at,
            'fechaActualización' => (string)$user->updated_at,
            'fechaEliminación' => isset($user->updated_at) ? (string)$user->deleted_at : null,
        ];
    }
}
