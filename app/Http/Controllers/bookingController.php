<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use App\Mail\HelloMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'anio' => 'nullable|integer|digits:4',
            'color' => 'nullable|string|max:255',
            'servicio_extra' => 'nullable|string|max:255',
            'nombre' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
            'nombre_cliente' => 'nullable|string|max:255',
            'apellido_cliente' => 'nullable|string|max:255',
            'email_cliente' => 'nullable|email|max:255',
            'telefono_cliente' => 'nullable|string|max:20',
            'direccion_cliente' => 'nullable|string|max:255',
            'ciudad_cliente' => 'nullable|string|max:255',
            'estado_servico' => 'nullable|string|max:255',
            'codigo_postal_cliente' => 'nullable|string|max:10',
            'peticion_cliente' => 'nullable|string|max:500',
            'descripcion_servicio' => 'nullable|string|max:500',
            'dirt_charges' => 'nullable|boolean',
            'acepto_veicle' => 'nullable|boolean',
            'la_tos' => 'nullable|boolean',
            'fecha_servicio' => 'nullable|date',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        // Almacena los datos
        $carro = Booking::create($request->all());

        // Prepara el correo
        $emailContent = new HelloMail($carro);

        // Envía el correo al administrador
        Mail::to('javierteheran19@gmail.com')->send($emailContent);

        // Envía el correo al cliente si tiene un email registrado
        if ($carro->email_cliente) {
            Mail::to($carro->email_cliente)->send($emailContent);
        }

        return response()->json(['message' => 'Carro almacenado exitosamente', 'data' => $carro], 201);
    }
    }


