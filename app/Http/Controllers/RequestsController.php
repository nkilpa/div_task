<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RequestRepository;
use App\Mail\RequestMail;
use App\Models\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RequestsController extends Controller
{
    private RequestRepository $requestRepository;

    public function __construct()
    {
        $this->requestRepository = new RequestRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->requestRepository->getAll();

        return response()->json([
            'status' => 'ok',
            'data' => $result
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(\Illuminate\Http\Request $request): JsonResponse
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

        $requestForm = new Request();
        $requestForm->name = $data['name'];
        $requestForm->email = $data['email'];
        $requestForm->message = $data['message'];
        $requestForm->status = 'Active';

        if ($requestForm->save())
        {
            return response()->json([
                'status' => 'ok',
                'id' => $requestForm->id
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ошибка создания заявки'
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
        $result = $this->requestRepository->getById($id);

        return response()->json([
            'status' => 'ok',
            'model' => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(int $id, \Illuminate\Http\Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->validated();

        $requestForm = $this->requestRepository->getById($id);

        if ($requestForm->status == 'Resolved')
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Заявка уже обработана'
            ]);
        }

        $requestForm->comment = $data['comment'];
        $requestForm->status = 'Resolved';

        if ($requestForm->save())
        {
            Mail::to($requestForm->email)->send(new RequestMail($requestForm));
            return response()->json([
                'status' => 'ok',
                'id' => $requestForm->id
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ошибка изменения заявки'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $requestForm = $this->requestRepository->getById($id);

        if (is_null($requestForm))
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Заявка с id '.$id.' не найдена'
            ]);
        }

        $requestForm->delete();

        return response()->json([
            'status' => 'ok'
        ]);
    }
}
