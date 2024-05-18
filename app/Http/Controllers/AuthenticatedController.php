<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedController extends Controller
{
    protected $guard;

    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    public function checkAuth()
    {
        try {
            $user = auth()->user();
            if ($user) {
                return response()->json(['authenticated' => true, 'user' => User::with('customer')->where('id', $user->id)->first()]);
            }
        } catch (\Exception $e) {
            // Token is invalid
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function refreshToken()
    {
        try {
            if (!$token = JWTAuth::getToken()) {
                return response()->json(['error' => 'Token not provided'], 401);
            }

            try {
                $newToken = JWTAuth::refresh($token);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Token cannot be refreshed'], 401);
            }

            // You might want to include additional user data in the response here
            return response()->json([
                'access_token' => $newToken,
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        $response = null;
        DB::transaction(function () use ($request, &$response) {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $response = response()->json(['validation' => $validator->errors()], 422);

                return;
            }

            $customer = Customers::where('phone', $request->get('phone'))
                                    ->first();

            if ($customer) {
                if ($customer->user_id === null) {
                    // Create the user
                    $user = User::create([
                        'name' => $request->get('name'),
                        'email' => $request->get('email'),
                        'password' => Hash::make($request->get('password')),
                    ]);
                    Customers::where('phone', $request->get('phone'))->update(['user_id' => $user->id]);
                } else {
                    $response = response()->json(['error' => 'Phone number already in use.'], 422);

                    return;
                }
            } else {
                // Create the user
                $user = User::create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
                ]);
                Customers::create([
                    'user_id' => $user->id,
                    'phone' => $request->get('phone'),
                ]);
            }

            $loginRequest = Request::create('/login', 'POST', [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            $response = $this->login($loginRequest);
        });

        return $response ?: response()->json(['error' => 'Unknown error occurred'], 500);
    }

    public function login(Request $request)
    {
        try {
            return $this->loginPipeline($request)->then(function ($request) {
                $credentials = $request->only('email', 'password');

                if (!$token = Auth::guard('api')->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                return response()->json([
                    'access_token' => $token, // Although this is not used if HTTP-only cookies are set
                    'token_type' => 'bearer',
                    'expires_in' => JWTAuth::factory()->getTTL() * 60,
                    'user' => auth()->user(), // Return the user object in the response
                ])->cookie(
                    'token', // name of the cookie
                    $token, // the JWT token
                    60, // expiration in minutes
                    '/', // path
                    null, // domain
                    true, // secure
                    true, // HttpOnly
                    false, // raw
                    'Lax' // sameSite
                );
            });
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    protected function loginPipeline(Request $request)
    {
        if (Fortify::$authenticateThroughCallback) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                call_user_func(Fortify::$authenticateThroughCallback, $request)
            ));
        }

        if (is_array(config('fortify.pipelines.login'))) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                config('fortify.pipelines.login')
            ));
        }

        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }

    // protected function createNewToken($token)
    // {
    //     return [
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => JWTAuth::factory()->getTTL() * 60,
    //         'user' => auth()->user(),
    //     ];
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {
            if ($token = JWTAuth::getToken()) {
                JWTAuth::invalidate($token);
            }
            $this->guard->logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            // Prepare a response for successful logout
            $response = response()->json(['message' => 'Successfully logged out']);

            // Forget the JWT token cookie
            $cookie = Cookie::forget('token'); // 'token' is the name of the cookie

            // Add cookie to response to ensure it's cleared on the client side
            return $response->withCookie($cookie);
        } catch (TokenInvalidException $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => 'Invalid token'], 400);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => 'An error occurred during logout'], 500);
        }
    }
}
