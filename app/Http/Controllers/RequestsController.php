<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RequestFromRepository;
use App\Mail\RequestMail;
use App\Models\RequestForm as RequestForm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RequestsController extends Controller
{
    private RequestFromRepository $requestFormRepository;

    public function __construct()
    {
        $this->requestFormRepository = new RequestFromRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->requestFormRepository->getAll();

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
     * @throws \Illuminate\Validation\ValidationException
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

        $requestForm = new RequestForm();
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
        $result = $this->requestFormRepository->getById($id);

        return response()->json([
            'status' => 'ok',
            'model' => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(int $id, Request $request): JsonResponse
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

        $requestForm = $this->requestFormRepository->getById($id);

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
        $requestForm = $this->requestFormRepository->getById($id);

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
