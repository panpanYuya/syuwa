<?php

namespace Tests\Unit\Services;

use App\Repositories\CreateNewPostRepository;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;


    private $create_new_post_repository_mock;
    private $post_repository_mock;


    public function setUp(): void
    {
        parent::setUp();

        $this->create_new_post_repository_mock = Mockery::mock(CreateNewPostRepository::class);
        $this->post_repository_mock = Mockery::mock(PostRepository::class);
    }

    /**
     * base64型の文字列から画像をデコードし、新たなファイル名とデコードしたファイルを返す処理のテスト
     *
     * @return void
     */
    public function test_to_file()
    {
        //base64ファイルのサンプル
        $testFileData = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAMAAADDpiTIAAADAFBMVEUAAAAAAAAAf38AVVUAP38AM2YAVVUkSG0fP18cOFUZTGYXRVwVP2oTTmISSFsRRGYPP18PS2kORmMNQ10MP2YYSGEXRWgWQmMVP18UR2YTRGISQl4SSGQRRmARRGYQQWIPR18PRWQWQ2EVQWYVRmMURGAUQ2QTR2ITRmYSRGMSQmERR2QRRWIRRGAQQmMQRmEVRWQUQ2IUR2AURmQTRGITQ2USRmMSRWESRGQRQ2IRRmURRWMRRGEUR2QURWIURGETQ2MTRmITRWQTRGISQ2ESRmMSRWIRRGQRQ2MRRWEURGMURGIURmQTRWMTRGITQ2QTRmISRWESRGMSQ2ISRWQSRWMRRGERRmMURWIURGQTRGMTRmITRWMTRGISQ2ESRWMSRWISRGMSRmISRWERRGMURGIURmQTRWMTRGITRGMTRWITRWQSRGMSQ2ISRWMSRGISRGESRWMRRWITRGMTRGITRWITRWMTRGITRGMSRWMSRGISRGMSRWISRWMSRGMSRGITRWMTRWITRGITRGMTRWITRWMTRGISRWISRWMSRGISRGMSRWMSRWITRGMTRGITRWMTRWMTRGITRWMTRWISRGISRGMSRWISRWMSRGISRGISRWMTRWITRGMTRGMTRWITRGMTRGITRWMSRWMSRGISRGMSRWISRWISRGMTRGITRWMTRWMTRGITRWMTRWITRGMSRGMSRWISRWMSRGISRGISRWMSRWITRGMTRWITRWITRGMTRGITRWMSRWMSRGISRGMSRWISRWMSRGMSRGITRWMTRGITRGITRWMTRWITRGMTRGISRWISRWMSRGISRGMSRWMSRGISRGMTRWITRWMTRGMTRGITRWMTRWISRGISRGMSRWISRWMSRGISRWISRWMTRGITRGMTRWMTRWITRGMTRGITRWMSRWMSRGISRWMSRWISRGISRGMTRWITRW";

        $service = new PostService($this->create_new_post_repository_mock, $this->post_repository_mock);
        list($fileName) = $service->base64ToFile($testFileData);
        $fileformat = explode('.', $fileName);
        // 現在は画像を一枚しか登録できない仕様となっているので配列の1を指定する
        $this->assertEquals($fileformat[1], 'png');
    }

    /**
     * 画像をS3に登録する処理のテスト
     *
     * @return void
     */
    public function test_store_photo()
    {
        $testFileName = 'testFile.png';
        $testFileDate = UploadedFile::fake()->image('test.png', 500, 500);

        $service = new PostService($this->create_new_post_repository_mock, $this->post_repository_mock);
        $fileUrl = $service->storePhoto($testFileName, $testFileDate);
        $imageFile = str_replace(env('AWS_URL') . '/' . env('AWS_BUCKET') . '/', '', $fileUrl);
        Storage::disk('s3')->assertExists($imageFile);
    }

}


