<?php

namespace App\Datatables;

use App\Entity\UserLog;
use Doctrine\ORM\EntityManagerInterface;

class UserLogDatatable
{
    private ?int $firstRecord;
    private ?string $search = null;
    private ?int $recordsCount;
    private ?array $orderColumn = null;
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        $this->em = $em;
    }

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

    private function processRequest($request): void
    {
        $this->firstRecord = $request->get('start');
        $this->recordsCount = $request->get('length');

        $columns = $request->get('columns');

        if (!is_null($request->get('order')) && isset($request->get('order')[0])) {
            $this->orderColumn = [
                'column' => $columns[$request->get('order')[0]['column']]['data'],
                'dir'    => $request->get('order')[0]['dir']
            ];
        }

        if (!is_null($request->get('search')) && $request->get('search')['value'] !== '') {
            $this->search = $request->get('search')['value'];
        }
    }
}
