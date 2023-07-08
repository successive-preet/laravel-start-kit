<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ApiGenericException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;
use Laravel\Passport\RefreshTokenRepository;
use App\Http\Requests\Api\{UserRegisterRequest, UserLoginRequest, RefreshTokenRequest};

use App\Services\TokenService;

use App\Interfaces\{UserRepositoryInterface};

class AuthController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle account registration api request
     * 
     * @param UserRegisterRequest $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function register(UserRegisterRequest $request, TokenService $tokenService) 
    {
        $response = array();
        try{
            
            $data = $request->all();
            // create user
            $user = $this->userRepository->create($data);

            // autologin field will be need to check if the user needs to be logged in after registration or not
            if($request->autologin === '1') {

                // get access token and refresh token
                $tokenResponse = $tokenService->getAuthTokens($request);

                if (!$tokenResponse->ok()) {
                    $user->delete();
                    throw new ApiGenericException('Unexpected Exception. Try later', config('constant.STATUS_CODE.statusUnauthorized'));   

                }

                auth()->login($user);
                
                // send tokens
                $response['tokens'] =  json_decode($tokenResponse->getBody(), true);
            }

            // send user information
            $response['name']  =  $user->name;
            return $response;   
        }
        catch(Exception $e){
            throw new ApiGenericException('Unexpected Exception. Try later', config('constant.STATUS_CODE.statusUnauthorized')); 
        }
    }

     /**
     * Handle account login request
     * 
     * @param UserLoginRequest $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(UserLoginRequest $request, TokenService $tokenService)
    {
        $response = array();
        // check email and password exists or not
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

            // get access token and refresh token
            $tokenResponse = $tokenService->getAuthTokens($request);

            if (!$tokenResponse->ok()) {
                throw new ApiGenericException('Unexpected Exception. Try later', config('constant.STATUS_CODE.statusUnauthorized')); 
            }

            $user = Auth::user();

            // send user information and tokens
            $response['name']   =  $user->name;
            $response['tokens'] =  json_decode($tokenResponse->getBody(), true);
            return $response;

        } 
        else{ 
            throw new ApiGenericException('Invalid Credentials', config('constant.STATUS_CODE.statusUnauthorized'));   
        } 
    }

    /**
     * Function to get access token and refresh token
     * @param RefreshTokenRequest $request
     * @param TokenService $tokenService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function refreshToken(RefreshTokenRequest $request, TokenService $tokenService)
    {
        try{

            $response = $tokenService->getAuthTokens($request);

            if (!$response->ok()) {
                throw new ApiGenericException('Unexpected Exception. Try later', config('constant.STATUS_CODE.statusUnauthorized')); 
            }

            return json_decode($response->getBody(), true);

        }
        catch (Exception $e) {
            throw new ApiGenericException('Unexpected Exception. Try later', config('constant.STATUS_CODE.statusUnauthorized')); 
        }

    }

    /**
     * Logout User, Revole tokens
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function logout()
    {
        $response = array();
        $token = Auth::user()->token();

        /* --------------------------- revoke access token -------------------------- */
        $token->revoke();
        $token->delete();

        /* -------------------------- revoke refresh token -------------------------- */
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        $response['message'] = 'User Logout Successfully';

        return $response;

    }

}