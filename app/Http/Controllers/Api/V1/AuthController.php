<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Traits\ControllerTraits;
use App\Traits\TokenTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ControllerTraits, TokenTraits;

    private array $returnMessage = [
        'success' => false,
        'message' => 'An error occurred',
        'data' => [],
    ];
    private int $returnMessageStatus = Response::HTTP_BAD_REQUEST;

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'deviceName' => ['required', 'string'],
        ]);

        try {
            $employee = Employee::where('email', $request->email)->first();

            if ($employee && Hash::check($request->password, $employee->password)) {
                $token = $employee->createToken($request->deviceName);

                // Set token expiration (e.g., 60 minutes from now)
                $token->accessToken->expires_at = now()->addDay();
                $token->accessToken->save();

                $this->returnMessage = [
                    'success' => true,
                    'message' => "Token Generated",
                    'data' => [
                        'token' => $token->plainTextToken,
                        'tokenName' => $token->accessToken->name,
                        'expiresAt' => $token->accessToken->expires_at->toDateTimeString(),
                    ]
                ];
                $this->returnMessageStatus = 200;
            } else {
                // Invalid credentials
                $this->returnMessage = [
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'data' => []
                ];
                $this->returnMessageStatus = 401;
            }

        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred during authentication!',
                'data' => []
            ];
            if ($this->debuggable()) {
                $this->returnMessage['debug'] = $throwable->getMessage();
            }
            $this->returnMessageStatus = 500;
        }

        return response()->json($this->returnMessage, $this->returnMessageStatus);
    }

    public function logout(Request $request)
    {
        try {
            // Delete the current access token
            $request->user()->currentAccessToken()->delete();

            $this->returnMessage = [
                'success' => true,
                'message' => "Logged out successfully",
                'data' => []

            ];
            $this->returnMessageStatus = 200;

        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred during logout!',
                'data' => []
            ];
            if ($this->debuggable()) {
                $this->returnMessage['debug'] = $throwable->getMessage();
            }
            $this->returnMessageStatus = 500;
        }

        return response()->json($this->returnMessage, $this->returnMessageStatus);
    }

    public function logoutFromAll(Request $request)
    {
        try {
            // Fetch the authenticated user using request()->user() (Sanctum should handle this automatically)
            $employee = $request->user();

            // Ensure that we have an authenticated employee
            if ($employee) {
                // Delete all tokens associated with the employee (logs out from all sessions)
                $employee->tokens()->delete();

                // Success message
                $this->returnMessage = [
                    'success' => true,
                    'message' => "Logged out from all sessions successfully",
                    'data' => []
                ];
                $this->returnMessageStatus = Response::HTTP_OK;
            } else {
                // Handle case where no user is authenticated
                $this->returnMessage = [
                    'success' => false,
                    'message' => 'No user is currently authenticated',
                    'data' => []
                ];
                $this->returnMessageStatus = Response::HTTP_UNAUTHORIZED;
            }

        } catch (\Throwable $throwable) {
            // Error handling
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred during logout from all sessions!',
                'data' => []
            ];
            if ($this->debuggable()) {
                $this->returnMessage['debug'] = $throwable->getMessage();
            }
            $this->returnMessageStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->returnMessage, $this->returnMessageStatus);
    }

    // need to implement more robust authentication with laravel passport in the future for things like refreshing tokens

}
