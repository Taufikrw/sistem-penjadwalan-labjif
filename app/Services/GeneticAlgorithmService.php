<?php

namespace App\Services;

use App\Models\Preference;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Log;

class GeneticAlgorithmService
{
    protected const CLASH_PENALTY = 1000;
    protected const PREFERENCE_BONUS = 20;
    protected const DISTRIBUTION_PENALTY = 50;

    protected $assistantRepository;
    protected $scheduleRepository;

    // Parameter Algoritma Genetika
    protected int $populationSize = 50;
    protected int $maxGenerations = 1000;
    protected float $mutationRate = 0.05;
    protected int $tournamentSize = 5;

    protected $schedules;
    protected array $candidatePools = [];
    protected array $assistantPreferences = [];

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
                throw new \Exception("Jadwal '{$schedule->name}' (" . optional($schedule->practicum)->name . ") tidak memiliki cukup kandidat asisten (minimal 2).");
            }
        }

        $population = $this->initializePopulation();
        $bestSolution = $population[0];
        $bestFitnessScore = 0;

        for ($generation = 0; $generation < $this->maxGenerations; $generation++) {
            $fitnessScores = [];
            foreach ($population as $index => $individual) {
                $fitnessScore = $this->calculateFitness($individual);
                $fitnessScores[$index] = $fitnessScore;

                if ($fitnessScore > $bestFitnessScore) {
                    $bestFitnessScore = $fitnessScore;
                    $bestSolution = $individual;
                }
            }

            $newPopulation = [];
            for ($i = 0; $i < $this->populationSize; $i++) {
                $parent1 = $this->selection($population, $fitnessScores);
                $parent2 = $this->selection($population, $fitnessScores);
                $child = $this->crossover($parent1, $parent2);
                $this->mutate($child);
                $newPopulation[] = $child;
            }
            $population = $newPopulation;
        }

        Log::warning("Max generations reached. Mengembalikan solusi terbaik yang ditemukan.", ['fitness_score' => $bestFitnessScore]);

        $finalClashInfo = $this->getClashInfo($bestSolution);
        return $this->formatSolution($bestSolution, $finalClashInfo, $bestFitnessScore);
    }

    private function prepareInitialData(array $filters): void
    {
        $this->schedules = $this->scheduleRepository->getEmptyAssistantSchedules($filters);

        foreach ($this->schedules as $schedule) {
            $availableAssistants = $this->assistantRepository->getAssistantAvailableSchedules($schedule->id);
            $this->candidatePools[$schedule->id] = collect($availableAssistants)->pluck('nim')->toArray();
        }

        $preferences = Preference::all()->groupBy('nim');
        foreach ($preferences as $nim => $prefs) {
            $this->assistantPreferences[$nim] = $prefs->pluck('kode_praktikum')->toArray();
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

    private function calculateFitness(array $individual): int
    {
        $clashCount = 0;
        $preferenceMatches = 0;
        $assistantTimeSlots = [];
        $assistantClassCounts = []; // Untuk menghitung beban kerja setiap asisten

        foreach ($individual as $scheduleId => $assistantNims) {
            $schedule = $this->schedules->find($scheduleId);
            $timeSlotIdentifier = $schedule->day->value . '_' . $schedule->start_time . '_' . $schedule->end_time;
            $kodePraktikum = optional($schedule->practicum)->kode_praktikum;

            foreach ($assistantNims as $nim) {
                // 1. Hitung Bentrok
                if (isset($assistantTimeSlots[$nim][$timeSlotIdentifier])) {
                    $clashCount++;
                } else {
                    $assistantTimeSlots[$nim][$timeSlotIdentifier] = $scheduleId;
                }

                // 2. Hitung Preferensi
                if ($kodePraktikum && isset($this->assistantPreferences[$nim]) && in_array($kodePraktikum, $this->assistantPreferences[$nim])) {
                    $preferenceMatches++;
                }

                // 3. Catat jumlah kelas untuk setiap asisten
                if (!isset($assistantClassCounts[$nim])) {
                    $assistantClassCounts[$nim] = 0;
                }
                $assistantClassCounts[$nim]++;
            }
        }

        // 4. Hitung Penalti Distribusi (Standar Deviasi)
        $distributionPenalty = 0;
        if (!empty($assistantClassCounts)) {
            $classCounts = array_values($assistantClassCounts);
            $count = count($classCounts);
            $mean = array_sum($classCounts) / $count;
            $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $classCounts)) / $count;
            $distributionPenalty = sqrt($variance);
        }

        // Hitung total skor penalti terlebih dahulu
        $totalPenaltyScore = ($clashCount * self::CLASH_PENALTY)
            - ($preferenceMatches * self::PREFERENCE_BONUS)
            + ($distributionPenalty * self::DISTRIBUTION_PENALTY);

        // Pastikan skor penalti tidak negatif
        $nonNegativePenalty = max(0, $totalPenaltyScore);

        // Terapkan rumus maksimasi
        return 1 / (1 + $nonNegativePenalty);
    }

    private function selection(array $population, array $fitnessScores): array
    {
        $bestIndividual = null;
        $bestFitness = -1;
        for ($i = 0; $i < $this->tournamentSize; $i++) {
            $randomIndex = array_rand($population);
            // Bandingkan skor fitness, bukan 'count'
            if ($fitnessScores[$randomIndex] > $bestFitness) {
                $bestFitness = $fitnessScores[$randomIndex];
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

    private function formatSolution(array $individual, array $clashInfo, float $fitnessScore): array
    {
        $formatted = [
            'fitness_score' => $fitnessScore,
            'clashes' => $clashInfo['count'],
            'clashing_schedule_ids' => $clashInfo['clashing_ids'],
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

    private function getClashInfo(array $individual): array
    {
        $clashingScheduleIds = [];
        $assistantTimeSlots = [];

        foreach ($individual as $scheduleId => $assistantNims) {
            $schedule = $this->schedules->find($scheduleId);
            $timeSlotIdentifier = $schedule->day->value . '_' . $schedule->start_time . '_' . $schedule->end_time;

            foreach ($assistantNims as $nim) {
                if (isset($assistantTimeSlots[$nim][$timeSlotIdentifier])) {
                    $clashingScheduleIds[] = $scheduleId;
                    $clashingScheduleIds[] = $assistantTimeSlots[$nim][$timeSlotIdentifier];
                } else {
                    $assistantTimeSlots[$nim][$timeSlotIdentifier] = $scheduleId;
                }
            }
        }

        return [
            'count' => count(array_unique($clashingScheduleIds)), // Jumlah jadwal yang bentrok
            'clashing_ids' => array_unique($clashingScheduleIds)
        ];
    }
}
