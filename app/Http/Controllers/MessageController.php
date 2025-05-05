<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    /**
     * @param MessageService $messageService
     */
    public function __construct(
        private readonly MessageService $messageService
    )
    {
    }

    /**
     * vai listar todos os usuarios cadastrados exceto o auth user;
     * todos os usuarios que forem lsitados podem conversar (trocar mensagens)
     *
     * @param Request $request
     * @return void
     */
    public function listUsersMessages(int $userTo, Request $request)
    {
        try {
            $messages = $this->messageService->listUsersMessages($userTo, $request->query());
            //TODO event dispacther broadcast reverb
            return response()->json($messages, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar mensagens.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * usuario logado envia mensagem para outro usuario selecionado pelo id
     *
     * @param int $userTo
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMessage(int $userTo, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
        ]);
        try {
            $send = $this->messageService->sendMessage($userTo, $data);
            return response()->json($send, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao enviar mensagens.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Listar todas as mensagens do grupo, inicialamente temos apenas o FURIA TEAM;
     *
     * @param int $team
     * @param Request $request
     * @return JsonResponse
     */
    public function listTeamMessages(int $team, Request $request)
    {
        try {
            $messages = $this->messageService->listTeamMessages($team, $request->query());
            //TODO event dispacther broadcast reverb
            return response()->json($messages, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar mensagens.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * usuario logado envia mensagens para o grupo, inicialmente temos apenas o FURIA TEAM;
     *
     * @param int $team
     * @param Request $request
     * @return JsonResponse
     */
    public function sendTeamMessage(int $team, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
        ]);
        try {
            $send = $this->messageService->sendTeamMessage($team, $data);
            return response()->json($send, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao enviar mensagens.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }
}
