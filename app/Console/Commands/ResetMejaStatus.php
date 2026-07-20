<?php

namespace App\Console\Commands;

use App\Models\Meja;
use Illuminate\Console\Command;

class ResetMejaStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meja:reset-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset semua status meja menjadi Tersedia setiap pergantian hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Meja::query()->update(['status_meja' => 'Tersedia']);
        $this->info('Semua status meja berhasil direset menjadi Tersedia.');
    }
}