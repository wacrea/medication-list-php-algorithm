<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MedicationList {

	public function get_frenchmedications()
	{
		
				$time = microtime(); 
				$time = explode(" ", $time); 
				$time = $time[1] + $time[0]; 
				$start = $time;
	
				// FR
				
				$medications = array();
				
				for($lettre='A'; $lettre!='AA';$lettre++)
				{
				
					$ch = curl_init('http://www.eurekasante.fr/medicaments/alphabetique/recherche/liste-medicament-'.$lettre.'.html');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
					curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
					$content = utf8_decode(curl_exec($ch));
					curl_close($ch);
					
					$regexp = "<li>(.*)<\/li>";
					if(preg_match_all("/$regexp/siU", $content, $matches)) {
					
						$count = count($matches[0]);
						$count = $count-4;
											
						$countForeach = 0;
						foreach($matches[0] as $li)
						{
							if($countForeach < 2)
							{
								$countForeach++;
								continue;
							}
						
							if(!strpos($li, 'suppr')) {
							
								if(preg_match_all("/<A.*?href\s*=\s*[\"'](.*?)['\"].*?>(.*?)<\/A>/siU", $li, $href))
								{
									$href = 'http://www.eurekasante.fr'.$href[1][0];									
								}
								
								$li = preg_replace("/<.*?>/", "", $li); /* Delete link and li */
								
								// Add to the ARRAY
								$medications[] = array('name_medication' => utf8_encode(trim($li)),
														'link_medication' => $href);
								
								$countForeach++;
								
								if($count == $countForeach){ break; }
							}
							else{
								$count--;
								continue;
							}
						}
					}
				}
				
				// Add to the database
				
				//echo '<pre>';
				//var_dump($medications);
				//echo '</pre>';
				
				foreach($medications AS $medication)
				{
					// Save where you want $medication //
				}
				
				echo '<hr/>';
				$time = microtime(); 
				$time = explode(" ", $time); 
				$time = $time[1] + $time[0]; 
				$finish = $time; 
				$totaltime = ($finish - $start); 
				printf ("This page took %f seconds to load.", $totaltime); 
				
	}
}