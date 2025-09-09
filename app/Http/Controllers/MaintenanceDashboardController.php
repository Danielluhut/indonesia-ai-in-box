<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MaintenanceDashboardController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role 'pengguna'
        $users = \App\Models\User::where('role', 'pengguna')->get();

        $allContainers = [];

        foreach ($users as $user) {
            $containersResponse = Http::get('https://api-dummy.hpc-hs.my.id/docker/container', [
                'machine' => 'machine 1', // bisa juga nanti diganti dynamic kalau ada banyak machine
                'user' => $user->id,
            ]);

            if ($containersResponse->successful()) {
                $allContainers[$user->id] = $containersResponse->json()['data'] ?? [];
            } else {
                $allContainers[$user->id] = [];
            }
        }

        return view('maintenance.maintenance', compact('allContainers', 'users'));
    }

    public function reset(Request $request)
    {
        $id = $request->input('id_container');

        Log::info("MAINTENANCE: User request RESET container", [
            'id_container' => $id
        ]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
            'Action' => 'reset',
            'id_containter' => $id // typo diperbaiki dari 'id_containter'
        ]);

        Log::info("MAINTENANCE: API response RESET", [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            return redirect()->route('maintenance.dashboard')->with('status', "Container {$id} berhasil direset.");
        } else {
            return redirect()->route('maintenance.dashboard')->with('status', "Gagal reset container {$id}. Silakan cek log.");
        }
    }

    public function resetAll(Request $request)
    {
        $request->validate([
            'ids' => 'required|string', // akan dikirim dalam bentuk JSON string
        ]);

        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids) || empty($ids)) {
            return back()->with('status', 'Tidak ada container yang dipilih untuk reset.');
        }

        Log::info("MAINTENANCE: Request reset all containers", [
            'maintenance_id' => auth()->id(),
            'containers' => $ids
        ]);

        $allSuccess = true;

        foreach ($ids as $id) {
            $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
                'Action' => 'reset',
                'id_containter' => $id
            ]);

            Log::info("MAINTENANCE: Response reset container {$id}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                $allSuccess = false;
                Log::error("MAINTENANCE: Gagal reset container {$id}", [
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);
            }
        }

        return redirect()->route('maintenance.dashboard')->with(
            'status',
            $allSuccess
            ? 'Service berhasil direset.'
            : 'Sebagian container gagal direset. Silakan cek log.'
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

        // ambil nama container dari query string
        $name = request()->query('name', 'Unknown');

        return view('maintenance.log', compact('id', 'name', 'log'));
    }
}
