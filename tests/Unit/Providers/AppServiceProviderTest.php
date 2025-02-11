<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    /**
     * @group unit
     * @group manager
     * ひらがなチェック
     * @return void
     */
    public function test01()
    {
        $target = ['value' => 'あいうえお'];
        $rule = ['value' => 'hiragana'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * ひらがなチェック
     * @return void
     */
    public function test02()
    {
        $target = ['value' => 'ー 　！＠［｀｛～'];
        $rule = ['value' => 'hiragana'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * ひらがなチェック
     * @return void
     */
    public function test03()
    {
        $target = ['value' => 'アイウエオ'];
        $rule = ['value' => 'hiragana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * ひらがなチェック
     * @return void
     */
    public function test04()
    {
        $target = ['value' => 'ＡＢＣＤＥ'];
        $rule = ['value' => 'hiragana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * ひらがなチェック
     * @return void
     */
    public function test05()
    {
        $target = ['value' => 'abcde'];
        $rule = ['value' => 'hiragana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * ひらがなチェック
     * @return void
     */
    public function test06()
    {
        $target = ['value' => '安以宇衣於'];
        $rule = ['value' => 'hiragana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 平仮名片仮名チェック
     * @return void
     */
    public function test07()
    {
        $target = ['value' => ' 　'];
        $rule = ['value' => 'hiraganakatakana'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 平仮名片仮名チェック
     * @return void
     */
    public function test08()
    {
        $target = ['value' => 'ー！-＠［-｀｛-～]'];
        $rule = ['value' => 'hiraganakatakana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 平仮名片仮名チェック
     * @return void
     */
    public function test09()
    {
        $target = ['value' => 'アイウエオ'];
        $rule = ['value' => 'hiraganakatakana'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 平仮名片仮名チェック
     * @return void
     */
    public function test10()
    {
        $target = ['value' => 'ＡＢＣＤＥ'];
        $rule = ['value' => 'hiraganakatakana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 平仮名片仮名チェック
     * @return void
     */
    public function test11()
    {
        $target = ['value' => 'abcde'];
        $rule = ['value' => 'hiraganakatakana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 平仮名片仮名チェック
     * @return void
     */
    public function test12()
    {
        $target = ['value' => '安以宇衣於'];
        $rule = ['value' => 'hiraganakatakana'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * @return void
     */
    public function test13()
    {
        $target = ['value' => 'abc@bm-sms.co.jp'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * @return void
     */
    public function test14()
    {
        $target = ['value' => 'ABC@BM-SMS.CO.JP'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * @return void
     */
    public function test15()
    {
        $target = ['value' => 'abc._-def@bm-sms.co.jp'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * @return void
     */
    public function test16()
    {
        $target = ['value' => '___abc@bm-sms.co.jp'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * KJAと違い、先頭"-"を許可する
     * @return void
     */
    public function test17()
    {
        $target = ['value' => '---abc@bm-sms.co.jp'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * KJAと違い、アカウント部での"?"の使用を許可する
     * @return void
     */
    public function test18()
    {
        $target = ['value' => 'abc?def@bm-sms.co.jp'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * @return void
     */
    public function test19()
    {
        $target = ['value' => 'abcdef'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * Eメールアドレスチェック
     * @return void
     */
    public function test20()
    {
        $target = ['value' => 'abc@bm-sms'];
        $rule = ['value' => 'custom_email'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    // /**
    //  * @group unit
    //  * @group manager
    //  * 電話番号チェック
    //  * カスタムValidation内では空チェックを行っているが、
    //  * そもそも対象文字列が空である場合はチェックすら行っていない
    //  * @return void
    //  */
    // public function test21()
    // {
    //     $target = ['value' => ''];
    //     $rule = ['value' => 'custom_tel'];

    //     $validator = \Validator::make($target, $rule);
    //     $this->assertFalse($validator->passes());
    // }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test22()
    {
        $target = ['value' => '0000000000'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test23()
    {
        $target = ['value' => '１１１１１１１１１１'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test24()
    {
        $target = ['value' => '123 456　789'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test25()
    {
        $target = ['value' => '222ー222ｰ2―2'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test26()
    {
        $target = ['value' => '333(333)3333'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test27()
    {
        $target = ['value' => '４４（４４４４））４４４４'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test28()
    {
        $target = ['value' => 'abc-123-def'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号チェック
     * @return void
     */
    public function test29()
    {
        $target = ['value' => 'ＡＢＣ（ＤＥＦ）ＧＨＩＪ'];
        $rule = ['value' => 'custom_tel'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    // /**
    //  * @group unit
    //  * @group manager
    //  * 電話番号チェック
    //  * スペースのみであると、最終的に空文字となり、チェックに引っかからなくなる
    //  * そのため、このValidationルール単体での使用は非推奨
    //  * @return void
    //  */
    // public function test30()
    // {
    //     $target = ['value' => '　　　　　　　　　　'];
    //     $rule = ['value' => 'custom_tel'];

    //     $validator = \Validator::make($target, $rule);
    //     $this->assertFalse($validator->passes());
    // }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test31()
    {
        $target = ['value' => '333(333)3333'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test32()
    {
        $target = ['value' => '(444)4444444'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test33()
    {
        $target = ['value' => '55-555-55555'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test34()
    {
        $target = ['value' => '66666-66666'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test35()
    {
        $target = ['value' => '000(000)(000)00'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test36()
    {
        $target = ['value' => '11(1(111)1)11'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test37()
    {
        $target = ['value' => '2222222(222)'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test38()
    {
        $target = ['value' => '3333333333'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test39()
    {
        $target = ['value' => '777-77-77-777'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test40()
    {
        $target = ['value' => '-88888-88888'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号形式チェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test41()
    {
        $target = ['value' => '99999-99999-'];
        $rule = ['value' => 'custom_tel_format'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test42()
    {
        $target = ['value' => '1234567890'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test43()
    {
        $target = ['value' => '123(456)7890'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test44()
    {
        $target = ['value' => '123-456-7890'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test45()
    {
        $target = ['value' => '05011111111'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test46()
    {
        $target = ['value' => '07022222222'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test47()
    {
        $target = ['value' => '08033333333'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test48()
    {
        $target = ['value' => '09044444444'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertTrue($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test49()
    {
        $target = ['value' => '123456789'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test50()
    {
        $target = ['value' => '12345678901'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test51()
    {
        $target = ['value' => '0905555555'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }

    /**
     * @group unit
     * @group manager
     * 電話番号長さチェック
     * 値の変換については、"custom_tel"でチェック済み
     * @return void
     */
    public function test52()
    {
        $target = ['value' => '080666666666'];
        $rule = ['value' => 'custom_tel_length'];

        $validator = \Validator::make($target, $rule);
        $this->assertFalse($validator->passes());
    }
}
