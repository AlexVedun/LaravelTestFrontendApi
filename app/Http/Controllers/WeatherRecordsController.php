<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRecordRequests\UpdateWeatherRecordRequest;
use App\Http\Requests\WeatherRecordRequests\WeatherRecordRequest;
use App\Http\Requests\WeatherRecordRequests\WeatherRecordsByDateRequest;
use App\Http\Resources\WeatherRecordResources\DetailWeatherRecordResource;
use App\Http\Resources\WeatherRecordResources\WeatherRecordResource;
use App\Interfaces\WeatherRecordRepositoryInterface;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WeatherRecordsController extends Controller
{
    private WeatherRecordRepositoryInterface $weatherRecordRepository;
    private ResponseService $responseService;

    public function __construct(
        WeatherRecordRepositoryInterface $weatherRecordRepository,
        ResponseService $responseService
    ){
        $this->weatherRecordRepository = $weatherRecordRepository;
        $this->responseService = $responseService;
    }

    public function getAll(): JsonResponse
    {
        $user = Auth::user();
        $weatherRecords = $this->weatherRecordRepository->getWeatherRecordsByUserId($user->id);

        return response()->json(
            $this->responseService->getOkResponse(
                '',
                WeatherRecordResource::collection($weatherRecords)
            )
        );
    }

    public function getAllByDate(WeatherRecordsByDateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $beginDate = Carbon::parse($request->get('begin_date'));
        $endDate = $request->has('end_date') ? Carbon::parse($request->get('end_date')) : null;

        $weatherRecords = $this->weatherRecordRepository->getWeatherRecordsByDate($user->id, $beginDate, $endDate);

        return response()->json(
            $this->responseService->getOkResponse(
                '',
                DetailWeatherRecordResource::collection($weatherRecords)
            )
        );
    }

    public function get(WeatherRecordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $weatherRecordId = $request->get('weather_record_id');

        $weatherRecord = $this->weatherRecordRepository->getWeatherRecord($weatherRecordId);

        if ($user->cannot('view', $weatherRecord)) {
            return response()->json(
                $this->responseService->getErrorResponse('The user is not allowed to make this action'),
                Response::HTTP_FORBIDDEN
            );
        }

        return response()->json(
            $this->responseService->getOkResponse(
                '',
                DetailWeatherRecordResource::make($weatherRecord)
            )
        );
    }

    public function update(UpdateWeatherRecordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $weatherRecordId = $request->get('weather_record_id');
        $weatherRecord = $this->weatherRecordRepository->getWeatherRecord($weatherRecordId);
        $weatherRecordData = $request->only([
            'temp',
            'feels_like',
            'humidity',
            'pressure',
            'temp_max',
            'temp_min',
        ]);

        if ($user->cannot('update', $weatherRecord)) {
            return response()->json(
                $this->responseService->getErrorResponse('The user is not allowed to make this action'),
                Response::HTTP_FORBIDDEN
            );
        }

        $weatherRecord = $this->weatherRecordRepository->updateWeatherRecord($weatherRecordId, $weatherRecordData);

        if ($weatherRecord) {
            return response()->json(
                $this->responseService->getOkResponse(
                    'Weather record has been updated successfully',
                    DetailWeatherRecordResource::make($weatherRecord)
                )
            );
        }

        return response()->json(
            $this->responseService->getErrorResponse('Cannot update weather record due to internal error'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function delete(WeatherRecordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $weatherRecordId = $request->get('weather_record_id');
        $weatherRecord = $this->weatherRecordRepository->getWeatherRecord($weatherRecordId);

        if ($user->cannot('delete', $weatherRecord)) {
            return response()->json(
                $this->responseService->getErrorResponse('The user is not allowed to make this action'),
                Response::HTTP_FORBIDDEN
            );
        }

        if ($this->weatherRecordRepository->deleteWeatherRecord($weatherRecordId)) {
            return response()->json(
                $this->responseService->getOkResponse('Weather record has been deleted successfully')
            );
        }

        return response()->json(
            $this->responseService->getErrorResponse('Cannot delete weather record due to internal error'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
