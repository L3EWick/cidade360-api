<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppEvento;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class AppEventoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'categoria' => 'required|string',
            'categoria_id' => 'required|integer',
            'descricao' => 'nullable|string|max:500',
            'imagem' => 'nullable|image|max:2048',
        ]);
    
        $lat = $request->lat;
        $lng = $request->long;
    
        $apiKey = "9efe5d391ac2424991c80483975ee531"; 
    
        $url = "https://api.opencagedata.com/geocode/v1/json?q={$lat}+{$lng}&key={$apiKey}&language=pt&pretty=1";
    
        try {
            $response = Http::get($url);
            $data = $response->json();
    
            if (isset($data['results'][0]['components'])) {
                $address = $data['results'][0]['components'];
                $logradouro = $address['road'] ?? null;
                $bairro = $address['suburb'] ?? null;
                $cidade = $address['city'] ?? ($address['town'] ?? null);
                $estado = $address['state'] ?? null;
                $cep = $address['postcode'] ?? null;
            } else {
                $logradouro = null;
                $bairro = null;
                $cidade = null;
                $estado = null;
                $cep = null;
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar endereço no OpenCage: ' . $e->getMessage());
            $logradouro = null;
            $bairro = null;
            $cidade = null;
            $estado = null;
            $cep = null;
        }
    
        $evento = new AppEvento();
        $evento->categoria_id = $request->categoria_id;
        $evento->categoria = $request->categoria;
        $evento->descricao = $request->descricao;
        $evento->lat = $lat;
        $evento->long = $lng;
        $evento->logradouro = $logradouro;
        $evento->bairro = $bairro;
        $evento->cidade = $cidade;
        $evento->estado = $estado;
    
        if ($request->hasFile('imagem')) {
            $evento->imagem = $request->file('imagem')->store('eventos', 'public');
        }
    
        $evento->save();
    
        return response()->json([
            'message' => 'Evento criado com sucesso!',
            'data' => $evento
        ], 201);
    }
    public function index()
{
    try {
        $eventos = AppEvento::all(); 

        return response()->json([
            'success' => true,
            'data' => $eventos
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Erro ao buscar eventos: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar eventos'
        ], 500);
    }
}
}
