<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class TrabajadorController extends Controller
{
    public function index()
    {
        try {
            $trabajadores = Trabajador::activo()->get();
            return response()->json([
                'data' => $trabajadores,
                'message' => 'Trabajadores recuperados correctamente.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al recuperar los Trabajadores. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tra_cod' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!is_numeric($value) || intval($value) <= 0) {
                            $fail("The tra_cod must be an integer value.");
                        } else {

                            $exists = Trabajador::where('tra_cod', $value)->exists();
                            if ($exists) {
                                $fail("El código de trabajador ya ha sido registrado.");
                            }
                        }
                    },
                ],
                'tra_nom' => 'required|string|max:200',
                'tra_pat' => 'required|string|max:200',
                'tra_mat' => 'required|string|max:200',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => 'Error de validación al crear Trabajador.'
                ], Response::HTTP_BAD_REQUEST);
            }


            $trabajador = Trabajador::create($validator->validated());
            return response()->json([
                'data' => $trabajador,
                'message' => 'Trabajador creado correctamente.'
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Error en la base de datos',
                'message' => 'Ocurrió un problema al crear el Trabajador. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al crear el Trabajador. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function destroy($id)
    {
        try {
            $trabajador = Trabajador::activo()->findOrFail($id);
            $trabajador->update(['est_ado' => 0]);

            return response()->json([
                'data' => $trabajador,
                'message' => 'Trabajador eliminado correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Trabajador no encontrado.',
                'message' => 'Error al eliminar Trabajador.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Error al eliminar Trabajador.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tra_cod' => [
                function ($attribute, $value, $fail) use ($id) {
                    if (!is_numeric($value) || intval($value) <= 0) {
                        $fail("The tra_cod must be an integer value.");
                    } else {

                        $exists = Trabajador::where('tra_cod', $value)->where('tra_ide', '<>', $id)->exists();
                        if ($exists) {
                            $fail("El código de trabajador ya está en uso.");
                        }
                    }
                },


            ],
            'tra_nom' => 'string|max:200',
            'tra_pat' => 'string|max:200',
            'tra_mat' => 'string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Error de validación al actualizar Trabajador.'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $trabajador = Trabajador::activo()->findOrFail($id);

            $trabajador->update($validator->validated());

            return response()->json([
                'data' => $trabajador,
                'message' => 'Trabajador actualizado correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Trabajador no encontrado.',
                'message' => 'Error al actualizar Trabajador.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Error al actualizar Trabajador.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $trabajador = Trabajador::activo()->findOrFail($id);
            return response()->json([
                'data' => $trabajador,
                'message' => 'Trabajador recuperado correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Trabajador no encontrado.',
                'message' => 'El trabajador con el ID especificado no existe o ha sido eliminado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Error al recuperar Trabajador.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
