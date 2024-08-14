<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Venta::query()->activo();

            // Filtrado
            if ($request->has('filter')) {
                $filter = $request->input('filter');
                $query->where(function ($q) use ($filter) {
                    $q->where('ven_ser', 'like', "%{$filter}%")
                        ->orWhere('ven_num', 'like', "%{$filter}%")
                        ->orWhere('ven_cli', 'like', "%{$filter}%");
                });
            }

            // Paginación
            $page = $request->input('page', 1);
            $perPage = $request->input('limit', 20);
            $ventas = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $ventas->items(),
                'meta' => [
                    'total' => $ventas->total(),
                    'per_page' => $ventas->perPage(),
                    'current_page' => $ventas->currentPage(),
                    'last_page' => $ventas->lastPage(),
                ],
                'success' => true,
                'message' => 'Ventas recuperadas correctamente.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al recuperar las ventas. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ven_ser' => 'required|string|max:5',
            'ven_num' => 'required|string|max:100',
            'ven_cli' => 'required|string|max:200',
            'ven_mon' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Error de validación al crear la venta.'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $venta = Venta::create($validator->validated());
            return response()->json([
                'data' => $venta,
                'success' => true,
                'message' => 'Venta creada correctamente.'
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Error en la base de datos',
                'message' => 'Ocurrió un problema al crear la venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al crear la venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $venta = Venta::activo()->findOrFail($id);
            return response()->json([
                'data' => $venta,
                'success' => true,
                'message' => 'Venta recuperada correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Venta no encontrada.',
                'message' => 'La venta con el ID especificado no existe o ha sido eliminada.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Error al recuperar la venta.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $venta = Venta::activo()->findOrFail($id);
            // Iniciar la transacción para asegurar consistencia en la base de datos
            DB::beginTransaction();

            // Marcar la venta como eliminada lógicamente
            $venta->update(['est_ado' => 0]);

            // Marcar lógicamente todos los detalles de la venta como eliminados
            $venta->detalles()->update(['est_ado' => 0]);

            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'data' => $venta,
                'success' => true,
                'message' => 'Venta eliminada correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Venta no encontrada.',
                'message' => 'Error al eliminar la venta.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Error al eliminar la venta.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ven_ser' => 'string|max:5',
            'ven_num' => 'string|max:100',
            'ven_cli' => 'string|max:200',
            'ven_mon' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Error de validación al actualizar la venta.'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $venta = Venta::activo()->findOrFail($id);
            $venta->update($validator->validated());

            return response()->json([
                'data' => $venta,
                'success' => true,
                'message' => 'Venta actualizada correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Venta no encontrada.',
                'message' => 'Error al actualizar la venta.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Error al actualizar la venta.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
