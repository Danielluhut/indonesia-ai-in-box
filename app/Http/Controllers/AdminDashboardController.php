<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get Machines
        $machinesResponse = Http::get('https://api-dummy.hpc-hs.my.id/docker/machine');
        $machines = $machinesResponse->successful() ? ($machinesResponse->json()['data'] ?? []) : [];

        // Get Packages
        $packagesResponse = Http::get('https://api-dummy.hpc-hs.my.id/docker/package');
        $packages = $packagesResponse->successful() ? ($packagesResponse->json()['data'] ?? []) : [];

        // Ambil semua user (misalnya dari tabel users)
        $users = \App\Models\User::where('role', 'pengguna')->get(); // kalau pakai model User
        // atau kalau ada endpoint API user, tinggal pakai Http::get juga

        $allContainers = [];

        foreach ($users as $user) {
            $containersResponse = Http::get('https://api-dummy.hpc-hs.my.id/docker/container', [
                'machine' => 'machine 1',
                'user' => $user->id,
            ]);

            if ($containersResponse->successful()) {
                $allContainers[$user->id] = $containersResponse->json()['data'] ?? [];
            } else {
                $allContainers[$user->id] = []; // kalau error tetap isi kosong
            }
        }

        return view('admin.admin', compact('machines', 'packages', 'allContainers', 'users'));
    }

    public function storeMachine(Request $request)
    {
        Log::info('Masuk ke storeMachine!', $request->all());
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'ip' => 'required|string',
        ]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/machine', [
            'name' => $request->name,
            'location' => $request->location,
            'ip' => $request->ip,
        ]);

        Log::info('Response storeMachine', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return redirect()->route('admin.dashboard')->with(
            $response->successful() ? 'success' : 'error',
            $response->successful() ? 'Machine berhasil ditambahkan.' : 'Gagal menambahkan machine.'
        );
    }

    public function removeMachine(Request $request)
    {
        Log::info('Masuk ke removeMachine!', $request->all());

        $request->validate([
            'ip' => 'required|string',
            'name' => 'required|string',
        ]);

        $response = Http::asForm()->delete('https://api-dummy.hpc-hs.my.id/docker/machine', [
            'ip' => $request->ip,
            'name' => $request->name,
        ]);

        Log::info('Response removeMachine', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->with(
            $response->successful() ? 'success' : 'error',
            $response->successful() ? 'Machine berhasil dihapus.' : 'Gagal menghapus machine.'
        );
    }


    public function containerAction(Request $request)
    {
        $request->validate([
            'Action' => 'required|string',
            'id_container' => 'required|string',
        ]);

        $action = strtolower($request->Action);
        $id = $request->id_container;

        Log::info("ADMIN: Request action '{$action}' for container", [
            'admin_id' => auth()->id(),
            'id_container' => $id
        ]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
            'Action' => $action,
            'id_containter' => $id,
        ]);

        Log::info("ADMIN: API response '{$action}'", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->successful()) {
            return back()->with('success', "Action {$action} untuk container {$id} berhasil.");
        } else {
            Log::error("ADMIN: Gagal action '{$action}' pada container", [
                'id_container' => $id,
                'response_status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            return back()->with('error', "Gagal menjalankan action {$action} pada container {$id}. Detail telah dicatat.");
        }
    }

    public function serviceAction(Request $request)
    {
        $request->validate([
            'Action' => 'required|string',
            'ids' => 'required|string', // JSON array of container IDs
        ]);

        $action = strtolower($request->Action);
        $ids = json_decode($request->ids, true);

        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada container untuk diproses.');
        }

        Log::info("ADMIN: Request SERVICE-LEVEL action '{$action}'", [
            'admin_id' => auth()->id(),
            'containers' => $ids,
        ]);

        $allSuccess = true;

        foreach ($ids as $id) {
            $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
                'Action' => $action,
                'id_containter' => $id,
            ]);

            Log::info("ADMIN: API response '{$action}' untuk container {$id}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if (!$response->successful()) {
                $allSuccess = false;
                Log::error("ADMIN: Gagal action '{$action}' pada container", [
                    'id_container' => $id,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            }
        }

        return back()->with(
            $allSuccess ? 'success' : 'error',
            $allSuccess
            ? "{$action} berhasil dijalankan pada semua container di service."
            : "Sebagian {$action} gagal, detail error sudah dicatat."
        );
    }
}