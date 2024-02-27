<?php

namespace App\Datatables;

use App\Entity\Weapon\WeaponLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class WeaponLogDatatable extends Datatable
{
    private Container $container;

    public function __construct(
        EntityManagerInterface $em,
        Container              $container,
    )
    {
        parent::__construct($em);
        $this->container = $container;
    }

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

            $logData['weapon'] = [
                'name' => $log['weaponName'],
                'image' => $this->container->getParameter('base_url') . '/assets/img/weapons/' . $log['weaponImage'] . '.webp'
            ];

            $logData['createdAt'] = $formattedData;
            $logData['type'] = $log['type'];

            $logData['newValue'] = [
                'value' => $log['newValue'],
                'type' => $log['type']
            ];

            $logData['oldValue'] = [
                'value' => $log['oldValue'],
                'type' => $log['type']
            ];

            $logsArray[] = $logData;
        }

        $records = [
            'total' => $logsCount,
            'filtered' => $filteredLogsCount,
            'data' => $logsArray
        ];

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $records['total'],
            'recordsFiltered' => $records['filtered'],
            'data' => $records['data'],
        ];
    }
}
