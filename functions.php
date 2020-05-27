<?php
if(isset($_POST['seed'])){$GLOBALS['seed'] = $_POST['seed'];}else{$GLOBALS['seed'] = time();}

//$GLOBALS['seed'] = 1521733664;

function SeedShuffle($seed,$array){
	$tmp = array();
    for ($rest = $count = count($array);$count>0;$count--) {
        $seed %= $count;
        $t = array_splice($array,$seed,1);
        $tmp[] = $t[0];
        $seed = $seed*$seed + $rest;
    }
    return $tmp;
}

//Name generator
function generate_first_name($gender){
	$names = [];
	if($gender == 'male'){
		$names = array_map('str_getcsv', file('names/Human_Names_-_Western_Male.csv'));
	}else{
		$names = array_map('str_getcsv', file('names/Human_Names_-_Western_Female.csv'));
	}
	//Generate a random forename
	$first_name = $names[mt_rand(0, sizeof($names) - 1)];
	//Combine them together and print out the result.
	return $first_name;
}
function generate_last_name(){
	$surnames = array_map('str_getcsv', file('names/Human_Names_-_Western_Surname.csv'));
	//Generate a random surname
	$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];
	//Combine them together and print out the result
	$GLOBALS['lname'] = $random_surname[1];
}

// Generate the session
function generate($json_name){
	$seed = $GLOBALS['seed'];
	mt_srand($seed);

	$path = "json/".$json_name.".json";
	$str = file_get_contents($path);
	$result_array = [];
	$json = json_decode($str, true); // decode the JSON into an associative array
		if ($json === null
		&& json_last_error() !== JSON_ERROR_NONE) {
			error_modal("ERROR: Invalid {$json_name} json","Your {$json_name} json file is invalid, please correct and try again.<br>[See <a href='http://jsonlint.com/' target='_blank'>http://jsonlint.com/</a>]");
		}else{
			if (isset($json[$json_name])){
			//change this so json weight value factors into random selection
			//IS WEIGHTED?
			if(isset($json[$json_name][0]['weight'])){
			//weighting results
			 $weight_sum = 0;
			  foreach($json[$json_name] as $val)
			  {
				$weight_sum += $val['weight'];
			  }

			 $rand_key = mt_rand(1, $weight_sum);

			  for($i = 0; $i < count($json[$json_name],0); $i++){
				if($rand_key < $json[$json_name][$i]['weight'])
				{
				  break;
				}else{
				  $rand_key -= $json[$json_name][$i]['weight'];
				}}

			}else{
				$rand_key = array_rand($json[$json_name]);
			}

			$rand_tmp = SeedShuffle($seed,$json[$json_name]);
				foreach ($rand_tmp[0] as $key => $val) {
					if($key == 'Misfortune'){
						echo " ";
						generate('misfortune');
					}
					if($key == 'Death'){
						echo ", ";
						generate('death');
					}

					if($key != 'weight'){
					$result_array[0] = $key;
					$result_array[1] = $val;
						if($json_name == 'birth'){
							$birth_tmp = SeedShuffle($seed,$json[$json_name]);
							$birth_tmp  = array_column($birth_tmp[0], 0);
							echo $birth_tmp[0];
						}elseif($json_name == 'caretakers_status'){
							$father_name = generate_first_name('male',$seed);
							$mother_name = generate_first_name('female',$seed);
							echo "Your father <strong>{$father_name[1]}</strong> and mother <strong>{$mother_name[1]}</strong> ";
							echo $result_array[1];
						}else{
							$tmp = SeedShuffle($GLOBALS['seed'],$result_array);
							echo $val;
						}
					}
				}
				if($json_name == 'races'){
					generate_race_list();
				}
			}else{
				error_modal('ERROR: Invalid {$json_name} type selected','Your {$json_name} type selection is invalid, please correct and try again.');
			}
		}
}
//Races Generator
function generate_race_list(){
	$path = "json/races.json";
	$str = file_get_contents($path);
	$json = json_decode($str, true); // decode the JSON into an associative array
	$max = count($json['races']);

	$seed = $GLOBALS['seed'];
	mt_srand($seed);
	$raceCount = mt_rand(0,$max);
	$GLOBALS['race'] = $raceCount;
		if ($json === null
		&& json_last_error() !== JSON_ERROR_NONE) {
			error_modal("ERROR: Invalid {$json_name} json","Your {$json_name} json file is invalid, please correct and try again.<br>[See <a href='http://jsonlint.com/' target='_blank'>http://jsonlint.com/</a>]");
		}else{
			foreach($json['races'] as $race)
			{
				echo "<option value='" .$race['name'];
				echo "' ";
				if($_POST['race_select'] and $_POST['race_select']  != ""){
					//If user selected a specific race
					if($race['name'] == $_POST['race_select']){
						echo " selected ";
					}
				}elseif($race['id'] == $raceCount){
					//Select current seeded race
					echo " selected ";
				}
				
				echo ">";
				echo $race['name'];
				echo "</option>";
			}
		}

}
function get_race_info(){
	$path = "json/races.json";
	$str = file_get_contents($path);
	$json = json_decode($str, true); // decode the JSON into an associative array
	$races = [];
	if ($json === null
	&& json_last_error() !== JSON_ERROR_NONE) {
		error_modal("ERROR: Invalid {$json_name} json","Your {$json_name} json file is invalid, please correct and try again.<br>[See <a href='http://jsonlint.com/' target='_blank'>http://jsonlint.com/</a>]");
	}else{
		foreach($json['races'] as $race)
		{
			if($race['id'] == $GLOBALS['race']){
				echo $race['desc'];
				echo "<div class='row p-3 bg-secondary text-white'>";
				echo "<div class='col'>Avg. Height:&nbsp;";
				echo $race['avgheightmin'];
				echo "&nbsp;-&nbsp;";
				echo $race['avgheightmax'];
				echo "&nbsp;ft.</div>";
				echo "<div class='col'>Avg. Weight:&nbsp;";
				echo $race['avgheightmax'];
				echo "&nbsp;-&nbsp;";
				echo $race['avgheightmax'];
				echo "&nbsp;lbs.</div>";
				echo "<div class='col'>Maturity:&nbsp;";
				echo $race['maturationyear'];
				echo "&nbsp;yrs.</div>";
				echo "<div class='col'>Maximum Age:&nbsp;";
				echo $race['maxage'];
				echo "</div>";
				echo "</div>";
				echo "<span class='float-right'><a href='";
				echo $race['link'];
				echo "' target='_blank'>";
				echo $race['link'];
				echo "</a></span>";
			}
		}

		
	}
} 

function siblings(){
	$path = "json/races.json";
	$str = file_get_contents($path);
	$json = json_decode($str, true); // decode the JSON into an associative array
	$races = [];
	if ($json === null
	&& json_last_error() !== JSON_ERROR_NONE) {
		error_modal("ERROR: Invalid {$json_name} json","Your {$json_name} json file is invalid, please correct and try again.<br>[See <a href='http://jsonlint.com/' target='_blank'>http://jsonlint.com/</a>]");
	}else{
		foreach($json['races'] as $race)
		{
			if($race['id'] == $GLOBALS['race']){
				$max = $race['maxsiblings'];
				$siblings = mt_rand(0,$max);
				if($siblings >1 && $siblings <=$max){
				echo "were raised with $siblings siblings.<br/>";
				echo "<ul class='list-group list-group-flush'>";
				for ($s = 1; $s <= $siblings; $s++) {
					mt_srand($s); 
					$gender = mt_rand(1,2);
					$race 	= mt_rand(1,100);
					if($gender == 1){
							$gender_type = "brother";
							$fname = generate_first_name('male');
						}else{
							$gender_type = "sister";
							$fname = generate_first_name('female');
						}
						$fname = $fname[1];
		
						if($race  > 0 AND $race < 80){
							$race_type =  $race_selected;
						}else{
								$other_race 	= mt_rand(1,100);
								if($other_race > 0 AND $other_race < 50){
										$race_type = "Human (Adopted)";
								}
								if($other_race > 50 AND $other_race < 55){
										$race_type = "Shirren (Adopted)";
								}
								if($other_race > 55 AND $other_race < 65){
										$race_type = "Kasatha (Adopted)";
								}
								if($other_race > 65 AND $other_race < 70){
										$race_type = "Vesk (Adopted)";
								}
								if($other_race > 70 AND $other_race < 90){
										$race_type = "Ysoki (Adopted)";
								}
								if($other_race > 90 AND $other_race < 100){
										$race_type = "Other (Adopted)";
								}
						}
					$fate 	= mt_rand(1,12);
					echo "<li class='list-group-item'>";
					if($fate == 1 || $fate == 2){
						echo "{$fname}, {$race_type} {$gender_type} lost touch, it is unknown to you what became of them";
					}
					if($fate == 3 || $fate == 4){
						echo "{$fname}, {$race_type} {$gender_type} lives at home with your parents";
					}
					if($fate == 5 || $fate == 6){
						echo "{$fname}, {$race_type} {$gender_type} has had bad luck in life";
						echo ", ";
						generate('misfortune');
					}
					if($fate == 7 || $fate == 8){
						echo "{$fname}, {$race_type} {$gender_type} keeps in touch, they are enjoying their own life";
					}
					if($fate == 9 || $fate == 10){
						echo "{$fname}, {$race_type} {$gender_type} hates you, for some past transgression";
					}
					if($fate == 11 || $fate == 12){
						echo "{$fname}, {$race_type} {$gender_type} is dead";
						echo ", ";
						generate('death');
					}
					echo "</li>";
				}
				echo "</ul>";
			}
			if($siblings == 0 && $race['name'] != 'Android'){
				echo "are an only child.";
			}
			if($siblings == 0 && $race['name'] == 'Android'){
				echo "were manufactured.";
			}
			}
		}
	}

}

function tragedy(){
	$fate = mt_rand(1,12);
	if($fate == 1 || $fate == 2){
		echo "You lost all your credits/possessions";
	}
	if($fate == 3 || $fate == 4){
		echo "You made yourself indebted to someone or some group";
	}
	if($fate == 5 || $fate == 6){
		echo "You ended up imprisoned for several months";
	}
	if($fate == 7 || $fate == 8){
		echo "You had a serious accident that left you incapacitated for several months";
	}
	if($fate == 9 || $fate == 10){
		echo "You spent several months battling an addiction";
	}
	if($fate == 11 || $fate == 12){
		echo "You lost a pet";
	}
}
function windfall(){
	$fate = mt_rand(1,13);
	if($fate == 1){
		echo "You double your worth (2x starting money)";
	}
	if($fate == 2){
		echo "You managed to be at the right place and at the right time and someone owes you";
	}
	if($fate == 3){
		echo "Through your deeds you manage to make a name for yourself locally (Starting REP+4)";
	}
	if($fate == 4){
		echo "You find a sibling you never knew you had";
	}
	if($fate == 5){
		echo "You find yourself a pet (Any 1/3 CR creature)";
	}
	if($fate == 6){
		echo "The opportunity arises to travel to far distant lands for several months (Knowledge culture +2 ranks)";
	}
	if($fate == 7){
		echo "Gain combat training (+1 to hit with a chosen weapon)";
	}
	if($fate == 8){
		echo "Obtain a goblin junkcycle";
	}
	if($fate == 9){
		echo "Obtain an enercycle";
	}
	if($fate == 10){
		echo "Obtain Second Skin&trade; armor";
	}
	if($fate == 11){
		echo "Obtain Freebooter I armor";
	}
	if($fate == 12){
		echo "Obtain a Wanderer Shuttle";
	}
	if($fate == 13){
		echo "Obtain a Voidrunner Racer";
	}
}

function enemy_heft(){
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "This person has no real pull, only has themselves";
	}
	if($fate == 2){
		echo "They are apart of a clan all willing to lay their lives for them";
	}
	if($fate == 3){
		echo "They are apart of a small gang";
	}
	if($fate == 4){
		echo "They are apart of a large family";
	}
	if($fate == 5){
		echo "They are a local hero that can pull on the resources of a single town";
	}
	if($fate == 6){
		echo "They are a famous hero or major noble that can pull resources over an entire province";
	}
	if($fate == 7){
		echo "They are apart of a mercenary outfit or part of the guard";
	}
	if($fate == 8){
		echo "They have powerful connections with the black market and the criminal world";
	}
	if($fate == 9){
		echo "They know someone who is a power unto himself, like a mage or a powerful priest";
	}
	if($fate == 10){
		echo "They are connected to angelic or extra planar forces";
	}
	if($fate == 11){
		echo "They have connections to dark demonic forces or something of an evil nature";
	}
	if($fate == 12){
		echo "They are a member of the ruling family with pull anywhere within the kingdom, some beyond";
	}

	echo ".  They hate you because ";
	animosity();
}

function friend_heft(){
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "This person has no real pull, only has themselves";
	}
	if($fate == 2){
		echo "They are apart of a clan all willing to lay their lives for them";
	}
	if($fate == 3){
		echo "They are apart of a small gang";
	}
	if($fate == 4){
		echo "They are apart of a large family";
	}
	if($fate == 5){
		echo "They are a local hero that can pull on the resources of a single town";
	}
	if($fate == 6){
		echo "They are a famous hero or major noble that can pull resources over an entire province";
	}
	if($fate == 7){
		echo "They are apart of a mercenary outfit or part of the guard";
	}
	if($fate == 8){
		echo "They have powerful connections with the black market and the criminal world";
	}
	if($fate == 9){
		echo "They know someone who is a power unto himself, like a mage or a powerful priest";
	}
	if($fate == 10){
		echo "They are connected to angelic or extra planar forces";
	}
	if($fate == 11){
		echo "They have connections to dark demonic forces or something of an evil nature";
	}
	if($fate == 12){
		echo "They are a member of the ruling family with pull anywhere within the kingdom, some beyond";
	}

	echo ".  They are your friend because ";
	friendship();
}


function animosity(){
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "you caused the loss of face or status publicly";
	}
	if($fate == 2){
		echo "you caused the loss of a friend or lover";
	}
	if($fate == 3){
		echo "you truly or falsely brought criminal charges against the person";
	}
	if($fate == 4){
		echo "you left the other out to dry or outright backstabbing";
	}
	if($fate == 5){
		echo "you turned down their job or romantic advances";
	}
	if($fate == 6){
		echo "you were commpeting for a job or romance and won";
	}
	if($fate == 7){
		echo "you caused the failure of some plot, quest or undertaking";
	}
	if($fate == 8){
		echo "because you defeated them in combat or game/gamble";
	}
	if($fate == 9){
		echo "you are hated due to race and/or religious beliefs, the hatred stems from stereotype";
	}
	if($fate == 10){
		echo "you murdered a friend/relative/lover";
	}
	if($fate == 11){
		echo "you made them jealous";
	}
	if($fate == 12){
		echo "you took economic advantage by scam, or physical advantage through force";
	}
	fracked();
}
function fracked(){
	$fate = mt_rand(1,3);
	if($fate == 1){
		echo ". ";
	}
	if($fate == 2){
		echo ", and you hate them. ";
	}
	if($fate == 3){
		echo ", and you hate each other for it. ";
	}
	fracked2();
	
}
function fracked2(){
	$fate = mt_rand(1,4);
	if($fate == 1){
		echo "If you see them, you will go into a murderous killing rage and rip their face off! ";
	}
	if($fate == 2){
		echo "If you see them, you will avoid the scum. ";
	}
	if($fate == 3){
		echo "If you find them, you will backstab them indirectly. ";
	}
	if($fate == 4){
		echo "If you see them, you will rip into them verbally. ";
	}	
}


function friendship(){
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "you caused them to gain face or status publicly";
	}
	if($fate == 2){
		echo "you caused them to find a friend or lover";
	}
	if($fate == 3){
		echo "you got criminal charges against the person dropped";
	}
	if($fate == 4){
		echo "you saved them from being left out to dry or an outright backstabbing";
	}
	if($fate == 5){
		echo "you found them their job";
	}
	if($fate == 6){
		echo "you were commpeting for a job or romance and gave in on purpose";
	}
	if($fate == 7){
		echo "you were instramental in some plot, quest or undertaking";
	}
	if($fate == 8){
		echo "because you defeated them in combat or game/gamble";
	}
	if($fate == 9){
		echo "you are liked due to race and/or religious beliefs";
	}
	if($fate == 10){
		echo "you saved a friend/relative/lover";
	}
	if($fate == 11){
		echo "you made them envious";
	}
	if($fate == 12){
		echo "you gave them an economic advantage by scam, or physical advantage through force";
	}
}
function intensity(){
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "Annoyed - It rubs you wrong to be around this person, but you can control it";
	}
	if($fate == 2){
		echo "Bothered - You can't restrain quips and cut downs when you are around this person";
	}
	if($fate == 3){
		echo "Angry	- Proximity to this individual leads to arguments, shouting, yelling";
	}
	if($fate == 4){
		echo " <br>";
	}
	if($fate == 5){
		echo " <br>";
	}
	if($fate == 6){
		echo " <br>";
	}
	if($fate == 7){
		echo " <br>";
	}
	if($fate == 8){
		echo " <br>";
	}
	if($fate == 9){
		echo " <br>";
	}
	if($fate == 10){
		echo " <br>";
	}
	if($fate == 11){
		echo " <br>";
	}
	if($fate == 12){
		echo " <br>";
	}
}
function friend(){
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "Like a Big Brother or Sister to You - Someone that is older and looks after you, fussing over you at times<br>";
	}
	if($fate == 2){
		echo "Like a Kid Brother or Sister to You	Someone you look after as well as tease<br>";
	}
	if($fate == 3){
		echo "Teacher or Mentor	A sage becomes a friend that instructs you in matters<br>";
	}
	if($fate == 4){
		echo "Partner or Co-worker	Someone you work with often becomes a close friend<br>";
	}
	if($fate == 5){
		echo "An Old Lover - You said - I just want to be friends - and meant it<br>";
	}
	if($fate == 6){
		echo "Bygones become bygones and old rivalries become funny stories with an old enemy";
	}
	if($fate == 7){
		echo "Like a Foster Parent to You - This friend regails you with advice as well as cares for you<br>";
	}
	if($fate == 8){
		echo "Old Childhood Friend	- You bump into someone you had not seen in years<br>";
	}
	if($fate == 9){
		echo "Relative	- A relative becomes a friend as well as a relation<br>";
	}
	if($fate == 10){
		echo "Gang or Tribe	- Somehow you earn the friendship of a gang or tribe of people<br>";
	}
	if($fate == 11){
		echo "Creature with Animal Intelligence	- You befriend a badger or horse, or even a random Displacer Beast you can no longer find<br>";
	}
	if($fate == 12){
		echo "Intelligent Creature	- Maybe you ran into a Sphinx or a Wemic and managed to take the proverbial thorn out of the proverbial paw<br>";
	}
	friend_heft();
}
function enemy(){
	echo "<div style='padding-left:20px;'>";
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "A former friend now hates you";
	}
	if($fate == 2){
		echo "A former lover now hates you";
	}
	if($fate == 3){
		echo "A relative now hates you";
	}
	if($fate == 4){
		echo "A childhood enemy, an old face you hoped you would never see again has returned";
	}
	if($fate == 5){
		echo "A person you worked for";
	}
	if($fate == 6){
		echo "A person that worked for you, hates you";
	}
	if($fate == 7){
		echo "A former partner turns to arguments";
	}
	if($fate == 8){
		echo "You managed to step on the really wrong foot and now a gang or tribe hates you";
	}
	if($fate == 9){
		echo "Someone amongst the law reall dislikes you";
	}
	if($fate == 10){
		echo "Somehow you've come to the attention of dark forces, and they know your name";
	}
	if($fate == 11){
		echo "You kicked that animal one time too many, and now it hates you to the bone";
	}
	if($fate == 12){
		echo "You have raised the anger of a dragon";
	}
	echo ".  ";
	enemy_heft();
	echo "</div>";
}

function romance(){
	echo "<div style='padding-left:20px;'>";
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "Casual	The year is marked with relationships. You date several people throughout the year, each for about 1d12 weeks";
	}
	if($fate == 2){
		echo "The year is marked with relationships. You date several people throughout the year, each for about 1d12 weeks";
	}
	if($fate == 3){
		echo "You find a certain someone whom you date for 1d12 months";
	}
	if($fate == 4){
		echo "You find a certain someone whom you date for 1d12 months";
	}
	if($fate == 5){
		echo "A serious relationship has lasted into this year. Roll 1d12 - On a 1-5 it ends, on a 6-11 it continues through the year, on a 12 you marry";
	}
	if($fate == 6){
		echo "A serious relationship has lasted into this year. Roll 1d12 - On a 1-5 it ends, on a 6-11 it continues through the year, on a 12 you marry";
	}
	if($fate == 7){
		echo "You find someone else, while dating someone seriously. Roll 1d12 - odd they find out and dump you, even they never find out";
	}
	if($fate == 8){
		echo "You find someone else, while dating someone seriously. Roll 1d12 - odd they find out and dump you, even they never find out";
	}
	if($fate == 9){
		echo "Someone whom you'd been dating cheats on you. Roll 1d12. Even, you find out, odd they dump you and you don't know why";
	}
	if($fate == 10){
		echo "Someone whom you'd been dating cheats on you. Roll 1d12. Even, you find out, odd they dump you and you don't know why";
	}
	if($fate == 11){
		echo "Someone whom you'd been dating for over a year dies";
	}
	if($fate == 12){
		echo "Pregnancy. Roll 1d12; 1-3 You leave, 4-6 He/she Leaves You, 7-8 You Marry, 9-12 Crossbow Point Wedding";
	}
	echo ".  ";

	echo "</div>";
}


function enlightenment(){
	echo "<div style='padding-left:20px;'>";
	$fate = mt_rand(1,12);
	if($fate == 1 || $fate == 2 ||  $fate == 3){
		//Attribute Improvement
		$fate_attr = mt_rand(1,12);
			if($fate_attr == 1 || $fate_attr == 2){
				echo "Heavy labor and little rest develop your muscles (gain +1 Strength)";
			}
			if($fate_attr == 3 || $fate_attr == 4){
				echo "Running with a band of rogues or maybe at the circuit with the jugglers sharpen your reflexes (gain +1 Dexterity)";
			}
			if($fate_attr == 5 || $fate_attr == 6){
				echo "Training under a veteran or ex-mercenary, or time spent in harsh terrains, toughens you (gain +1 Constitution)";
			}
			if($fate_attr == 7 || $fate_attr == 8){
				echo "Time spent at an university or with a scholar teaches valuable skills and how to apply rational thinking (gain +1 Intelligence)";
			}
			if($fate_attr == 9 || $fate_attr == 10){
				echo "A sage or priest schools you in the benefits of faith and strength of mind (gain +1 Wisdom)";
			}
			if($fate_attr == 11 || $fate_attr == 12){
				echo "You meet a very charismatic leader or powerful entertainer who teaches you the strength of assertiveness and charm (gain +1 Charisma)";
			}

	}
	if($fate == 4 || $fate == 5 ||  $fate == 6){
		//Skill Learning
		$fate_attr = mt_rand(1,12);
			if($fate_attr == 1){
				echo "You are able to learn a new Strength-based skill.";
			}
			if($fate_attr == 2){
				echo "Through lots of practice or much use, one of your Strength-based skills improves.";
			}
			if($fate_attr == 3){
				echo "You are able to learn a new Dexterity-based skill.";
			}
			if($fate_attr == 4){
				echo "Through lots of practice or much use, one of your Dexterity-based skills improves.";
			}
			if($fate_attr == 5){
				echo "You are able to learn a new Constitution-based skill.";
			}
			if($fate_attr == 6){
				echo "Through lots of practice or much use, one of your Constitution-based skills improves.";
			}
			if($fate_attr == 7){
				echo "You are able to learn a new Intelligence-based skill.";
			}
			if($fate_attr == 8){
				echo "Through lots of practice or much use, one of your Intelligence-based skills improves.";
			}
			if($fate_attr == 9){
				echo "You are able to learn a new Wisdom-based skill.";
			}
			if($fate_attr == 10){
				echo "Through lots of practice or much use, one of your Wisdom-based skills improves.";
			}
			if($fate_attr == 11){
				echo "You are able to learn a new Charisma-based skill.";
			}
			if($fate_attr == 12){
				echo "Through lots of practice or much use, one of your Charisma-based skills improves.";
			}
	}
	if($fate == 7 || $fate == 8 ||  $fate == 9){
		//Magical Ability
		$fate_attr = mt_rand(1,12);
			if($fate_attr == 1){
				echo "You learn a new Abjuration spell.";
			}
			if($fate_attr == 2){
				echo "You learn a new Conjuration spell.";
			}
			if($fate_attr == 3){
				echo "You learn a new Divination spell.";
			}
			if($fate_attr == 4){
				echo "You learn a new Enchantment spell.";
			}
			if($fate_attr == 5){
				echo "You learn a new Evocation spell.";
			}
			if($fate_attr == 6){
				echo "You learn a new Illusion spell.";
			}
			if($fate_attr == 7){
				echo "You learn a new Necromancy spell.";
			}
			if($fate_attr == 8){
				echo "You learn a new Transmutation spell.";
			}
			if($fate_attr == 9){
				echo "1/day	You somehow gain the ability to use a Spell-Like Ability 1/day";
			}
			if($fate_attr == 10){
				echo "1/day	You somehow gain the ability to use a Spell-Like Ability 1/day";
			}
			if($fate_attr == 11){
				echo "1/day	You somehow gain the ability to use a Supernatural Ability 1/day";
			}
			if($fate_attr == 12){
				echo "1/day	You somehow gain the ability to use a Supernatural Ability 1/day";
			}
	}
	if($fate == 10 || $fate == 11 ||  $fate == 12){
		//New Feat
			$fate_attr = mt_rand(1,12);
			if($fate_attr == 1){
				echo "You learn how to use a weapon.";
			}
			if($fate_attr == 2){
				echo "You learn how to use a weapon.";
			}
			if($fate_attr == 3){
				echo "You learn how to handle a type of armor or shield.";
			}
			if($fate_attr == 4){
				echo "You learn how to handle a type of armor or shield.";
			}
			if($fate_attr == 5){
				echo "You gain a feat (for which you meet the prerequisites) which only improves skill(s).";
			}
			if($fate_attr == 6){
				echo "You gain a feat (for which you meet the prerequisites) which only improves skill(s).";
			}
			if($fate_attr == 7){
				echo "You learn a new Feat that has no prerequisites.";
			}
			if($fate_attr == 8){
				echo "You learn a new Feat that has no prerequisites.";
			}
			if($fate_attr == 9){
				echo "You learn a new General Feat for which you meet the prerequisites.";
			}
			if($fate_attr == 10){
				echo "You learn a new General Feat for which you meet the prerequisites.";
			}
			if($fate_attr == 11){
				echo "You learn a new Feat for which you meet the prerequisites.";
			}
			if($fate_attr == 12){
				echo "You learn a new Feat for which you meet the prerequisites.";
			}
	}
	echo "</div>";
}

function fate(){
	$race_selected = $_COOKIE['race'];
	$fate_years = mt_rand(0,12); 
	$seed = $GLOBALS['seed'];
	mt_srand($seed);
	$path = "json/races.json";
	$str = file_get_contents($path);
	$json = json_decode($str, true);
	$races = [];
	$rand_key = array_rand($json['races']);
		if ($json === null
		&& json_last_error() !== JSON_ERROR_NONE) {
			error_modal("ERROR: Invalid {$json_name} json","Your {$json_name} json file is invalid, please correct and try again.<br>[See <a href='http://jsonlint.com/' target='_blank'>http://jsonlint.com/</a>]");
		}else{
			foreach($json['races'] as $race)
			{
			  if($race_selected == $race['name']){
				$current_age = $race['maturationyear']+$fate_years;
				echo "<p>Your Current Age: ";
				echo $current_age;
				echo "</p>";
			  }
			}
		}
		if($fate_years >=1 && $fate_years <=12){
	
		for ($s = 1; $s <= $fate_years; $s++) {
			$fate = mt_rand(1,12);
			if($fate == 1 || $fate == 2){
				echo "<div class='card text-white bg-light'><div class='card-header'>
				<span class='badge badge-dark'>Year - {$s}</span> <span class='float-right'>You had a tragedy</span></div><div class='card-body'><p class='card-text' style='color:black;'>";
				tragedy();
				echo "</p></div></div>";
			}
			if($fate == 3 || $fate == 4){
				echo "<div class='card text-white bg-success'><div class='card-header'>
				<span class='badge badge-dark'>Year - {$s}</span> <span class='float-right'>You had a windfall!</span></div><div class='card-body'><p class='card-text'>";
				windfall();
				echo "</p></div></div>";
			}
			if($fate == 5 || $fate == 6){
				echo "<div class='card text-white bg-light'><div class='card-header'>
				<span class='badge badge-dark'>Year - {$s}</span> <span class='float-right'>You made a Friend</span></div><div class='card-body'><p class='card-text' style='color:black;'>";
				friend();
				echo "</p></div></div>";
			}
			if($fate == 7 || $fate == 8){
				echo "<div class='card text-white bg-dark'><div class='card-header'>
				<span class='badge badge-dark'>Year - {$s}</span> <span class='float-right'>You made an Enemy</span></div><div class='card-body'><p class='card-text'>";
				enemy();
				echo "</p></div></div>";
			}
			if($fate == 9 || $fate == 10){
				echo "<div class='card text-white bg-danger'><div class='card-header'>
				<span class='badge badge-dark'>Year - {$s}</span> <span class='float-right'>Found romance!</span></div><div class='card-body'><p class='card-text'>";
				romance();
				echo "</p></div></div>";
			}
			if($fate == 11 || $fate == 12){
				echo "<div class='card text-white bg-info'><div class='card-header'>
				<span class='badge badge-dark'>Year - {$s}</span> <span class='float-right'>Found Enlightenment</span></div><div class='card-body'><p class='card-text'>";
				enlightenment();
				echo "</p></div></div>";
			}
		}
	}
}

//Handle Errors
function error_modal($title,$message){
	echo '<div class="modal fade" tabindex="-1" role="dialog" id="ErrorModal" aria-hidden="true" data-backdrop="false">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header alert-danger">
		  <h5 class="modal-title" id="exampleModalLabel">';
			echo $title;
			echo '</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
			</div>
			<div class="modal-body">';
			echo $message;
		  echo '</div><div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div></div></div></div><script>$("#ErrorModal").modal();</script>';
}


?>
