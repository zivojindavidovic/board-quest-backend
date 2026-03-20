<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterClubRequest;
use App\Http\Resources\RegisterClubResource;
use App\Services\RegisterClubService;
use Illuminate\Http\Request;
use Throwable;

final class ClubsController extends Controller
{
    /**
     * Creates new club
     *
     * @throws Throwable
     */
    public function register(RegisterClubRequest $request, RegisterClubService $service)
    {
        $result = $service->execute($request->validated());
        return new RegisterClubResource($result);
    }
}
