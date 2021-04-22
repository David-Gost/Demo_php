<?php

//已經修改成可以轉換其他類型的方式
//下一步 統一來源檔案，自動判斷是不是base64

/**
 * 變數
 * @param string $sourceFile <p>
 * 檔案來源路徑＋檔名
 * </p>
 * @param string $sourceBase64 <p>
 * 檔案來源base64
 * </p>
 * @param bool $inputBase64 <p>
 * 是否輸入為Base64 
 * </p>
 * @param ImageZoomFileType $makeOriginalType <p>
 * 儲存的檔案類型 ex: ImageZoomFileType::Original
 * </p>
 * @param bool $isMatchTargetWH <p>
 * 是否背景填滿(不填滿=false、填滿=true)預設為false
 * </p>
 * @param int $targetW <p>
 * 縮放的寬 ex: 640
 * </p>
 * @param int $targetH <p>
 * 縮放的高 ex: 640
 * </p>
 * @param ImageZoomMakeMode $type <p>
 * 模式(縮放=Zoom、裁切=Cut、不動作=None)預設為Zoom
 * </p>
 * @param int $addWaterMark <p>
 * 浮水印(無=false、有=true)預設為false
 * </p>
 * @param ImageZoomSite $site <p>
 * 裁切位置(原點(左上角)=LeftTop、中心點=Center)預設為LeftTop
 * </p>
 * @param float $moom <p>
 * 浮水印縮放率(0.3為0.3倍)預設為0.3
 * </p>
 * @param bool $isTransparent <p>
 * 背景是否透明(不透明=false、透明=true)預設為true
 * </p>
 * @param int $colorR <p>
 * 填滿的背景顏色紅色數值
 * </p>
 * @param int $colorG <p>
 * 填滿的背景顏色綠色數值
 * </p>
 * @param int $colorB <p>
 * 填滿的背景顏色藍色數值
 * </p>
 * @param string $waterFile <p>
 * 浮水印 檔案路徑＋檔名
 * </p>
 * @param bool $outputBase64 <p>
 * 是否回傳為Base64 
 * </p>
 */
class ImageZoom {

//公用
    public $sourceFile = NULL;
    public $sourceBase64 = NULL;
    public $inputBase64 = FALSE;
    public $makeOriginalType = ImageZoomFileType::Original;
    public $isMatchTargetWH = FALSE;
    public $targetW = NULL;
    public $targetH = NULL;
    public $type = ImageZoomMakeMode::Zoom;
    public $addWaterMark = FALSE;
    public $site = ImageZoomSite::LeftTop;
    public $moom = 0.3;
    public $isTransparent = TRUE;
    public $colorR = 255;
    public $colorG = 255;
    public $colorB = 255;
    public $waterFile = NULL;
    public $outputBase64 = FALSE;
//私有
    private $targetW2 = 0;
    private $targetH2 = 0;
    private $type_X = 0;
    private $type_Y = 0;
    private $type_W = 0;
    private $type_H = 0;

    /**
     * 圖片處理
     */
    /**
     * constructor
     *
     */
//    public function __construct() {
//        $this->waterFile = site_url() . '/resource/_source/water.png';
//    }

    /**
     * 圖片處理
     */
    public function makepic() {
        if (!$this->sourceFile && !$this->sourceBase64) {
            return;
        }

        if ($this->sourceFile) {
            if (!is_file($this->sourceFile)) {
                return;
            }
        }

        $tmpfilename = $this->getNewImageName($this->sourceFile);
        $tmpfilepath = $this->getNewImagePathAndName($tmpfilename);

        switch ($this->type) {
            case ImageZoomMakeMode::None :
                $tmpfilename = $this->getNewImageName();
                $tmpfilepath = $this->getNewImagePathAndName($tmpfilename);
                move_uploaded_file(iconv('utf-8', 'big5', $this->sourceFile), $tmpfilepath);
                break;
            default :
                $this->initialMoom();
                $this->initialColor();

                if ($this->inputBase64) {
                    $imageContent = base64_decode($this->sourceBase64);
                    $im = imagecreatefromstring($imageContent);
                } else {
                    $file = fopen($this->sourceFile, "r");
                    $srcFile = fread($file, filesize($this->sourceFile));
                    $im = imagecreatefromstring($srcFile);
                    fclose($file);
                }

                if ($this->inputBase64) {
                    $imageTypeData = getimagesizefromstring(base64_decode($this->sourceBase64));
                } else {
                    $imageTypeData = getimagesize($this->sourceFile);
                }

                $srcW = ImageSX($im); //原始圖片的寬度,也可以使用$data[0]
                $srcH = ImageSY($im); //原始圖片的高度,也可以使用$data[1]
                if (($this->targetW == '' or $this->targetW == null) and $this->targetW != '0') {
                    $this->targetW = $srcW;
                }
                if (($this->targetH == '' or $this->targetH == null) and $this->targetH != '0') {
                    $this->targetH = $srcH;
                }
                $srcX = 0; //來源圖的坐標x,y
                $srcY = 0;

                if ($this->isMatchTargetWH and $this->type != ImageZoomMakeMode::Zoom) {
                    if ($this->targetW > $this->targetW)
                        $this->targetW = $this->targetW;
                    if ($this->targetH > $srcH)
                        $this->targetH = $srcH;
                }
                if (($srcW / $this->targetW) > ($srcH / $this->targetH)) {//得出要生成圖片的長寬
                    $this->targetW2 = $this->targetW; //輸出圖片的寬度、高度
                    $this->targetH2 = $srcH * $this->targetW / $srcW;
                    if ($this->isMatchTargetWH != 1) {
                        if ($this->targetW > $this->targetW2)
                            $this->targetW = $this->targetW2;
                        if ($this->targetH > $this->targetH2)
                            $this->targetH = $this->targetH2;
                    }
                    $dstX = 0; //輸出圖形的坐標x,y
                    $dstY = ($this->targetH - $this->targetH2) / 2;
                } else {
                    $this->targetH2 = $this->targetH; //輸出圖片的寬度、高度
                    $this->targetW2 = $srcW * $this->targetH / $srcH;
                    if ($this->isMatchTargetWH != 1) {
                        if ($this->targetW > $this->targetW2)
                            $this->targetW = $this->targetW2;
                        if ($this->targetH > $this->targetH2)
                            $this->targetH = $this->targetH2;
                    }
                    $dstX = ($this->targetW - $this->targetW2) / 2; //輸出圖形的坐標x,y
                    $dstY = 0;
                }

                $ni = imagecreatetruecolor($this->targetW, $this->targetH); //ImageCreate($this->targetW,$this->targetH);畫出空白花布的大小

                if ($this->isTransparent && $this->getImageTypeString($imageTypeData) == ImageZoomFileType::Png) {
                    imagesavealpha($ni, true);
                    $colorBody = imagecolorallocatealpha($ni, 0, 0, 0, 127); //去背功能
                } else {
                    $colorBody = imagecolorallocate($ni, $this->colorR, $this->colorG, $this->colorB); //定義背景顏色
                }

                imagefill($ni, 0, 0, $colorBody); //填充背景顏色
                $this->type_W = $this->targetW2;
                $this->type_H = $this->targetH2;
                $this->type_X = $dstX;
                $this->type_Y = $dstY;

                if ($this->addWaterMark) {
                    $this->addWaterImage($ni);
                }

                switch ($this->type) {
                    case ImageZoomMakeMode::Zoom :
                        imagecopyresampled($ni, $im, $dstX, $dstY, $srcX, $srcY, $this->targetW2, $this->targetH2, $srcW, $srcH);
                        break;
                    case ImageZoomMakeMode::Cut :
                        switch ($this->site) {
                            case ImageZoomSite::LeftTop :
                                $dstX = 0;
                                $dstY = 0;
                                break;
                            case ImageZoomSite::Center :
                                $dstX = ($this->targetW - $srcW) / 2;
                                $dstY = ($this->targetH - $srcH) / 2;
                                break;
                            default :
                                break;
                        }

                        if ($srcW > $this->targetW) {
                            $this->type_W = $this->targetW;
                            $this->type_X = 0;
                        } else {
                            $this->type_W = $srcW;
                            $this->type_X = $dstX;
                        }

                        if ($srcH > $this->targetH) {
                            $this->type_H = $this->targetH;
                            $this->type_Y = 0;
                        } else {
                            $this->type_H = $srcH;
                            $this->type_Y = $dstY;
                        }

                        ImageCopy($ni, $im, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH);

                        break;
                    default :
                        break;
                }

                $tmpfilename = $this->getNewImageNameFromImage($ni);
                $tmpfilepath = $this->getNewImagePathAndName($tmpfilename);

                switch ($this->makeOriginalType) {
                    case ImageZoomFileType::Original:
                        if (strpos($tmpfilepath, ".gif")) {
                            $this->makeGifImage($ni, $tmpfilepath);
                        } else if (strpos($tmpfilepath, ".jpg") || strpos($tmpfilepath, ".jpeg")) {
                            $this->makeJpegImage($ni, $tmpfilepath);
                        } else if (strpos($tmpfilepath, ".png")) {
                            $this->makePngImage($ni, $tmpfilepath);
                        } else {
                            echo '判斷圖片類型失敗';
                        }
                        break;
                    case ImageZoomFileType::Gif:
                        $this->makeGifImage($ni, $tmpfilepath);
                        break;
                    case ImageZoomFileType::Jpeg:
                        $this->makeJpegImage($ni, $tmpfilepath);
                        break;
                    case ImageZoomFileType::Png:
                        $this->makePngImage($ni, $tmpfilepath);
                        break;
                }

                if ($this->outputBase64) {
                    $imgBinary = fread(fopen($tmpfilepath, "r"), filesize($tmpfilepath)); //讀取暫存檔
                    $base64Image = base64_encode($imgBinary);
                    unlink($tmpfilepath); //刪除暫存檔

                    imageDestroy($ni);

                    return $base64Image;
                } else {
                    imageDestroy($ni);

                    return $tmpfilename;
                }

                break;
        }
    }

    private function makeGifImage($image, $targetFile = NULL) {
        imagegif($image, $targetFile); //將變更後的圖存到指定路徑
    }

    private function makePngImage($image, $targetFile = NULL) {
        imagepng($image, $targetFile); //0:不壓縮 預設:6
    }

    private function makeJpegImage($image, $targetFile = NULL) {
        imagejpeg($image, $targetFile, 100);
    }

    private function getImageTypeString($imageTypeData) {
        switch ($this->makeOriginalType) {
            case ImageZoomFileType::Original:
                switch ($imageTypeData[2]) {
                    case 1: //圖片類型，1是GIF圖
                        $this->type = ImageZoomMakeMode::None;
                        return ImageZoomFileType::Gif;
                    case 2: //圖片類型，2是JPG圖
                        return ImageZoomFileType::Jpeg;
                    case 3: //圖片類型，3是PNG圖
                        return ImageZoomFileType::Png;
                }
            case ImageZoomFileType::Gif:
                return ImageZoomFileType::Gif;
            case ImageZoomFileType::Jpeg:
                switch ($imageTypeData[2]) {
                    case 1: //圖片類型，1是GIF圖
                        $this->type = ImageZoomMakeMode::None;
                    default :
                        break;
                }
                return ImageZoomFileType::Jpeg;
            case ImageZoom::Png:
                switch ($imageTypeData[2]) {
                    case 1: //圖片類型，1是GIF圖
                        $this->type = ImageZoomMakeMode::None;
                    default :
                        break;
                }
                return ImageZoomFileType::Png;
        }
    }

    private function getNewImageName() {
        if ($this->inputBase64) {
            $imageTypeData = getimagesizefromstring(base64_decode($this->sourceBase64));
        } else {
            $imageTypeData = getimagesize($this->sourceFile);
        }

        $base64 = '';
        if ($this->inputBase64) {
            $base64 = $this->sourceBase64;
        } else {
            $file = fopen($this->sourceFile, "r");
            $srcFile = fread($file, filesize($this->sourceFile));
            $base64 = base64_encode($srcFile);
            fclose($file);
        }

        $sha = hash("sha256", $base64);
        $tmpfileextension = $this->getImageTypeString($imageTypeData); //檔案的副檔名(轉成小寫)

        $tmpfilename = $sha . '.' . $tmpfileextension;

        return $tmpfilename;
    }

    private function getNewImageNameFromImage($image) {
        if ($this->inputBase64) {
            $imageTypeData = getimagesizefromstring(base64_decode($this->sourceBase64));
        } else {
            $imageTypeData = getimagesize($this->sourceFile);
        }

        $tmpfileextension = $this->getImageTypeString($imageTypeData); //檔案的副檔名(轉成小寫)

        $filename = time();
        $path = $this->getNewImagePathAndName($filename);
        switch ($this->makeOriginalType) {
            case ImageZoomFileType::Original:
                if (strpos($tmpfileextension, "gif") !== false) {
                    $this->makeGifImage($image, $path);
                } else if (strpos($tmpfileextension, "jpg") !== false || strpos($tmpfileextension, "jpeg") !== false) {
                    $this->makeJpegImage($image, $path);
                } else if (strpos($tmpfileextension, "png") !== false) {
                    $this->makePngImage($image, $path);
                } else {
                    echo 1;
                }
                break;
            case ImageZoomFileType::Gif:
                $this->makeGifImage($image, $path);
                break;
            case ImageZoomFileType::Jpeg:
                $this->makeJpegImage($image, $path);
                break;
            case ImageZoomFileType::Png:
                $this->makePngImage($image, $path);
                break;
        }

        $file = fopen($path, "r");
        $imageContent = fread($file, filesize($path));
        $base64 = base64_encode($imageContent);
        fclose($file);
        unlink($path);

        $sha = hash("sha256", $base64);

        $tmpfilename = $sha . '.' . $tmpfileextension;

        return $tmpfilename;
    }

    private function getNewImagePathAndName($tmpfilename) {
        $tmpfilepath = get_full_upload_folder($tmpfilename) . $tmpfilename;

        return $tmpfilepath;
    }

    /**
     * 初始浮水印縮放倍率
     * @param string $imageFile <p>
     * 檔案路徑
     */
    private function initialMoom() {
        if ($this->moom == '') {
            $this->moom = 0.3;
        }
    }

    /**
     * 初始背景顏色
     * @param string $imageFile <p>
     * 檔案路徑
     */
    private function initialColor() {
        if (($this->colorR == '' or $this->colorR == null) and $this->colorR != '0') {
            $this->colorR = 255;
        }
        if (($this->colorG == '' or $this->colorB == null) and $this->colorG != '0') {
            $this->colorG = 255;
        }
        if (($this->colorB == '' or $this->colorB == null) and $this->colorB != '0') {
            $this->colorB = 255;
        }
    }

    /**
     * 加入浮水印
     * @param string $imageFile <p>
     * 檔案路徑
     */
    private function addWaterImage($ni) {
        $logo_data = GetImageSize($this->waterFile);
        switch ($logo_data[2]) {
            case 1: //圖片類型，1是GIF圖
                $logo = imagecreatefromgif($this->waterFile);
                break;
            case 2: //圖片類型，2是JPG圖
                $logo = imagecreatefromjpeg($this->waterFile);
                break;
            case 3: //圖片類型，3是PNG圖
                $logo = imagecreatefrompng($this->waterFile);
                break;
        }

        $logo_srcW = ImageSX($logo); //原始圖片的寬度,也可以使用$data[0]
        $logo_srcH = ImageSY($logo); //原始圖片的高度,也可以使用$data[1]
        $logo_srcX = 0; //來源圖的坐標x,y
        $logo_srcY = 0;

        switch ($this->type) {
            case ImageZoomMakeMode::Zoom :
                if (($logo_srcW / $this->targetW2) > ($logo_srcH / $this->targetH2)) {//得出要生成圖片的長寬
                    $logo_dstW2 = $this->targetW2; //輸出圖片的寬度、高度
                    $logo_dstH2 = $logo_srcH * $this->targetW2 / $logo_srcW;
                } else {
                    $logo_dstH2 = $this->targetH2; //輸出圖片的寬度、高度
                    $logo_dstW2 = $logo_srcW * $this->targetH2 / $logo_srcH;
                }
                break;
            case ImageZoomMakeMode::Cut:
                if (($logo_srcW / $this->type_W) > ($logo_srcH / $this->type_H)) {//得出要生成圖片的長寬
                    $logo_dstW2 = $this->type_W; //輸出圖片的寬度、高度
                    $logo_dstH2 = $logo_srcH * $this->type_W / $logo_srcW;
                } else {
                    $logo_dstH2 = $this->type_H; //輸出圖片的寬度、高度
                    $logo_dstW2 = $logo_srcW * $this->type_H / $logo_srcH;
                }
                break;
            default :
                break;
        }

        $logo_dstX = $this->type_W - $logo_dstW2 * $this->moom + $this->type_X;
        $logo_dstY = $this->type_H - $logo_dstH2 * $this->moom + $this->type_Y;
        imagecopyresampled($ni, $logo, $logo_dstX, $logo_dstY, $logo_srcX, $logo_srcY, $logo_dstW2 * $this->moom, $logo_dstH2 * $this->moom, $logo_srcW, $logo_srcH);
    }

    function outputOriginal() {
        $this->makeOriginalType = ImageZoomFileType::Original;
    }

    function outputGif() {
        $this->makeOriginalType = ImageZoomFileType::Gif;
    }

    function outputPng() {
        $this->makeOriginalType = ImageZoomFileType::Png;
    }

    function outputJpeg() {
        $this->makeOriginalType = ImageZoomFileType::Jpeg;
    }

}

abstract class ImageZoomSite {

    const LeftTop = 'lefttop';
    const Center = 'center';

}

abstract class ImageZoomFileType {

    const Original = 'original';
    const Gif = 'gif';
    const Jpeg = 'jpeg';
    const Png = 'png';

}

abstract class ImageZoomMakeMode {

    const Zoom = 'zoom';
    const Cut = 'cut';
    const None = 'none';

}

?>