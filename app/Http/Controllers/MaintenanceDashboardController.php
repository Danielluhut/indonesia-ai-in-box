<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MaintenanceDashboardController extends Controller
{
    public function index()
    {
        $response = Http::get('https://api-dummy.hpc-hs.my.id/docker/container?machine=machine 1&user=all');
        $containers = $response->successful() ? ($response->json()['data'] ?? []) : [];

        return view('maintenance.maintenance', compact('containers'));
    }

    public function restart(Request $request)
    {
        $id = $request->input('id_container');

        Log::info("MAINTENANCE: User request RESTART container", [
            'id_container' => $id
        ]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
            'Action' => 'restart',
            'id_containter' => $id // typo diperbaiki dari 'id_containter'
        ]);

        Log::info("MAINTENANCE: API response RESTART", [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            return redirect()->route('maintenance.dashboard')->with('status', "Container {$id} berhasil direstart.");
        } else {
            return redirect()->route('maintenance.dashboard')->with('status', "Gagal restart container {$id}. Silakan cek log.");
        }
    }

    public function restartAll(Request $request)
    {
        $request->validate([
            'ids' => 'required|string', // akan dikirim dalam bentuk JSON string
        ]);

        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids) || empty($ids)) {
            return back()->with('status', 'Tidak ada container yang dipilih untuk restart.');
        }

        Log::info("MAINTENANCE: Request restart all containers", [
            'maintenance_id' => auth()->id(),
            'containers' => $ids
        ]);

        $allSuccess = true;

        foreach ($ids as $id) {
            $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
                'Action' => 'restart',
                'id_containter' => $id
            ]);

            Log::info("MAINTENANCE: Response restart container {$id}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                $allSuccess = false;
                Log::error("MAINTENANCE: Gagal restart container {$id}", [
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);
            }
        }

        return redirect()->route('maintenance.dashboard')->with(
            'status',
            $allSuccess
            ? 'Semua container berhasil direstart.'
            : 'Sebagian container gagal direstart. Silakan cek log.'
        );
    }

    public function log($id)
    {
        Log::info("MAINTENANCE: View log for container", ['id_container' => $id]);

        $response = Http::get("https://api-dummy.hpc-hs.my.id/docker/container/logs?id_container={$id}");

        if ($response->successful()) {
            $json = $response->json();
            $log = $json['content'] ?? 'Log kosong.';
        } else {
            $log = 'Tidak dapat mengambil log.';
        }

        return view('maintenance.log', compact('id', 'log'));
    }
}
