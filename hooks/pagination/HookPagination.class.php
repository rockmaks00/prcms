<?
/*
пример использования
name - обязательный параметр, имя Hooka
template - обязательный параметр, имя шаблона
count - обязательный параметр, общее число элементов
onpage - обязательный параметр, элементов на странице
current - обязательный параметр, текущая страница
{hook name="pagination" template="default" count=$iCount onapge=$iOnpage curren=$iCurrent}
*/
class HookPagination extends Hook {
	protected $aParams;
	public function Init() {}

	public function Pagination($aParams) {
		$this->aParams = $aParams;

		$iPages = $this->aParams["count"]/$this->aParams["onpage"];
		$iCurrent = $this->aParams["page"];
		if(!$iCurrent) $iCurrent = 1;
		$this->Template_Assign('iPages', $iPages);
		$this->Template_Assign('iCurrent', $this->aParams["page"]);
		return $this->Template_Fetch("hooks/pagination/templates/".$aParams['template'].".tpl");
	}
}