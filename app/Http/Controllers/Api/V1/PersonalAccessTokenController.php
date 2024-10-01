<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Traits\ControllerTraits;
use App\Traits\TokenTraits;
use Carbon\Carbon;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PersonalAccessTokenController extends Controller
{

    use ControllerTraits, TokenTraits;

    public function generateToken(Request $request)
    {

        $returnMessage = [];

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'tokenName' => ['required', 'string', Rule::in(['employee-token'])],
        ]);

        try {
            $employee = Employee::where('email', $request->email)->first();

            if ($employee && Hash::check($request->password, $employee->password)) {
                $token = $employee->createToken($request->tokenName);

                // need to implement token expiration
//                $expiresAt = Carbon::now()->addMinutes(5);

                $returnMessage = [
                    'success' => true,
                    'message' => "Token Generated",
                    'data' => [
                        'token' => $token->plainTextToken,
//                        'expiresAt' => $expiresAt->toDateTimeString(),
                    ]
                ];
            } else {
                // Invalid credentials
                $returnMessage = [
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'data' => []
                ];
            }

        } catch (\Throwable $throwable) {
            $returnMessage = [
                'success' => false,
                'message' => $throwable->getMessage(),
                'data' => []
            ];

            if ($this->debuggable()) {
                $returnMessage['debug'] = $throwable->getMessage();
            }
        }

        return response()->json($returnMessage, 200);
    }
}
