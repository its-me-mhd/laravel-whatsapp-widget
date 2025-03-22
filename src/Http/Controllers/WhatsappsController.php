<?php

namespace JeffersonGoncalves\WhatsappWidget\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JeffersonGoncalves\WhatsappWidget\Models\WhatsappAgent;

class WhatsappsController
{
    public function __invoke(Request $request): JsonResponse
    {
        if (!hash_equals(config('whatsapp-widget.key'), (string) $request->token)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $whatsappAgents = WhatsappAgent::query()
            ->where('active', true)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'image_url' => $agent->image_url ?? null,
                    'phone' => $agent->phone,
                    'link' => $agent->getLinkByWhatsappAgent($agent, config('whatsapp-widget.url')),
                ];
            });

        return response()->json($whatsappAgents);
    }
}
