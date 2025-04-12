<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Models\User;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AssistantRepository implements AssistantRepositoryInterface
{
    public function getAllAssistants($sortBy = 'nim', $order = 'asc')
    {
        return Assistant::with('user', 'courseSchedules', 'assistantSchedules')
            ->orderBy($sortBy, $order)
            ->get();
    }

    public function getAssistantByNim($nim)
    {
        return Assistant::with('user', 'courseSchedules', 'assistantSchedules')->where('nim', $nim)->first();
    }

    public function storeAssistant(array $data)
    {
        $user = User::create([
            'username' => $data['nim'],
            'password' => Hash::make($data['password']),
        ]);

        $assistant = Assistant::create([
            'name' => $data['name'],
            'nim' => $data['nim'],
            'prodi' => $data['prodi'],
            'angkatan' => $data['angkatan'],
            'tahun_masuk' => $data['tahun_masuk'],
            'user_id' => $user->id,
        ]);

        return $assistant;
    }
}