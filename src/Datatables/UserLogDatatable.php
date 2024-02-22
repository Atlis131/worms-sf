<?php

namespace App\Datatables;

use App\Entity\UserLog;

class UserLogDatatable extends Datatable
{

    public function getDatatableData($request): array
    {
        $this->processRequest($request);

        $weaponsRepository = $this->em->getRepository(UserLog::class);

        $logsCount = $weaponsRepository->getLogsCount($this->search);
        $filteredLogsCount = $weaponsRepository->getLogsCount($this->search);
        $logs = $weaponsRepository->getLogsList($this->firstRecord, $this->recordsCount, $this->orderColumn, $this->search);

        $logsArray = [];

        foreach ($logs as $log) {
            $logData = [];

            $formattedData = $log['createdAt']->format('Y-m-d H:i:s');

            $logData['id'] = $log['id'];
            $logData['username'] = $log['username'];
            $logData['createdAt'] = $formattedData;
            $logData['type'] = $log['type'];
            $logData['message'] = $log['message'];

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
