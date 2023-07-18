<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function userRegistration(Request $request)
    {
        try {
            $user = new User;
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = $request->password;
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => 'User registered successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function userLogin(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if ($user->password == $request->password) {
                    $token = JWTToken::generateToken($user);
                    return response()->json([
                        'status' => 200,
                        'message' => 'User logged in successfully',
                        'token' => $token,
                    ]);
                } else {
                    return response()->json([
                        'status' => 401,
                        'message' => 'Invalid credentials',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function sendOTPCode(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $otp = rand(1000, 9999);
                $user->otp = $otp;
                $user->save();
                $mail = new OTPMail($otp);
                Mail::to($user->email)->send($mail);
                return response()->json([
                    'status' => 200,
                    'message' => 'OTP sent successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function verifyOTPCode(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if ($user->otp == $request->otp) {
                    $token = JWTToken::generateToken($user);
                    $user->otp = 0;
                    $user->save();

                    return response()->json([
                        'status' => 200,
                        'message' => 'OTP verified successfully',
                        'token' => $token,
                    ]);
                } else {
                    return response()->json([
                        'status' => 401,
                        'message' => 'Invalid credentials',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $email = $request->header('userEmail');
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->password = $request->password;
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Password reset successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
            ]);
        }
    }
}
