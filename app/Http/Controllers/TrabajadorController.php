<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = Trabajador::activo()->get();
        $data = [
            'data' => $trabajadores
        ];
        return response()->json($data, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tra_cod' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    // Verifica si hay un registro activo con el mismo código
                    $exists = Trabajador::activo()->where('tra_cod', $value)->exists();
                    if ($exists) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    };
                },
            ],
            'tra_nom' => 'required|string|max:200',
            'tra_pat' => 'required|string|max:200',
            'tra_mat' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            $data = [
                'error' => $validator->errors(),
                'message' => 'Validation error in create Trabajador',
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        ;


        $trabajador = Trabajador::create($validator->validated());

        if (!$trabajador) {
            $data = [
                'message' => 'Error in create Trabajador',
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $data = [
            'data' => $trabajador,
            'message' => 'Create Trabajador',
        ];

        return response()->json($data, Response::HTTP_CREATED);

    }

    public function destroy($id)
    {
        try {
            $trabajador = Trabajador::findOrFail($id);

            // Verificar si el trabajador ya está eliminado (estado = 0)
            if ($trabajador->est_ado === 0) {
                $data = [
                    'message' => 'El Trabajador ya ha sido eliminado anteriormente.',
                ];
                return response()->json($data, Response::HTTP_OK);
            }

            // Actualización de est_ado a 0 (eliminado)
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

    public function update(Request $request, $id)
    {

        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'tra_cod' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($id) {
                    // Verifica si el código ya está en uso por otro trabajador activo
                    $exists = Trabajador::activo()->where('tra_cod', $value)->where('tra_ide', '<>', $id)->exists();
                    if ($exists) {
                        $fail('The ' . $attribute . ' has already been taken by another active trabajador.');
                    }
                },
            ],
            'tra_nom' => 'required|string|max:200',
            'tra_pat' => 'required|string|max:200',
            'tra_mat' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            $data = [
                'error' => $validator->errors(),
                'message' => 'Validation error in update Trabajador',
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }

        try {
            // Buscar el trabajador por ID
            $trabajador = Trabajador::findOrFail($id);

            // Verificar si el trabajador está eliminado (estado = 0)
            if ($trabajador->est_ado === 0) {
                $data = [
                    'message' => 'El Trabajador ha sido eliminado anteriormente y no puede ser actualizado.',
                ];
                return response()->json($data, Response::HTTP_FORBIDDEN);
            }

            // Actualizar los datos del trabajador
            $trabajador->update($validator->validated());

            $data = [
                'data' => $trabajador,
                'message' => 'Trabajador actualizado correctamente.',
            ];
            return response()->json($data, Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            $data = [
                'error' => 'Trabajador no encontrado.',
                'message' => 'Error en la actualización del Trabajador.',
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
                'message' => 'Error en la actualización del Trabajador.',
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            // Buscar el trabajador activo por su ID usando el scope
            $trabajador = Trabajador::activo()->findOrFail($id);

            // Retornar el trabajador encontrado
            return response()->json([
                'data' => $trabajador,
                'message' => "Recuperado Correctamente"
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Trabajador no encontrado',
                'message' => 'El trabajador con el ID especificado no existe o ha sido eliminado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Error en la recuperación del Trabajador.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
