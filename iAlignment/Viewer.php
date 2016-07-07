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
	public function paitwiseAlignment_to_htmltable()
	{	  
	  $temp1=$temp2="";
	  for($i=0;$i < sizeof($this->AalignedSentences['sentence1']); $i++){
	  	$temp1.="<td class='".$this->AalignedSentences['relation'][$i]."'>".$this->AalignedSentences['sentence1'][$i]."</td>";
	  	$temp2.="<td class='".$this->AalignedSentences['relation'][$i]."'>".$this->AalignedSentences['sentence2'][$i]."</td>";
	  }
     // generate the html output
	  $html="<table  class='table'>";
	  $html.="<tr>".$temp1."</tr>";
	  $html.="<tr>".$temp2."</tr>";
	  $html.="</table>";
	  return $html;	
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