<?php
//if(isset($_GET['seed'])){$GLOBALS['seed'] = $_GET['seed'];}else{$GLOBALS['seed'] = 42;}
$GLOBALS['seed'] = time();
srand($GLOBALS['seed']);

//Name generator
function generate_first_name($gender){
	if($gender == 'male'){
		$names = array_map('str_getcsv', file('names/Human_Names_-_Western_Male.csv'));
	}else{
		$names = array_map('str_getcsv', file('names/Human_Names_-_Western_Female.csv'));
	}
	//Generate a random forename.
	$random_name = $names[mt_rand(0, sizeof($names) - 1)];
	//Combine them together and print out the result.
	return $random_name[1];
}
function generate_last_name(){
	$surnames = array_map('str_getcsv', file('names/Human_Names_-_Western_Surname.csv'));
	//Generate a random surname.
	$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];
	//Combine them together and print out the result.
	$GLOBALS['lname'] = $random_surname[1];
}

// Generate the session
function generate($json_name){
	$path = "json/".$json_name.".json";
	$str = file_get_contents($path);
	$result_array = [];
	$json = json_decode($str, true); // decode the JSON into an associative array
		if ($json === null
		&& json_last_error() !== JSON_ERROR_NONE) {
			error_modal("ERROR: Invalid {$json_name} json","Your {$json_name} json file is invalid, please correct and try again.<br>[See <a href='http://jsonlint.com/' target='_blank'>http://jsonlint.com/</a>]");
		}else{
			if (isset($json[$json_name])){
				srand($GLOBALS['seed']);

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

			$_SESSION['lifepath'][$json_name] = $json[$json_name][$rand_key];
				foreach ($_SESSION['lifepath'][$json_name] as $key => $val) {
					if($key != 'weight'){
					$result_array[0] = $key;
					$result_array[1] = $val;
						if($json_name == 'birth'){
							echo $result_array[1][0];
						}elseif($json_name == 'caretakers_status'){
							$father_name = generate_first_name('male');
							$mother_name = generate_first_name('female');
							echo "Your father {$father_name} and mother {$mother_name} ";
							echo $result_array[1];
						}else{
							echo $result_array[1];
						}
							if($key == 'Misfortune'){
								echo " ";
								generate('misfortune');
							}
							if($key == 'Death'){
								echo ", ";
								generate('death');
							}
					}
				}
			}else{
				error_modal('ERROR: Invalid {$json_name} type selected','Your {$json_name} type selection is invalid, please correct and try again.');
			}
		}
}

function siblings(){
	$siblings = mt_rand(1,6);
		if($siblings >=1 && $siblings <=5){
		$number_of_siblings = mt_rand(1,12);
		echo "with $number_of_siblings siblings.<br>";
		for ($s = 1; $s <= $number_of_siblings; $s++) {

			$fate = mt_rand(1,12);
			$gender = mt_rand(1,2);
			if($gender == 1){
					$gender_type = "brother";
					$fname = generate_first_name('male');
				}else{
					$gender_type = "sister";
					$fname = generate_first_name('female');
				}
			if($fate == 1 || $fate == 2){
				echo "{$fname}, lost touch, it is unknown to you what became of them";
			}
			if($fate == 3 || $fate == 4){
				echo "{$fname}, lives at home with your parents";
			}
			if($fate == 5 || $fate == 6){
				echo "{$fname}, has had bad luck in life";
				echo ", ";
				generate('misfortune');
			}
			if($fate == 7 || $fate == 8){
				echo "{$fname}, keeps in touch, they are enjoying their own life";
			}
			if($fate == 9 || $fate == 10){
				echo "{$fname}, hates you, for some past transgression";
			}
			if($fate == 11 || $fate == 12){
				echo "{$fname}, is dead";
				echo ", ";
				generate('death');
			}
echo "<br>";
		}
	}
	if($siblings == 6){
		echo "as an only child.";
	}

}

function tragedy(){
	$fate = mt_rand(1,12);
	if($fate == 1 || $fate == 2){
		echo "you lost all your gold/possessions";
	}
	if($fate == 3 || $fate == 4){
		echo "you made yourself indebted to someone or some group";
	}
	if($fate == 5 || $fate == 6){
		echo "you ended up imprisoned for several months";
	}
	if($fate == 7 || $fate == 8){
		echo "you had a serious accident that left you incapacitated for several months";
	}
	if($fate == 9 || $fate == 10){
		echo "you spent several months battling an addiction";
	}
	if($fate == 11 || $fate == 12){
		echo "you lost a pet";
	}
}
function windfall(){
	$fate = mt_rand(1,12);
	if($fate == 1 || $fate == 2){
		echo "You nearly double your worth";
	}
	if($fate == 3 || $fate == 4){
		echo "You managed to be at the right place and at the right time and someone owes you";
	}
	if($fate == 5 || $fate == 6){
		echo "Through your deeds you manage to make a name for yourself locally";
	}
	if($fate == 7 || $fate == 8){
		echo "You find a sibling you never knew you had";
		siblings();
	}
	if($fate == 9 || $fate == 10){
		echo "You find yourself a pet";
	}
	if($fate == 11 || $fate == 12){
		echo "The opportunity arises to travel to far distant lands for several months";
	}
}

function heft(){
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
function animosity(){
	$fate = mt_rand(1,12);
	if($fate == 1){
		echo "Humiliation - Caused the loss of face or status publicly";
	}
	if($fate == 2){
		echo "Rift	- Caused the loss of a friend or lover";
	}
	if($fate == 3){
		echo "Busted - Truly or falsely brought criminal charges against the person";
	}
	if($fate == 4){
		echo "Betrayed - Left the other out to dry or outright backstabbing";
	}
	if($fate == 5){
		echo "Cold Shoulder	- Turned down for a job or turned down romantic advances";
	}
	if($fate == 6){
		echo "Rival	- Had been competing for a job or romantically and won over the other";
	}
	if($fate == 7){
		echo "Foiled - Caused the failure of some plot, quest or undertaking";
	}
	if($fate == 8){
		echo "because you defeated them in combat or game/gamble";
	}
	if($fate == 9){
		echo "Bigotry - From race to religious beliefs, the hatred stems from stereotype";
	}
	if($fate == 10){
		echo "Murdered	- Convinced this person killed a friend/relative/lover";
	}
	if($fate == 11){
		echo "Jealousy	- This person's looks/life/luck/wealth bother you";
	}
	if($fate == 12){
		echo "Took Advantage - Took economic advantage by scam, or physical advantage through force";
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
	heft();
	animosity();
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
	heft();
	echo "</div>";
}
function enlightenment(){
	$fate = mt_rand(1,12);
	if($fate == 1 || $fate == 2 ||  $fate == 3){
		//Attribute Improvement
		$fate = mt_rand(1,12);
			if($fate == 1 || $fate == 2){
				echo "Heavy labor and little rest develop your muscles (gain +1 Strength)";
			}
			if($fate == 3 || $fate == 4){
				echo "Running with a band of rogues or maybe at the circuit with the jugglers sharpen your reflexes (gain +1 Dexterity)";
			}
			if($fate == 5 || $fate == 6){
				echo "Training under a veteran or ex-mercenary, or time spent in harsh terrains, toughens you (gain +1 Constitution)";
			}
			if($fate == 7 || $fate == 8){
				echo "Time spent at an university or with a scholar teaches valuable skills and how to apply rational thinking (gain +1 Intelligence)";
			}
			if($fate == 9 || $fate == 10){
				echo "A sage or priest schools you in the benefits of faith and strength of mind (gain +1 Wisdom)";
			}
			if($fate == 11 || $fate == 12){
				echo "You meet a very charismatic leader or powerful entertainer who teaches you the strength of assertiveness and charm (gain +1 Charisma)";
			}

	}
	if($fate == 4 || $fate == 5 ||  $fate == 6){
		//Skill Learning
		/*
		Strength Skill, New	You are able to learn a new Strength-based skill.
		2	Strength Skill, Improvement	Through lots of practice or much use, one of your Strength-based skills improves.
		3	Dexterity Skill, New	You are able to learn a new Dexterity-based skill.
		4	Dexterity Skill, Improvement	Through lots of practice or much use, one of your Dexterity-based skills improves.
		5	Constitution Skill, New	You are able to learn a new Constitution-based skill.
		6	Constitution Skill, Improvement	Through lots of practice or much use, one of your Constitution-based skills improves.
		7	Intelligence Skill, New	You are able to learn a new Intelligence-based skill.
		8	Intelligence Skill, Improvement	Through lots of practice or much use, one of your Intelligence-based skills improves.
		9	Wisdom Skill, New	You are able to learn a new Wisdom-based skill.
		10	Wisdom Skill, Improvement	Through lots of practice or much use, one of your Wisdom-based skills improves.
		11	Charisma Skill, New	You are able to learn a new Charisma-based skill.
		12	Charisma Skill, Improvement	Through lots of practice or much use, one of your Charisma-based skills improves.
		*/
	}
	if($fate == 7 || $fate == 8 ||  $fate == 9){
		//Magical Ability
		/*
		1	Abjuration	You learn a new Abjuration spell.
		2	Conjuration	You learn a new Conjuration spell.
		3	Divination	You learn a new Divination spell.
		4	Enchantment	You learn a new Enchantment spell.
		5	Evocation	You learn a new Evocation spell.
		6	Illusion	You learn a new Illusion spell.
		7	Necromancy	You learn a new Necromancy spell.
		8	Transmutation	You learn a new Transmutation spell.
		9-10	Spell-Like Ability, 1/day	You somehow gain the ability to use a Spell-Like Ability 1/day
		11-12	Supernatural Ability, 1/day	You somehow gain the ability to use a Supernatural Ability 1/day
		*/
	}
	if($fate == 10 || $fate == 11 ||  $fate == 12){
		//New Feat
		/*
		1-2	Weapon Proficiency	You learn how to use a weapon.
		3-4	Armor or Shield Proficiency	You learn how to handle a type of armor or shield.
		5-6	Skill Feat	You gain a feat (for which you meet the prerequisites) which only improves skill(s).
		7-8	Basic Feat	You learn a new Feat that has no prerequisites.
		9-10	General Feat	You learn a new General Feat for which you meet the prerequisites.
		11-12	Feat	You learn a new Feat for which you meet the prerequisites.
		*/
	}
}

function fate(){
	$yrs = mt_rand(1,12);
		if($yrs >=1 && $yrs <=12){

		$fate = mt_rand(1,12);
		for ($s = 1; $s <= $fate; $s++) {

			$fate = mt_rand(1,12);
			if($fate == 1 || $fate == 2){
				echo "<div style='padding-left:20px;'>";
				echo "A few years ago, ";
				tragedy();
				echo "</div>";
			}
			if($fate == 3 || $fate == 4){
				echo "<h4>Windfall - <small>Lady luck smiles on you</small></h4>";
				echo "<div style='padding-left:20px;'>";
				windfall();
				echo "</div>";
			}
			if($fate == 5 || $fate == 6){
				echo "<h4>Made a Friend - <small>Someone enters your life in a beneficial way</small></h4>";
				echo "<div style='padding-left:20px;'>";
				friend();
				echo "</div>";
			}
			if($fate == 7 || $fate == 8){
				echo "<h4>Made an Enemy - <small>You rubbed someone wrong</small></h4>";
				echo "<div style='padding-left:20px;'>";
				enemy();
				echo "</div>";
			}
			if($fate == 9 || $fate == 10){
				echo "<h4>Romance - <small>A relationship develops</small></h4>";
				echo "<div style='padding-left:20px;'>";

				echo "</div>";
			}
			if($fate == 11 || $fate == 12){
				echo "<h4>Personal Enlightenment - <small>Events unfold that help you grow as a person</small></h4>";
				echo "<div style='padding-left:20px;'>";
				enlightenment();
				echo "</div>";
			}
		}
	}
}

//Handle Errors
function error_modal($title,$message){
	echo '<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header alert-danger">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">';
			echo $title;
			echo '</h4></div><div class="modal-body">';
			echo $message;
		  echo '</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>
		  <script>$("#myModal").modal();</script>';
}


?>
