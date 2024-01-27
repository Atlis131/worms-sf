<?php

namespace App\Datatables;

use Doctrine\ORM\EntityManagerInterface;

class Datatable
{
    protected ?int                   $firstRecord;
    protected ?string                $search      = null;
    protected ?int                   $recordsCount;
    protected ?array                 $orderColumn = null;
    protected ?array                 $filters     = [];
    protected EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        $this->em = $em;
    }

    protected function processRequest($request): void
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

        if ($request->get('weaponType') != '') {
            $this->filters['weaponType'] = $request->get('weaponType');
        }

        if ($request->get('tool') != '') {
            $this->filters['tool'] = $request->get('tool');
        }

        if ($request->get('openMap') != '') {
            $this->filters['openMap'] = $request->get('openMap');
        }
    }
}
