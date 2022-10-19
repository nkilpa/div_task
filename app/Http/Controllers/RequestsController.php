<?php

namespace App\Http\Controllers;

use App\Dto\RequestFilterDto;
use App\Mail\RequestMail;
use App\Models\Request;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RequestsController extends Controller
{
    private RequestRepositoryInterface $requestRepository;

    public function __construct(RequestRepositoryInterface $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    /**
     * Возвращает список заявок.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $dto = new RequestFilterDto();
        if (!is_null($request->date))
        {
            $dto->date = $request->date;
        }
        if (!is_null($request->status))
        {
            $dto->status = $request->status;
        }

        $result = $this->requestRepository->getAll($dto);

        return response()->json([
            'status' => 'ok',
            'data' => $result
        ]);
    }

    /**
     * Создает новую заявку.
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
     * Возвращает заявку по id.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $requestForm = $this->requestRepository->getById($id);

        if (is_null($requestForm))
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Заявка с id '.$id.' не найдена'
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'model' => $requestForm
        ]);
    }

    /**
     * Обновляет заявку.
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

        if (is_null($requestForm))
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Заявка с id '.$id.' не найдена'
            ]);
        }

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
     * Удаляет заявку.
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
