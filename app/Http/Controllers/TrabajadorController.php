<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = Trabajador::all();
        $data = [
            'data' => $trabajadores
        ];
        return response()->json($data, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tra_cod' => 'required|integer',
            'tra_nom' => 'required|string|max:200',
            'tra_pat' => 'required|string|max:200',
            'tra_mat' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            $data = [
                'error' => $validator->errors(),
                'message' => 'Error in create Trabajador',
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }

        $trabajador = Trabajador::create($validator->validated());

        if (!$trabajador) {
            $data = [
                'message' => 'Error in create Trabajador',
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);

        }
        return response()->json($trabajador, Response::HTTP_CREATED);

    }

    public function destroy($id)
    {
        try {
            $trabajador = Trabajador::findOrFail($id);

            // Actualización de est_ado
            $trabajador->update(['est_ado' => 0]);

            $data = [
                'data' => $trabajador,
                'message' => 'Trabajador eliminado correctamente.',
            ];

            return response()->json($data, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            $data = [
                'error' => 'Trabajador no encontrado.',
                'message' => 'Error en la eliminación del Trabajador.',
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
                'message' => 'Error en la eliminación del Trabajador.',
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
