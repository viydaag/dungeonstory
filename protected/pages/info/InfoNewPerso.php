<?php
class InfoNewPerso extends DSPage
{	
	public function onLoad($param) {
		parent::onLoad($param);
        if(!$this->IsPostBack) {
            $racefinder = RaceRecord::finder();
			$races = $racefinder->findAll();
			$this->RaceRepeater->DataSource = $races;
			$this->RaceRepeater->dataBind();
			
			$this->RaceAgeRepeater->DataSource = $races;
			$this->RaceAgeRepeater->dataBind();
			
			$this->RaceTaillePoidsRepeater->DataSource = $races;
			$this->RaceTaillePoidsRepeater->dataBind();

			$classefinder = ClasseRecord::finder();
			$classes = $classefinder->findAll();
			$this->ClasseList->DataSource = $classes;
			$this->ClasseList->dataBind();
			
			$alignementfinder = AlignementRecord::finder();
			$alignements = $alignementfinder->findAll();
			$this->AlignementRepeater->DataSource = $alignements;
			$this->AlignementRepeater->dataBind();
			
			$regionfinder = RegionRecord::finder();
			$regions = $regionfinder->findAll();
			$this->RegionList->DataSource = $regions;
			$this->RegionList->dataBind();
		}
	}
}
?>