<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;
use App\Services\UpDateUserPaper;


class UpDateUserPaperController extends Controller
{
    public function updateUserPaper()
    {
       
        $paper = Paper::orderBy('id', 'desc')->get();
        $ServiceUserPaper = new UpDateUserPaper();
        
        foreach ($paper as $paper) {
            echo $paper->paper_name . "\n";
            $PaperAuthors = $ServiceUserPaper->getAuthor($paper->paper_name);
            echo implode(", ", $PaperAuthors) . "\n";
            $PaperAuthorFName = $ServiceUserPaper->getFirstName($PaperAuthors);
            echo implode(", ",$PaperAuthorFName) . "\n";
            echo "**************\n";    
            $updateUserPaper = $ServiceUserPaper->assignAuthorsToPaper($paper->id,$PaperAuthorFName);
            echo "**************\n";    
        }
    }
}
