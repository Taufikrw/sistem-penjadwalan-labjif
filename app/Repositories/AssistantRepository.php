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

    public function getAssistantByNimIncludeTrashedWithUser($nim)
    {
        return Assistant::with('user')->withTrashed()->where('nim', $nim)->first();
    }

    public function getUserByNimIncludeTrashed($nim)
    {
        return User::withTrashed()->where('username', $nim)->first();
    }

    public function storeAssistant(array $data)
    {
        $existing = Assistant::where('nim', $data['nim'])->first();
        if ($existing) {
            throw new \Exception('Assistant with this NIM already exists.');
        }
        
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

    public function updateAssistant($nim, array $data)
    {
        $assistant = $this->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        if (isset($data['nim']) && $data['nim'] !== $assistant->nim) {
            $existing = Assistant::where('nim', $data['nim'])->first();
            if ($existing) {
                throw new \Exception('Assistant with this NIM already exists.');
            }
            $assistant->user->update(['username' => $data['nim']]);
            $assistant->update(['nim' => $data['nim']]);
        }

        $assistant->update([
            'name' => $data['name'],
            'prodi' => $data['prodi'],
            'angkatan' => $data['angkatan'],
            'tahun_masuk' => $data['tahun_masuk'],
        ]);

        if (isset($data['password'])) {
            if ($assistant->user) {
                $assistant->user->update(['password' => Hash::make($data['password'])]);
            }
        }

        return $assistant;
    }

    public function deleteAssistant($nim)
    {
        $assistant = $this->getAssistantByNim($nim);
        $user = $this->getUserByNimIncludeTrashed($nim);

        if (!$assistant) {
            return null;
        }

        $assistant->delete();
        $user->delete();

        return true;
    }
}