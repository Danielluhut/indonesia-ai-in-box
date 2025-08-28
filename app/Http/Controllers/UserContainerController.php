<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserContainerController extends Controller
{
    // Form untuk install package
    public function create()
    {
        $response = Http::get('https://api-dummy.hpc-hs.my.id/docker/package');

        $result = $response->json();

        // Jika API tidak berhasil, set kosong
        $packages = $result['data'] ?? [];

        return view('user.containers.create', compact('packages'));
    }


    // Store: kirim ke API install package
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'key' => 'required'
        ]);

        $response = Http::asForm()->post('https://api-dummy.hpc-hs.my.id/docker/package', [
            'id' => $request->id,
            'key' => $request->key,
            'machine' => auth()->user()->machine_name,  // kirim machine
            'user' => auth()->id(),                     // kirim user id
        ]);

        // Debug respons install
        if ($response->successful()) {
            return redirect()->route('user-dashboard')->with('success', 'Package berhasil di install.');
        } else {
            return back()->with('error', 'Gagal install container. Respons API: ' . $response->body());
        }
    }

    public function start($id)
    {
        Http::asForm()->post("https://api-dummy.hpc-hs.my.id/docker/container", [
            'Action' => 'start',
            'id_container' => $id
        ]);

        return redirect()->back()->with('success', 'Container berhasil dinyalakan.');
    }

    public function stop($id)
    {
        Http::asForm()->post("https://api-dummy.hpc-hs.my.id/docker/container", [
            'Action' => 'stop',
            'id_container' => $id
        ]);

        return redirect()->back()->with('success', 'Container berhasil dihentikan.');
    }

    public function destroy($id)
    {
        // Tidak ada API delete, tampilkan pesan
        return redirect()->back()->with('success', 'Fitur hapus container belum tersedia.');
    }
}
