<?
/*
Компонент поиска опрашивает все компоненты, выбранные в админпанели для проверки
запрос идет по шаблону:
$this->Component{Имя компонента}_{Имя компонента}_Search({Массив обработанных слов});
соответственно в каждом модуле компонента должен быть метод Search
Этот метод возвращает объект (сущность класса ComponentSearch_ModuleSearch_EntityResult не имеет привязки к базе данных) в котором должны быть установлены следующие свойства:
Node 	- объект раздела на который ссылкается результат поиска
Url 	- ссылка, задается отдельно, так как может быть отлична от url раздела (например конкретная новость)
Count 	- число совпадений, по нему будут сортироваться окончательные результаты
Text 	- текст (опционально)
Title 	- заголовок ссылки (опционально, если не задан, будет взят заголовок раздела)

ЗЫ Если искать в компоненте нечего, не поленитесь вставить метод Search (желательно возвращающий пустой массив) иначе движок сайта выдаст FatalError!!!
*/
require_once("external/Lingua_Stem_Ru.php");
class ComponentSearch extends Component {
	public $oNode=null;
	/*public $aParamsDefault=array(
		"template" => array(
			"title" => "Шаблон",
			"type" => "select",
			"values" => array(
				"default.tpl"=>"По умолчанию (default.tpl)"
			),
			"default" => "default.tpl"
		)
	);*/

	public function Init(){
		$this->SetDefaultAction('default');
		$this->oNode=Router::GetCurrentNode();
		$this->Template_SetPageTitle($this->oNode->getTitle());
		$this->Template_AddTitle($this->oNode->getTitle());
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddAction('results','ActionResults');
		$this->AddActionPreg('/^bad$/i','/^(page(\d+))?$/i','ActionDefault');
		$this->AddActionPreg('/^new$/i','/^(page(\d+))?$/i','ActionDefault');
	}
		
	protected function ActionDefault() {
		$this->SetTemplate("default.tpl");
	}

	protected function ActionEmpty(){
		$this->Template_Assign("iEmpty", "1");
		$this->SetTemplate("default.tpl");
	}

	protected function ActionResults() {
		$aSearchResults = array();
		$sSearchQuery = getRequest("search");
		$aWords = $this->ComponentSearch_ModuleSearch_ClearText($sSearchQuery);
		if( empty($aWords) ) return $this->ActionEmpty();
		if( count($aWords)>7 ) $aWords = array_slice($aWords, 0, 7);

		$aSearchResults = $this->Node_Search($aWords);
		$aComponents = $this->ComponentSearch_ModuleSearch_GetComponentsToSearchByNode( $this->oNode->getId() );

		foreach( $aComponents as $oComponent){
			$sComponent = ucfirst($oComponent->getComponentName());
			eval('$aTmpResults = $this->Component'.$sComponent."_Module".$sComponent.'_Search($aWords);');
			foreach( $aTmpResults as $oResult){
				$sKey = md5($oResult->getUrl());
				if( !$oResult->getNode()->getActive() ) continue;
				if( !$aSearchResults[ $sKey ] ){
					$aSearchResults[ $sKey ] = $oResult;
				}else{
					$aSearchResults[ $sKey ]->addCount( $oResult->getCount() );
					$aSearchResults[ $sKey ]->setText($oResult->getText());
				}
			}
		}

		if( empty($aSearchResults) ) return $this->ActionEmpty();

		usort($aSearchResults, function($a, $b){
			$iCountA = $a->getCount();
			$iCountB = $b->getCount();
			if($iCountA==$iCountB) return 0;
			return $iCountA > $iCountB ? -1 : 1;
		});

		$this->Template_Assign("aResult", $aSearchResults);
		$this->SetTemplate("default.tpl");
	}
}