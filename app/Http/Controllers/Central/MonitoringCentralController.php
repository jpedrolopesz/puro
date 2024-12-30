<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Events\Central\WebSocketBroadcastEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonitoringCentralController extends Controller
{
    public function index()
    {
        //event(new WebSocketBroadcastEvent("Mensagem de monitoramento!"));
        return Inertia::render("Central/Monitoring/MonitoringCentral");
    }
}
