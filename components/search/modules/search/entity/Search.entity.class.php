<?
class ComponentSearch_ModuleSearch_EntitySearch extends Entity {
	public function getComponentName(){
		$oEngine = Engine::GetInstance();
		$oComponent = $oEngine->Component_GetComponentById( $this->getComponent() );
		if( $oComponent ) return $oComponent->getName();
		return false;
	}
}	