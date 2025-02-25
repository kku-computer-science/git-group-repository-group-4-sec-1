<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\PublicationRetrieval;

class UpDateUserPaper
{
    public function getAuthor($paperName){
        $paperOpenAlxe = new PublicationRetrieval();
        $author = $paperOpenAlxe->getPaperOpenAlxe($paperName);
        if (isset($author["authorships"]) && !empty($author["authorships"])) {
            $authorNames = array_map(fn($item) => $item[0], $author["authorships"]);
        }else {
            $authorNames = [];
        }
        return $authorNames;
    }


    public function assignAuthorsToPaper($paperId, $authorNames)
    {
        foreach ($authorNames as $index => $authorName) {
            $user = User::where('fname_en', 'like', $authorName . '%')->first(); 

            if ($user) {
                $paper = Paper::find($paperId);
                $paper->teacher()->attach($user->id, [
                    'author_type' => $index + 1 
                ]);
                echo "add {$user->id}, {$paperId} Success\n";
                Log::info("Assigned author {$authorName} to paper {$paperId} with author_type " . ($index + 1));
            } else {
                Log::warning("Author {$authorName} not found, skipping.");
            }
        }
    }

    //ฟังก์ชั่นคัดชื่อหน้า
    public function getFirstName($fullNames)
    {
        
        if (is_array($fullNames)) {
            return array_map(fn($fullName) => $this->getFirstNameFromFullName($fullName), $fullNames);
        }

       
        return [$this->getFirstNameFromFullName($fullNames)];
    }
    
    //ฟังก์ชั่นย่อยคัดชื่อหน้า
    private function getFirstNameFromFullName($fullName)
    {
        $fullNameCut = str_replace('‐', ' ', $fullName);
        $fullNameWithoutHyphen = str_replace('-', ' ', $fullNameCut);
        $nameParts = explode(' ', $fullNameWithoutHyphen); 
        return $nameParts[0]; 
    }
}