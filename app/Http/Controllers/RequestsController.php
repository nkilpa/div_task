<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RequestsRepository;
use App\Models\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestsController extends Controller
{
    private RequestsRepository $requestsRepository;

    public function __construct()
    {
        $this->requestsRepository = new RequestsRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->requestsRepository->getAll();

        return response()->json([
            'status' => 'ok',
            'data' => $result
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->validated();

        $requests = new Requests();
        $requests->name = $data['name'];
        $requests->email = $data['email'];
        $requests->message = $data['message'];
        $requests->status = 'Active';

        if ($requests->save())
        {
            return response()->json([
                'status' => 'ok',
                'id' => $requests->id
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ошибка создания запроса'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->requestsRepository->getById($id);

        return response()->json([
            'status' => 'ok',
            'model' => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests $requests
     * @return JsonResponse
     */
    public function update(Requests $requests): JsonResponse
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Requests  $requests
     * @return \Illuminate\Http\Response
     */
    public function delete(Requests $requests)
    {
        //
    }
}
