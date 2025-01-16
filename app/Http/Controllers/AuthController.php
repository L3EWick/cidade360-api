<?php
namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Mail\LoginTokenMail;
use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Models\LoginToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'cpf' => 'required|unique:users,cpf',
                'telefone' => 'required',
                'data_nascimento' => 'required|date',
            ]);

            $user = User::create(array_merge($validated, ['email_verificado' => false]));

            $token = random_int(100000, 999999);

            EmailVerificationToken::create([
                'user_id' => $user->id,
                'token' => $token,
            ]);

            Mail::to($user->email)->send(new VerificationMail($token));

            return response()->json([
                'success' => true,
                'message' => 'Usuário registrado com sucesso. Verifique seu email para concluir o cadastro.',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro no registro de usuário: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao registrar o usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'token' => 'required|string|size:6',
            ]);

            $verificationToken = EmailVerificationToken::where('token', $validated['token'])
                ->whereHas('user', function ($query) use ($validated) {
                    $query->where('email', $validated['email']);
                })
                ->first();

            if (!$verificationToken) {
                return response()->json(['success' => false, 'message' => 'Token inválido, email incorreto ou expirado.'], 400);
            }

            $user = $verificationToken->user;
            $user->update(['email_verificado' => true]);

            $verificationToken->delete();

            return response()->json(['success' => true, 'message' => 'Email verificado com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error('Erro na verificação de email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao verificar o email.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function requestLogin(Request $request)
    {
        try {
            $validated = $request->validate([
                'identifier' => 'required',
            ]);

            $user = User::where('email', $validated['identifier'])
                ->orWhere('cpf', $validated['identifier'])
                ->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuário não encontrado.'], 404);
            }

            $loginToken = random_int(100000, 999999);
            $expiresAt = now()->addMinutes(10);

            LoginToken::updateOrCreate(
                ['user_id' => $user->id],
                ['token' => $loginToken, 'expires_at' => $expiresAt]
            );

            Mail::to($user->email)->send(new LoginTokenMail($loginToken));

            return response()->json([
                'success' => true,
                'message' => 'Token de login enviado para seu email.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro no envio do token de login: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao solicitar o token de login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'identifier' => 'required',
                'token' => 'required|string|size:6',
            ]);

            $user = User::where('email', $validated['identifier'])
                ->orWhere('cpf', $validated['identifier'])
                ->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuário não encontrado.'], 404);
            }

            $loginToken = LoginToken::where('user_id', $user->id)
                ->where('token', $validated['token'])
                ->where('expires_at', '>=', now())
                ->first();

            if (!$loginToken) {
                return response()->json(['success' => false, 'message' => 'Token inválido ou expirado.'], 400);
            }

            $loginToken->delete();
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso.',
                'jwt_token' => $token,
                'token' => $request->token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'cpf' => $user->cpf,
                    'telefone' => $user->telefone,
                    'data_nascimento' => $user->data_nascimento,
            ]], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao efetuar login: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao efetuar o login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}