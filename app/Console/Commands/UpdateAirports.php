<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;
use bjoernffm\ourAirportsDownloader\Downloader;
use App\Airport;
use App\AirportFrequency;
use App\Runway;

class UpdateAirports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:airports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Downloading latest airport information ...');
        $downloader = new Downloader();
        $result = $downloader->run();
        $this->line('Download complete');

        $this->info('Inserting airport information');
        $bar = $this->output->createProgressBar(count($result['airports']));
        foreach($result['airports'] as $record) {
            try {
                $airport = new Airport($record);
                $airport->save();
            } catch(\Exception $e) {
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info('Inserting airport frequencies');
        $bar = $this->output->createProgressBar(count($result['airport-frequencies']));
        foreach($result['airport-frequencies'] as $record) {
            try {
                $airportFrequency = new AirportFrequency($record);
                $airportFrequency->save();
            } catch(\Exception $e) {
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info('Inserting runways');
        $bar = $this->output->createProgressBar(count($result['runways']));
        foreach($result['runways'] as $record) {
            try {
                $runway = new Runway($record);
                $runway->save();
            } catch(\Exception $e) {
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
