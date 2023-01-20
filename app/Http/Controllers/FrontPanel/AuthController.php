<?php

namespace App\Http\Controllers\FrontPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontPanel\SignInRequest;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function index(): Response
    {
        $title = 'Account Login';
        return response()->view('FrontPanel.auth', compact('title'), Response::HTTP_OK);
    }

    public function authenticate(Request $request) : JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'for' => 'Account Panel', 'status' => 'Active'], (int)$request->remember_me)) {
                $request->session()->regenerate();
                return response()->json(['message' => 'Authorized Access'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Unauthorized Access!'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');

    }
}
