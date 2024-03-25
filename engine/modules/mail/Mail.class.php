<?
class ModuleMail extends Module {
	public function Init() {}

	public function SendMail(ModuleMail_EntityMail $oMail){

		if( $oMail->getTo() && $oMail->getSubject() && $oMail->getMessage() ){
			if( mail(	$oMail->getTo(),
						$oMail->getSubject(),
						$oMail->getMessage(),
						$oMail->getHeaders()
					)
				) return true;
			else return false;
		}else return false;

	}
}