<?php

namespace App\Services;

use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Log;

class GeneticAlgorithmService
{
    protected $assistantRepository;
    protected $scheduleRepository;

    // Parameter Algoritma Genetika
    protected int $populationSize = 50;
    protected int $maxGenerations = 1000;
    protected float $mutationRate = 0.05;
    protected int $tournamentSize = 5;

    protected $schedules;
    protected array $candidatePools = [];

    public function __construct(
        AssistantRepositoryInterface $assistantRepository,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->assistantRepository = $assistantRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function generateAssistantSchedule(array $filters = []): ?array
    {
        $this->prepareInitialData($filters);

        if (empty($this->schedules) || $this->schedules->isEmpty()) {
            throw new \Exception("Tidak ada jadwal praktikum yang ditemukan untuk semester dan tahun ajaran yang dipilih.");
        }

        foreach ($this->schedules as $schedule) {
            if (count($this->candidatePools[$schedule->id] ?? []) < 2) {
                throw new \Exception("Jadwal '{$schedule->name}' (" . optional($schedule->practicum)->name . ") tidak memiliki cukup kandidat asisten.");
            }
        }

        $population = $this->initializePopulation();
        $bestSolution = null;
        $bestFitnessResult = ['count' => PHP_INT_MAX, 'clashing_ids' => []];

        for ($generation = 0; $generation < $this->maxGenerations; $generation++) {
            $fitnessResults = [];
            foreach ($population as $index => $individual) {
                $fitnessResult = $this->calculateFitness($individual);
                $fitnessResults[$index] = $fitnessResult;

                if ($fitnessResult['count'] === 0) {
                    Log::info("Solusi sempurna ditemukan pada generasi ke-{$generation}.");
                    return $this->formatSolution($individual, $fitnessResult);
                }

                if ($fitnessResult['count'] < $bestFitnessResult['count']) {
                    $bestFitnessResult = $fitnessResult;
                    $bestSolution = $individual;
                }
            }

            $newPopulation = [];
            for ($i = 0; $i < $this->populationSize; $i++) {
                $parent1 = $this->selection($population, $fitnessResults);
                $parent2 = $this->selection($population, $fitnessResults);
                $child = $this->crossover($parent1, $parent2);
                $this->mutate($child);
                $newPopulation[] = $child;
            }
            $population = $newPopulation;
        }

        Log::warning("Max generations reached. Mengembalikan solusi terbaik yang ditemukan.", ['clashes' => $bestFitnessResult['count']]);
        return $this->formatSolution($bestSolution, $bestFitnessResult);
    }

    private function prepareInitialData(array $filters): void
    {
        $this->schedules = $this->scheduleRepository->getEmptyAssistantSchedules($filters);

        foreach ($this->schedules as $schedule) {
            $availableAssistants = $this->assistantRepository->getAssistantAvailableSchedules($schedule->id);
            $this->candidatePools[$schedule->id] = collect($availableAssistants)->pluck('nim')->toArray();
        }
    }

    private function initializePopulation(): array
    {
        $population = [];
        for ($i = 0; $i < $this->populationSize; $i++) {
            $individual = [];
            foreach ($this->schedules as $schedule) {
                $candidates = $this->candidatePools[$schedule->id];
                $keys = array_rand($candidates, 2);
                $individual[$schedule->id] = [$candidates[$keys[0]], $candidates[$keys[1]]];
            }
            $population[] = $individual;
        }
        return $population;
    }

    private function calculateFitness(array $individual): array
    {
        $clashes = 0;
        $clashingScheduleIds = [];
        $assistantTimeSlots = []; // Format: ['nim' => ['day_start_end' => 'schedule_id']]

        foreach ($individual as $scheduleId => $assistantNims) {
            $schedule = $this->schedules->find($scheduleId);
            $timeSlotIdentifier = $schedule->day->value . '_' . $schedule->start_time . '_' . $schedule->end_time;

            foreach ($assistantNims as $nim) {
                // Cek jika asisten sudah ada di slot waktu ini
                if (isset($assistantTimeSlots[$nim][$timeSlotIdentifier])) {
                    $clashes++;
                    // Tandai kedua jadwal yang bentrok
                    $clashingScheduleIds[] = $scheduleId; // Jadwal saat ini
                    $clashingScheduleIds[] = $assistantTimeSlots[$nim][$timeSlotIdentifier]; // Jadwal yang sudah ada sebelumnya
                } else {
                    $assistantTimeSlots[$nim][$timeSlotIdentifier] = $scheduleId;
                }
            }
        }

        return [
            'count' => $clashes,
            'clashing_ids' => array_unique($clashingScheduleIds)
        ];
    }

    private function selection(array $population, array $fitnessResults): array
    {
        $bestIndividual = null;
        $bestFitness = PHP_INT_MAX;
        for ($i = 0; $i < $this->tournamentSize; $i++) {
            $randomIndex = array_rand($population);
            if ($fitnessResults[$randomIndex]['count'] < $bestFitness) {
                $bestFitness = $fitnessResults[$randomIndex]['count'];
                $bestIndividual = $population[$randomIndex];
            }
        }
        return $bestIndividual;
    }

    private function crossover(array $parent1, array $parent2): array
    {
        $child = [];
        $keys = array_keys($parent1);
        $crossoverPoint = rand(1, count($keys) - 1);
        $child = array_slice($parent1, 0, $crossoverPoint, true);
        $child += array_slice($parent2, $crossoverPoint, null, true);
        return $child;
    }

    private function mutate(array &$individual): void
    {
        foreach ($individual as $scheduleId => &$assignments) {
            if ((mt_rand() / mt_getrandmax()) < $this->mutationRate) {
                $candidates = $this->candidatePools[$scheduleId];
                if (count($candidates) >= 2) {
                    $keys = array_rand($candidates, 2);
                    $assignments = [$candidates[$keys[0]], $candidates[$keys[1]]];
                }
            }
        }
    }

    private function formatSolution(array $individual, array $fitnessResult): array
    {
        $formatted = [
            'clashes' => $fitnessResult['count'],
            'clashing_schedule_ids' => $fitnessResult['clashing_ids'], // Pastikan kunci ini ditambahkan
            'assignments' => []
        ];

        foreach ($individual as $scheduleId => $nims) {
            $scheduleInfo = $this->schedules->find($scheduleId);
            $assistantsInfo = $this->assistantRepository->getAssistantsByNims($nims);

            $formatted['assignments'][] = [
                'schedule_id' => $scheduleId,
                'schedule_name' => $scheduleInfo->name,
                'practicum_name' => optional($scheduleInfo->practicum)->name,
                'day' => $scheduleInfo->day,
                'time' => $scheduleInfo->start_time . ' - ' . $scheduleInfo->end_time,
                'assistant_nims' => $nims,
                'assistant_names' => $assistantsInfo->pluck('name')->all()
            ];
        }

        return $formatted;
    }
}
