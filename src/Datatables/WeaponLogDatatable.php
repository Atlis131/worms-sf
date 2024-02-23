<?php

namespace App\Datatables;

use App\Entity\WeaponLog;

class WeaponLogDatatable extends Datatable
{

    public function getDatatableData($request): array
    {
        $this->processRequest($request);

        $weaponLogs = $this->em->getRepository(WeaponLog::class);

        $logsCount = $weaponLogs->getLogsCount($this->search);
        $filteredLogsCount = $weaponLogs->getLogsCount($this->search);
        $logs = $weaponLogs->getLogsList($this->firstRecord, $this->recordsCount, $this->orderColumn, $this->search);

        $logsArray = [];

        foreach ($logs as $log) {
            $logData = [];

            $formattedData = $log['createdAt']->format('Y-m-d H:i:s');

            $logData['id'] = $log['id'];
            $logData['username'] = $log['username'];
            $logData['createdAt'] = $formattedData;
            $logData['type'] = $log['type'];
            $logData['newValue'] = $log['newValue'];
            $logData['oldValue'] = $log['oldValue'];

            $logsArray[] = $logData;
        }

        $records = [
            'total'    => $logsCount,
            'filtered' => $filteredLogsCount,
            'data'     => $logsArray
        ];

        return [
            'draw'            => $request->get('draw'),
            'recordsTotal'    => $records['total'],
            'recordsFiltered' => $records['filtered'],
            'data'            => $records['data'],
        ];
    }
}
