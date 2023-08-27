<?php

namespace App\Http\Controllers;

use App\Contracts\IInsuranceCase;
use App\Http\Resources\InsuranceCaseCollection;
use App\Models\InsuranceCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InsuranceCaseController extends Controller
{
    protected IInsuranceCase $service;

    public function __construct(IInsuranceCase $IInsuranceCase)
    {
        $this->service = $IInsuranceCase;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $data = $this->service->list($request, $request->user()->id);

        return Inertia::render('Dashboard', [
            'data' => new InsuranceCaseCollection($data),
            'cars' => $this->service->carsList()
        ]);
    }

    /**
     * Search a listing of the resource.
     */
    public function search(Request $request): JsonResponse
    {
        $data = $this->service->list($request, $request->user()->id);

        return response()->json([
            'data' => new InsuranceCaseCollection($data),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $insuranceCase = new InsuranceCase();

        $created = $this->service->save($request->all(), $insuranceCase);
        return response()->json([
            'status' => 'success',
            'data'   => $created
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(InsuranceCase $insuranceCase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InsuranceCase $insuranceCase): JsonResponse
    {
        $updated = $this->service->save($request->all(), $insuranceCase);

        return response()->json([
            'status' => 'success',
            'data'   => $updated
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InsuranceCase $insuranceCase): JsonResponse
    {
        $insuranceCase->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
