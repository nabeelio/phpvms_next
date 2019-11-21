<?php

namespace Modules\Installer\Services\Importer\Importers;

use App\Models\Flight;
use Modules\Installer\Services\Importer\BaseImporter;

class FlightImporter extends BaseImporter
{
    public function run()
    {
        $this->comment('--- FLIGHT SCHEDULE IMPORT ---');

        $count = 0;
        foreach ($this->db->readRows('schedules') as $row) {
            $airline_id = $this->idMapper->getMapping('airlines', $row->code);

            $flight_num = trim($row->flightnum);

            $attrs = [
                'dpt_airport_id' => $row->depicao,
                'arr_airport_id' => $row->arricao,
                'route'          => $row->route ?: '',
                'distance'       => round($row->distance ?: 0, 2),
                'level'          => $row->flightlevel ?: 0,
                'dpt_time'       => $row->deptime ?: '',
                'arr_time'       => $row->arrtime ?: '',
                'flight_time'    => $this->convertDuration($row->flighttime) ?: '',
                'notes'          => $row->notes ?: '',
                'active'         => $row->enabled ?: true,
            ];

            try {
                $w = ['airline_id' => $airline_id, 'flight_number' => $flight_num];
                $flight = Flight::updateOrCreate($w, $attrs);
            } catch (\Exception $e) {
                //$this->error($e);
            }

            $this->idMapper->addMapping('flights', $row->id, $flight->id);

            // TODO: deserialize route_details into ACARS table

            if ($flight->wasRecentlyCreated) {
                $count++;
            }
        }

        $this->info('Imported '.$count.' flights');
    }
}
