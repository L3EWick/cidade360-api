<?php

namespace App\Http\Controllers;

use App\Models\AppCategorias;
use Illuminate\Http\JsonResponse;

class AppCategoriaController extends Controller
{

    public function index(): JsonResponse
    {
        $categorias = AppCategorias::all();

        return response()->json([
            'success' => true,
            'data' => $categorias,
        ], 200);
    }
}