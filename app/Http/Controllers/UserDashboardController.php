<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Container;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;
        $machine = $user->machine_name ?? 'machine 1';

        $response = Http::get('https://api-dummy.hpc-hs.my.id/docker/container', [
            'machine' => $machine,
            'user' => $userId
        ]);

        $services = [];

        if ($response->successful() && isset($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $entry) {
                $serviceName = $entry['service'] ?? '(Unknown Service)';
                $machineId = $entry['machine'] ?? '(Unknown Machine)';
                $containers = [];

                foreach ($entry['container_data'] ?? [] as $container) {
                    $containers[] = [
                        'id' => $container['id_container'] ?? '-',
                        'name' => $container['container_name'] ?? '-',
                        'loc_ip' => $container['loc_ip'] ?? '-',
                        'ext_ip' => $container['ext_ip'] ?? '-',
                        'status' => 'Running'
                    ];
                }

                $services[] = [
                    'name' => $serviceName,
                    'machine' => $machineId,
                    'containers' => $containers
                ];
            }
        }

        return view('user.user', compact('services'));
    }

    public function start(Request $request, $id)
    {
        Log::info("User request START container", ['id' => $id]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
            'Action' => 'start',
            'id_containter' => $id
        ]);

        Log::info("API Response START", [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            return redirect()->route('user.dashboard')->with('success', "Container $id berhasil di-Start.");
        } else {
            return back()->with('error', "Gagal Start container $id. API Response: " . $response->body());
        }
    }

    public function stop(Request $request, $id)
    {
        Log::info("User request STOP container", ['id' => $id]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
            'Action' => 'stop',
            'id_containter' => $id
        ]);

        Log::info("API Response STOP", [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            return redirect()->route('user.dashboard')->with('success', "Container $id berhasil di-Stop.");
        } else {
            return back()->with('error', "Gagal Stop container $id. API Response: " . $response->body());
        }
    }

    public function serviceAction(Request $request)
    {
        $request->validate([
            'Action' => 'required|in:start,stop',
            'ids' => 'required',
        ]);

        $action = strtolower($request->input('Action'));
        $idsRaw = $request->input('ids');

        // Parse JSON jika perlu
        $containerIds = is_string($idsRaw) ? json_decode($idsRaw, true) : $idsRaw;

        if (!is_array($containerIds) || empty($containerIds)) {
            return back()->with('error', 'Tidak ada container yang diproses.');
        }

        Log::info("USER: Request {$action} all containers", [
            'user_id' => auth()->id(),
            'containers' => $containerIds,
        ]);

        foreach ($containerIds as $id) {
            $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/container', [
                'Action' => $action,
                'id_containter' => $id,
            ]);

            Log::info("USER: Response {$action} container {$id}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if (!$response->successful()) {
                Log::error("USER: Gagal {$action} container", [
                    'id_container' => $id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        }

        return back()->with('success', "Aksi " . strtoupper($action) . " berhasil dilakukan pada semua container.");
    }

    public function showInstallForm()
    {
        $response = Http::get('https://api-dummy.hpc-hs.my.id/docker/package');
        $packages = $response->successful() ? ($response->json()['data'] ?? []) : [];

        return view('user.install-package', compact('packages'));
    }

    public function installPackage(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'key' => 'required|string',
        ]);

        Log::info('USER install package request', [
            'user_id' => auth()->id(),
            'id' => $request->id,
            'key' => $request->key,
        ]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/package', [
            'id' => $request->id,
            'key' => $request->key,
        ]);

        Log::info('USER install package response', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->successful()) {
            // ✅ Berhasil → ke dashboard
            return redirect()->route('user.dashboard')->with('success', 'Package berhasil diinstall.');
        } else {
            // ❌ Gagal → tetap di halaman install
            return back()->with('error', 'Gagal menginstall package. Cek kembali ID dan key.');
        }
    }
}