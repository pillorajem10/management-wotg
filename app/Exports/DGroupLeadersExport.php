<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
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
        
        // Men Group Section
        $data[] = ['DGroup Leaders - Men'];
        $data[] = ['Name', 'Members', 'Day & Time'];
        foreach ($menLeaders as $user) {
            $memberCount = $this->users->where('user_dgroup_leader', $user->id)->count();
            
            // Ensure it returns 0 explicitly if no members are found
            $memberCount = $memberCount > 0 ? $memberCount : '0';
        
            $data[] = [
                $user->user_fname . ' ' . $user->user_lname, 
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
            // Count the number of members
            $memberCount = $this->users->where('user_dgroup_leader', $user->id)->count();
            
            // Ensure it explicitly shows '0' as a string if there are no members
            $memberCount = $memberCount > 0 ? $memberCount : '0';
        
            $data[] = [
                $user->user_fname . ' ' . $user->user_lname, 
                $memberCount, 
                $user->user_meeting_day . ' ' . $user->user_meeting_time
            ];
        }
        
        $data[] = [''];
        $data[] = ['Total Members', $womenLeaders->sum(fn($user) => $this->users->where('user_dgroup_leader', $user->id)->count())];
        $data[] = ['Total Leaders', $womenLeaders->count()];

        $data[] = [''];
        $data[] = [''];

        $grandTotalMembers = $menLeaders->sum(fn($user) => $this->users->where('user_dgroup_leader', $user->id)->count()) 
            + $womenLeaders->sum(fn($user) => $this->users->where('user_dgroup_leader', $user->id)->count());

        $grandTotalLeaders = $menLeaders->count() + $womenLeaders->count();

        $data[] = ['Grand Total Leaders', $grandTotalLeaders];
        $data[] = ['Grand Total Members', $grandTotalMembers];

        
        // Add 3 blank rows after the total
        $data[] = [''];
        $data[] = [''];
        $data[] = [''];
        
        // Volunteers Section
        $data[] = ['Volunteers'];
        $data[] = ['Name', 'Ministry'];
        foreach ($volunteers as $volunteer) {
            $data[] = [$volunteer->user_fname . ' ' . $volunteer->user_lname, $volunteer->user_ministry];
        }

        $data[] = [''];
        $data[] = ['Total Volunteers', $volunteers->count()];
    
        // Add 3 blank rows after the Volunteers' section
        $data[] = [''];
        $data[] = [''];
        $data[] = [''];
        
        return collect($data);
        $data[] = [''];
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        // Dynamically determine column widths
        $maxColumns = 3; // Change dynamically based on data
        $widths = [];
        for ($i = 0; $i < $maxColumns; $i++) {
            $widths[chr(65 + $i)] = 30;
        }

        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        // Loop through rows to locate the string "DGroup Leaders - Men"
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





