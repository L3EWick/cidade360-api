<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppEvento;
use Illuminate\Support\Facades\Http;

class AppEventoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lat' => 'required|string',
            'long' => 'required|string',
            'categoria' => 'required|string',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
            'format' => 'json',
            'lat' => $request->lat,
            'lon' => $request->long,
        ]);

        $address = $response->json();

        $cidade = $address['address']['city'] ?? '';
        $bairro = $address['address']['suburb'] ?? '';
        $estado = $address['address']['state'] ?? '';

        $imagePath = $request->file('imagem')->store('imagens', 'public');

        $evento = AppEvento::create([
            'lat' => $request->lat,
            'long' => $request->long,
            'cidade' => $cidade,
            'bairro' => $bairro,
            'estado' => $estado,
            'categoria' => $request->categoria,
            'imagem' => $imagePath,
        ]);

        return response()->json(['message' => 'Evento criado com sucesso!', 'evento' => $evento], 201);
    }
}
