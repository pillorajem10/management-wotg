<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\User;

class DGroupLeadersExport implements FromCollection, WithHeadings, WithColumnWidths
{
    protected $users;

    public function __construct()
    {
        $this->users = User::all();
    }

    public function collection()
    {
        $menLeaders = $this->users->where('user_already_a_dgroup_leader', 1)->where('user_gender', 'male');
        $womenLeaders = $this->users->where('user_already_a_dgroup_leader', 1)->where('user_gender', 'female');
        $volunteers = $this->users->where('user_ministry', '!=', 'None')->whereNotNull('user_ministry');
        
        $data = [];

        // Men Group Section
        $data[] = ['DGroup Leaders - Men'];
        $data[] = ['Name', 'Members', 'Day & Time'];
        foreach ($menLeaders as $user) {
            $memberCount = $this->users->where('user_dgroup_leader', $user->id)->count();
            $memberCount = $memberCount > 0 ? $memberCount : '0';

            $data[] = [
                $this->formatName($user->user_fname . ' ' . $user->user_lname), // Auto-format name
                $memberCount,
                $user->user_meeting_day . ' ' . $user->user_meeting_time
            ];
        }
        $data[] = [''];
        $data[] = ['Total Members', $menLeaders->sum(fn($user) => $this->users->where('user_dgroup_leader', $user->id)->count())];
        $data[] = ['Total Leaders', $menLeaders->count()];

        // Add 3 blank rows after the total
        $data[] = [''];
        $data[] = [''];
        $data[] = [''];

        // Women Group Section
        $data[] = ['DGroup Leaders - Women'];
        $data[] = ['Name', 'Members', 'Day & Time'];
        foreach ($womenLeaders as $user) {
            $memberCount = $this->users->where('user_dgroup_leader', $user->id)->count();
            $memberCount = $memberCount > 0 ? $memberCount : '0';

            $data[] = [
                $this->formatName($user->user_fname . ' ' . $user->user_lname), // Auto-format name
                $memberCount,
                $user->user_meeting_day . ' ' . $user->user_meeting_time
            ];
        }
        $data[] = [''];
        $data[] = ['Total Members', $womenLeaders->sum(fn($user) => $this->users->where('user_dgroup_leader', $user->id)->count())];
        $data[] = ['Total Leaders', $womenLeaders->count()];

        // Add 2 blank rows after the totals
        $data[] = [''];
        $data[] = [''];

        // Grand Totals
        $grandTotalMembers = $menLeaders->sum(fn($user) => $this->users->where('user_dgroup_leader', $user->id)->count()) 
            + $womenLeaders->sum(fn($user) => $this->users->where('user_dgroup_leader', $user->id)->count());
        $grandTotalLeaders = $menLeaders->count() + $womenLeaders->count();

        $data[] = ['Grand Total Leaders', $grandTotalLeaders];
        $data[] = ['Grand Total Members', $grandTotalMembers];

        // Add 3 blank rows after the totals
        $data[] = [''];
        $data[] = [''];
        $data[] = [''];

        // Volunteers Section
        $data[] = ['Volunteers'];
        $data[] = ['Name', 'Ministry'];
        foreach ($volunteers as $volunteer) {
            $data[] = [
                $this->formatName($volunteer->user_fname . ' ' . $volunteer->user_lname), // Auto-format name
                $volunteer->user_ministry
            ];
        }
        $data[] = [''];
        $data[] = ['Total Volunteers', $volunteers->count()];

        // Add 3 blank rows after the Volunteers' section
        $data[] = [''];
        $data[] = [''];
        $data[] = [''];

        return collect($data);
    }

    /**
     * Helper function to format names into title case.
     *
     * @param string $name
     * @return string
     */
    private function formatName(string $name): string
    {
        return mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        $maxColumns = 3;
        $widths = [];
        for ($i = 0; $i < $maxColumns; $i++) {
            $widths[chr(65 + $i)] = 30;
        }

        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                if ($cell && $cell->getValue() === 'DGroup Leaders - Men') {
                    $cellCoordinate = $cell->getCoordinate();
                    $sheet->getStyle($cellCoordinate)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FF0000'],
                        ],
                    ]);
                }
            }
        }

        return $sheet;
    }
}
