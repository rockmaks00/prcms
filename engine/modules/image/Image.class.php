<?
class ModuleImage extends Module {
	public function Init() {
	}
	public function UploadImage($aFile, $sPath) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
		$sExt=explode(".", $aFile['name']); $sExt=$sExt[count($sExt)-1];
		$sPath="files/".$sPath."/";
		$this->MkDir($sPath);
		$sFileName=$sPath.substr(md5($aFile['tmp_name'].time()),0,8).".".$sExt;
		if (!move_uploaded_file($aFile['tmp_name'],$sFileName)) {			
			return false;
		}
		return "/".$sFileName;
	}
	public function GetUploadedFilePath($aFile, $sPath){
		if (isset($aFile) and is_uploaded_file($aFile['tmp_name'])) {				
			if ($sFileName=$this->UploadImage($aFile, $sPath)) {	
				return $sFileName;
			} else {
				$this->Template_AddMessage("Ошибка!","Не получилось загрузить фото!");
				return false;
			}
		}else{
			return null;
		}
	}
	public function Delete($sPath) {
		if($sPath){
			$sPath=substr($sPath, 1);
			unlink($sPath);
			return true;
		}
		return false;
	}
	public function MkDir($sPath) {
		mkdir($sPath, 0777, true);
		return true;
	}
	public function getFullName($sSrc) {
		return pathinfo($sSrc, PATHINFO_BASENAME);
	}
	public function getName($sSrc){
		return pathinfo($sSrc, PATHINFO_FILENAME);
	}
	public function getImgType($sSrc){
		return strtolower(pathinfo($sSrc, PATHINFO_EXTENSION));
	}
	public function GetRemoteLastModified( $sUri ){
		$iUnixTime = 0;
		$fp = fopen( $sUri, "r" );
		if( !$fp ) {return;}
		$MetaData = stream_get_meta_data( $fp );
		foreach( $MetaData['wrapper_data'] as $sResponse ){
			if( substr( strtolower($sResponse), 0, 10 ) == 'location: ' ){
				$sNewUri = substr( $sResponse, 10 );
				fclose( $fp );
				return self::GetRemoteLastModified( $sNewUri );
			}elseif( substr( strtolower($sResponse), 0, 15 ) == 'last-modified: ' ){
				$iUnixTime = strtotime( substr($sResponse, 15) );
				break;
			}
		}
		fclose( $fp );
		return $iUnixTime;
	}
	public function AdaptImage($sSrc, $iNewWidth, $iNewHeight, $iCrop=1, $iLeft=null, $iTop=null) {
		$iQuality=Config::Get("image.quality");
		$sCacheFolder=Config::Get("image.cache.dir");

		if (!file_exists($sCacheFolder)) self::MkDir($sCacheFolder);
		//if (!file_exists($sCacheFolder)) $this->MkDir("cache/files/");
		
		if ( empty($sSrc) ) return;
		if ( !is_numeric($iTop) or $iTop<0 ) $iTop = false;
		if ( !is_numeric($iLeft) or $iLeft<0 ) $iLeft = false;
		$iNewHeight = $iNewHeight<0 ? 150 : (int)$iNewHeight;
		$iNewWidth = $iNewWidth<0 ? 100 : (int)$iNewWidth;
		if(!$iNewHeight && !$iNewWidth){$iNewHeight=150;$iNewWidth=100;}

		if( !strpos(" ".$sSrc , "http://") ){
			if( strpos($sSrc , "/")===0 ) {
				$sSrc = $_SERVER['DOCUMENT_ROOT'].$sSrc;
			}
		}elseif( strpos( " ".$sSrc, Config::Get("host") ) ){
			$sSrc = str_replace(Config::Get("host"), $_SERVER['DOCUMENT_ROOT']."/", $sSrc);
		}else{
			$bRmoteMode=true;
		}

		$sExt = self::getImgType($sSrc);
		$iLastModified = ($bRmoteMode?self::GetRemoteLastModified( $sSrc ):filemtime( $sSrc ));
		$sNewImg = "file_".md5($sSrc.$iNewHeight.$iNewWidth.Config::Get("image.quality").$iLeft.$iTop.$iCrop.$iLastModified).".".$sExt;

		if ( file_exists($sCacheFolder.$sNewImg) and $iLastModified==filemtime($sCacheFolder.$sNewImg)){

			return Config::Get("host").$sCacheFolder.$sNewImg;

		} else {

			/*
			$iOrig__ - размеры исходника
			$iNew__  - желаемые размеры
			$iOld__  - размеры куска оригинала, откуда брать
			iCalculated__ - расчитанные размеры будущего изображения (до они могут не совпадать)
			*/

			list($iOrigWidth, $iOrigHeight) = getimagesize($sSrc);
			if(!$iOrigWidth) return;
			if ($iNewWidth and $iNewHeight){
				if($iCrop){
					if ($iNewHeight<=$iOrigHeight and $iNewWidth<=$iOrigWidth){					// исходник в пределах нужных размеров
						if( $iOrigWidth/$iOrigHeight >= $iNewWidth/$iNewHeight ){ 	// если шире, тогда высота вся
							$iOldWidth  = $iOrigHeight*($iNewWidth/$iNewHeight);
							$iOldHeight = $iOrigHeight;
						}else{														// если уже, тогда ширина вся
							$iOldWidth  = $iOrigWidth;
							$iOldHeight = $iOrigWidth*($iNewHeight/$iNewWidth);
						}
						$iCalculatedWidth  = $iNewWidth;
						$iCalculatedHeight = $iNewHeight;
						$iOffsetLeft = ($iOrigWidth  - $iOldWidth )/2;
						$iOffsetTop  = ($iOrigHeight - $iOldHeight)/2;
					}elseif( $iNewHeight>$iOrigHeight and $iNewWidth<=$iOrigWidth ){				// исходник ниже нужных размеров (ресайз не нужен)
						$iOldWidth  = $iNewWidth;
						$iOldHeight = $iOrigHeight;
						$iCalculatedWidth  = $iNewWidth;
						$iCalculatedHeight = $iOrigHeight;
						$iOffsetLeft = ($iOrigWidth-$iOldWidth)/2;
						$iOffsetTop  = 0;
					}elseif( $iNewHeight<=$iOrigHeight and $iNewWidth>$iOrigWidth ){				// исходник уже нужных размеров (ресайз не нужен)
						$iOldWidth  = $iOrigWidth;
						$iOldHeight = $iNewHeight;
						$iCalculatedWidth  = $iOrigWidth;
						$iCalculatedHeight = $iNewHeight;
						$iOffsetLeft = 0;
						$iOffsetTop  = ($iOrigHeight-$iOldHeight)/2;
					}else{																			// исходник меньше нужных размеров (ресайз и кроп не нужен)
						$iOldWidth  = $iOrigWidth;
						$iOldHeight = $iOrigHeight;
						$iCalculatedWidth  = $iOrigWidth;
						$iCalculatedHeight = $iOrigHeight;
						$iOffsetLeft = 0;
						$iOffsetTop  = 0;
					}
				}else{
					if ( $iNewWidth<=$iOrigWidth or $iNewHeight<=$iOrigHeight ){				// исходник в пределах нужных размеров
						if( $iOrigWidth/$iOrigHeight > $iNewWidth/$iNewHeight ){		//если шире
							$iCalculatedWidth  = $iNewWidth;
							$iCalculatedHeight = $iCalculatedWidth*($iOrigHeight/$iOrigWidth);
						}else{ 															//если уже
							$iCalculatedHeight = $iNewHeight;
							$iCalculatedWidth  = $iCalculatedHeight*($iOrigWidth/$iOrigHeight);
						}
					}else{																				// исходник меньше нужных размеров (ресайз и кроп не нужен)
						$iCalculatedWidth  = $iOrigWidth;
						$iCalculatedHeight = $iOrigHeight;
					}
					$iOffsetLeft = 0;
					$iOffsetTop  = 0;
					$iOldWidth  = $iOrigWidth;
					$iOldHeight = $iOrigHeight;
				}
			}elseif( $iNewWidth and !$iNewHeight ){
				if( $iNewWidth < $iOrigHeight ) {
					$iCalculatedWidth  = $iNewWidth;
					$iCalculatedHeight = (int)( $iOrigWidth*( $iCalculatedHeight/$iOrigHeight ) );
				}else{
					$iCalculatedHeight = $iOrigHeight;
					$iCalculatedWidth  = $iOrigWidth;
				}
				$iOffsetLeft = 0;
				$iOffsetTop  = 0;
				$iOldWidth  = $iOrigWidth;
				$iOldHeight = $iOrigHeight;
			}elseif( !$iNewWidth and $iNewHeight ){
				if( $iNewHeight < $iOrigWidth ){
					$iCalculatedHeight = $iNewHeight;
					$iCalculatedWidth  = (int)( $iOrigHeight*( $iCalculatedWidth/$iOrigWidth ) );
				}else{
					$iCalculatedHeight = $iOrigHeight;
					$iCalculatedWidth  = $iOrigWidth;
				}
				$iOffsetLeft = 0;
				$iOffsetTop  = 0;
				$iOldWidth   = $iOrigWidth;
				$iOldHeight  = $iOrigHeight;
			}
			$iOffsetLeft = (($iOffsetLeft && $iLeft) ? ($iLeft<($iOffsetLeft*2) ? $iLeft : $iOffsetLeft*2) : $iOffsetLeft);
			$iOffsetTop  = (($iOffsetTop  && $iTop ) ? ($iTop <($iOffsetTop*2)  ? $iTop  : $iOffsetTop*2 ) : $iOffsetTop );

			$sCachedImg = $sCacheFolder.$sNewImg;

			if( $sExt == "jpg" || $sExt == "jpeg") {
				$oSrcImg =ImageCreateFromJpeg($sSrc);
				$oDstImg = imagecreatetruecolor($iCalculatedWidth, $iCalculatedHeight);
				imagecopyresampled($oDstImg,$oSrcImg,0,0,$iOffsetLeft,$iOffsetTop,$iCalculatedWidth,$iCalculatedHeight,$iOldWidth,$iOldHeight);
				Imagejpeg($oDstImg, $sCachedImg, Config::Get("image.quality"));
			}
			if($sExt == "gif") {
				$oDstImg=ImageCreate($iCalculatedWidth,$iCalculatedHeight);
				$oSrcImg=ImageCreateFromGif($sSrc);

				//добавляем прозрачность
				$oTransparentSourseIndex = imagecolortransparent($oSrcImg);
				if($oTransparentSourseIndex!==-1){
					$oTransparentColor=imagecolorsforindex($oSrcImg, $oTransparentSourseIndex);
					$oTransparentDestinationIndex=imagecolorallocate($oDstImg, $oTransparentColor['red'], $oTransparentColor['green'], $oTransparentColor['blue']);
					imagecolortransparent($oDstImg, $oTransparentDestinationIndex);
					imagefill($oDstImg, 0, 0, $oTransparentDestinationIndex);
				}
				ImageCopyResized($oDstImg,$oSrcImg,0,0,$iOffsetLeft,$iOffsetTop,$iCalculatedWidth,$iCalculatedHeight,$iOldWidth,$iOldHeight);
				Imagegif($oDstImg, $sCachedImg);
			}
			if($sExt == "png") {
				$oSrcImg=ImageCreateFromPng($sSrc);
				$oDstImg = imagecreatetruecolor($iCalculatedWidth, $iCalculatedHeight);

				//добавляем прозрачность
				imagealphablending($oDstImg, false);
				imagesavealpha($oDstImg, true);
				ImageCopyResampled($oDstImg, $oSrcImg,0,0, $iOffsetLeft,$iOffsetTop, $iCalculatedWidth,$iCalculatedHeight, $iOldWidth,$iOldHeight);
				Imagepng($oDstImg, $sCachedImg);
			}
			ImageDestroy($oDstImg);

			@chmod($sCachedImg, 0777);
			touch($sCachedImg, $iLastModified);

			return Config::get("host").$sCachedImg; //."?qwe=".$iOffsetLeft."_".$iOffsetTop."_".$iCalculatedWidth."_".$iCalculatedHeight."_".$iOldWidth."_".$iOldHeight;
		}
	}
	public static function Crop($sSrc, $iNewWidth, $iNewHeight, $iLeft=null, $iTop=null) {
		return self::AdaptImage($sSrc, $iNewWidth, $iNewHeight, 1, $iLeft, $iTop);
	}
	public static function Resize($sSrc, $iNewWidth, $iNewHeight){
		return self::AdaptImage($sSrc, $iNewWidth, $iNewHeight, 0);
	}
}