<meta charset="utf-8">
<?php

class Viewer{

	private $AalignedSentences; // array( [sentence1] => array(), [sentence2] => array() , [relation] => array() )
			
	public function Alignment($alignedSen=""){
	    $this->AalignedSentences=$alignedSen;
	}
	
    public function setAlignment($alignedSen){
	    $this->AalignedSentences=$alignedSen;
	}

// visualise the alignment of 3 sentences
	function print_multiple_alignment(){
	  $temp1=$temp2=$temp3="";
	  for($i=0;$i<count($this->AalignedSentences['sentence1']); $i++){
	  	switch($this->AalignedSentences['relation'][$i]){
	  		case "Aligned": { 
                $class="success"; 
                break;
            }
	  		case "Not Aligned": { 
                $class="danger"; 
                break;
            }
	  		default: {
                $class=""; 
                break;
            }
	  	}
	  	$temp1.="<td class='".$this->AalignedSentences['relation'][$i][0]."'>".$this->AalignedSentences['sentence1'][$i]."</td>";
	  	$temp2.="<td class='".$this->AalignedSentences['relation'][$i][1]."'>".$this->AalignedSentences['sentence2'][$i]."</td>";
	  	$temp3.="<td class='".$this->AalignedSentences['relation'][$i][2]."'>".$this->AalignedSentences['sentence3'][$i]."</td>";
	  }	 
	  // generate the html output
	  $html="<table  class='table'>";
	  $html.="<tr>".$temp1."</tr>";
	  $html.="<tr>".$temp2."</tr>";
	  $html.="<tr>".$temp3."</tr>";
	  $html.="</table>";
	  return $html;	
	
	}
    
// visualise pairwise alignment as html table   
	public function pairwiseAlignment_to_htmltable()
	{	  
	  $temp1=$temp2="";
	  for($i=0;$i < sizeof($this->AalignedSentences['sentence1']); $i++){
	  	$temp1.="<td class='".$this->AalignedSentences['relation'][$i]."'>".$this->AalignedSentences['sentence1'][$i]."</td>";
	  	$temp2.="<td class='".$this->AalignedSentences['relation'][$i]."'>".$this->AalignedSentences['sentence2'][$i]."</td>";
	  }
     // generate the html output
	  $html="<table  class='table table-sm'>";
	  $html.="<tr>".$temp1."</tr>";
	  $html.="<tr>".$temp2."</tr>";
	  $html.="</table>";
	  return $html;	
	}

    public function pairwiseAlignment_longestcommonsubstring(){
        $alignment=$this->AalignedSentences;
        $longest_common_substring=array();
        $current_common_substring=array();
        for($i=0;$i < sizeof($alignment['sentence1']);$i++) {
            if ($alignment['relation'][$i] != "Gap" && $alignment['relation'][$i]!="notAligned") {
                $current_common_substring[] = array($alignment['sentence1'][$i], $alignment['sentence2'][$i], $alignment['relation'][$i]);
            } else {
                if (sizeof($current_common_substring) > sizeof($longest_common_substring))
                    $longest_common_substring = $current_common_substring;
                $current_common_substring = array();
            }
        }
        $td1=$td2="";

        foreach ($longest_common_substring as $k=>$v){
            $td1.="<td class='".$v[2]."'>".$v[0]."</td>";
            $td2.="<td class='".$v[2]."'>".$v[1]."</td>";
        }
        $table="<table class='table table-sm' >
                    <tr>".$td1."</tr>
                    <tr>".$td2."</tr>
                </table>";
        return $table;
    }

    /**
     * @return string
     */
    public function pairwiseAlignment_statisitcs(){
        $alignment=$this->AalignedSentences;
        $relations=array();
        $html=array();

        for($i=0;$i < sizeof($alignment['sentence1']);$i++)
            $relations[$alignment['relation'][$i]]+=1;

        $html[]="<span class='label label-default'>Length: <span class='badge'>".sizeof($alignment['sentence1'])."</span> </span>";

        foreach ($relations as $k=>$rel)
            $html[]="<span class='label ".$k."'> ".$k." <span class='badge'>".$rel."</span> </span>";

        $div="<div class='row'>
                <div class='col-md-12'>".implode(" &nbsp; ",$html)."</div>
           </div>";
        return $div;
    }

    public function pairwiseAlignment_to_coloredText(){
        $alignment=$this->AalignedSentences;
        $relations=array();
        $text1=array();
        $text2=array();
        for($i=0;$i < sizeof($alignment['sentence1']);$i++){
            $relations[$alignment['relation'][$i]]+=1;
            $text1[]="<span class='".$alignment['relation'][$i]."'>&nbsp; ".$alignment['sentence1'][$i]."&nbsp; </span>";
            $text2[]="<span class='".$alignment['relation'][$i]."'>&nbsp; ".$alignment['sentence2'][$i]."&nbsp; </span>";
        }
        $div="<div class='row'>
                    <div class='col-md-6' style='font-size: 14pt'>".implode(" ",$text1)."</div>
                    <div class='col-md-6' style='font-size: 14pt'>".implode(" ",$text2)."</div>
              </div><br><br>";
        return $div;
    }

	function OCRVisualisation()
	{
	  $html="";
	  for($i=0;$i<count($this->AalignedSentences['sentence1']); $i++){
		  if($this->AalignedSentences['relation'][$i]=="Aligned-complete")
				$html.="<span class='Aligned-complete'>".$this->AalignedSentences['sentence1'][$i]."</span>";
		  else
				$html.=" ".$this->createList(array($this->AalignedSentences['sentence1'][$i],$this->AalignedSentences['sentence2'][$i]));			
	  }	 
	  
	  return $html;
	}
	
// this function is used by OCRVisualisation
// to generate html dropdown menu
    function createList($options)
	{
	 $ret='<span class="form-group">      
	 		<select class="form-control inline" id="w1">';
	 foreach($options as $k=>$option)
	 	$ret.='<option>'.$option.'</option>';
	 $ret.='     </select>';
	 $ret.='</span>';
	 return $ret;
	}

}

?>