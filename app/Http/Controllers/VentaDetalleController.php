<?php

namespace App\Http\Controllers;

use App\Models\VentaDetalle;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class VentaDetalleController extends Controller
{

    // Obtener todos los detalles de ventas
    public function indexAll(Request $request)
    {
        try {
            $query = VentaDetalle::activo();

            // Filtrado
            if ($request->has('filter')) {
                $filter = $request->input('filter');
                $query->where(function ($q) use ($filter) {
                    $q->where('v_d_pro', 'like', "%{$filter}%");
                });
            }

            // Paginación
            $page = $request->input('page', 1);
            $perPage = $request->input('limit', 20);
            $ventaDetalles = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $ventaDetalles->items(),
                'meta' => [
                    'total' => $ventaDetalles->total(),
                    'per_page' => $ventaDetalles->perPage(),
                    'current_page' => $ventaDetalles->currentPage(),
                    'last_page' => $ventaDetalles->lastPage(),
                ],
                'success' => true,
                'message' => 'Todos los detalles de ventas recuperados correctamente.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del ' . $e->getMessage(),
                'message' => 'Ocurrió un error inesperado al recuperar los detalles de ventas. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(Request $request, $venta_id)
    {
        try {
            $query = VentaDetalle::where('ven_ide', $venta_id)->activo();

            // Filtrado
            if ($request->has('filter')) {
                $filter = $request->input('filter');
                $query->where(function ($q) use ($filter) {
                    $q->where('v_d_pro', 'like', "%{$filter}%")
                        ->orWhere('ven_ide', 'like', "%{$filter}%");
                });
            }

            // Paginación
            $page = $request->input('page', 1);
            $perPage = $request->input('limit', 20);
            $ventaDetalles = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $ventaDetalles->items(),
                'meta' => [
                    'total' => $ventaDetalles->total(),
                    'per_page' => $ventaDetalles->perPage(),
                    'current_page' => $ventaDetalles->currentPage(),
                    'last_page' => $ventaDetalles->lastPage(),
                ],
                'success' => true,
                'message' => 'Detalles de venta recuperados correctamente.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al recuperar los detalles de venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Crear un nuevo detalle para una venta específica
    public function store(Request $request, $venta_id)
    {
        // Validar la existencia y estado activo de la venta
        $venta = Venta::activo()->find($venta_id);

        if (!$venta) {
            return response()->json([
                'error' => 'Venta no encontrada o inactiva.',
                'message' => 'El venta_id de venta proporcionado no corresponde a una venta.'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'v_d_pro' => 'required|string',
            'v_d_uni' => 'required|numeric',
            'v_d_can' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Error de validación al crear el detalle de venta.'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $ventaDetalle = VentaDetalle::create(array_merge($validator->validated(), ['ven_ide' => $venta_id]));
            return response()->json([
                'data' => $ventaDetalle,
                'success' => true,
                'message' => 'Detalle de venta creado correctamente.'
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Error en la base de datos',
                'message' => 'Ocurrió un problema al crear el detalle de venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al crear el detalle de venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($venta_id, $id)
    {
        try {
            $ventaDetalle = VentaDetalle::where('ven_ide', $venta_id)->activo()->findOrFail($id);
            return response()->json([
                'data' => $ventaDetalle,
                'success' => true,
                'message' => 'Detalle de venta recuperado correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Detalle de venta no encontrado.',
                'message' => 'El detalle de venta con el ID especificado no existe o ha sido eliminado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Error al recuperar el detalle de venta.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Obtener un detalle de venta específico sin verificar la venta
    public function showById($id)
    {
        try {
            $ventaDetalle = VentaDetalle::activo()->findOrFail($id);
            return response()->json([
                'data' => $ventaDetalle,
                'success' => true,
                'message' => 'Detalle de venta recuperado correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Detalle de venta no encontrado.',
                'message' => 'El detalle de venta con el ID especificado no existe o ha sido eliminado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Error al recuperar el detalle de venta.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $venta_id, $id)
    {
        $validator = Validator::make($request->all(), [
            'v_d_pro' => 'string',
            'v_d_uni' => 'numeric',
            'v_d_can' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Error de validación al actualizar el detalle de venta.'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $ventaDetalle = VentaDetalle::where('ven_ide', $venta_id)->activo()->findOrFail($id);
            $ventaDetalle->update($validator->validated());
            $ventaDetalle->refresh();

            return response()->json([
                'data' => $ventaDetalle,
                'success' => true,
                'message' => 'Detalle de venta actualizado correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Detalle de venta no encontrado.',
                'message' => 'El detalle de venta con el ID especificado no existe o ha sido eliminado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Error en la base de datos',
                'message' => 'Ocurrió un problema al actualizar el detalle de venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al actualizar el detalle de venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Eliminar un detalle específico
    public function destroy($venta_id, $id)
    {
        try {
            $ventaDetalle = VentaDetalle::where('ven_ide', $venta_id)->activo()->findOrFail($id);
            $ventaDetalle->delete();
            return response()->json([
                'success' => true,
                'message' => 'Detalle de venta eliminado correctamente.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Detalle de venta no encontrado.',
                'message' => 'El detalle de venta con el ID especificado no existe o ha sido eliminado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Error en la base de datos',
                'message' => 'Ocurrió un problema al eliminar el detalle de venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => 'Ocurrió un error inesperado al eliminar el detalle de venta. Por favor, intenta nuevamente más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
