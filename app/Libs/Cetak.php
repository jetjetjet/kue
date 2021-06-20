<?php
namespace App\Libs;

use Carbon\Carbon;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;

use App\Repositories\SettingRepository;

class Cetak
{
  private static function getSetting()
  {
    return Array(
      "footer" => SettingRepository::getAppSetting('FooterStruk'),
      "header" => SettingRepository::getAppSetting('HeaderStruk'),
      "AppName" => SettingRepository::getAppSetting('NamaApp'),
      "IpPrinter" => SettingRepository::getAppSetting('IpPrinter'),
      "Telp" => SettingRepository::getAppSetting('Telp'),
      "Alamat" => SettingRepository::getAppSetting('Alamat'),
      "footerkasir" => SettingRepository::getAppSetting('FooterStrukKasir'),
      "headerkasir" => SettingRepository::getAppSetting('HeaderStrukKasir'),
      "logoApp" => SettingRepository::getAppSetting('logoApp'),
    );
  }

  public static function print($data)
  {
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new RawbtPrintConnector();
      $printer = new Printer($connector, $profile);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->setEmphasis(true);
      $printer->selectPrintMode();
      $printer->text(self::getSetting()['AppName'] ."\n");
      if(self::getSetting()['header'])
        $printer->text(self::getSetting()['header']."\n");
      $printer->text(self::getSetting()['Alamat']."\n");
      $printer->text("================================\n");
      /* Title of receipt */
      $printer -> setTextSize(2, 1);
      $printer->text($data->invoice . "\n");
      $printer -> setTextSize(1, 1);
      $printer->text($data->orderType . "\n Meja ". $data->noTable . "\n");
      $printer->setEmphasis(false);
  
      $printer->text("Daftar Pesanan\n");
      $printer->text("--------------------------------\n");
      // Body
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      $printer->setEmphasis(true);
      // $printer->text(self::getAsString("", $data->price, "Rp "));
      $printer->setEmphasis(false);
      if($data->detail){
        foreach($data->detail as $item){
          $rPrice = $item->promo ? $item->priceraw : $item->price;
          $rPriceTotal = $item->promo  ? $item->totalPriceraw : $item->totalPrice;
          $printer->text($item->text . "\n");
          $printer->text(self::getAsString($item->qty . " x " . number_format($rPrice,0), number_format($rPriceTotal,0))); // for 58mm Font A
          
          if($item->promo){
            $printer->text(self::getAsString( "Promo @" . number_format($item->promodiscount,0), number_format(($item->qty * $item->promodiscount),0))); // for 58mm Font A
          }
        }
      }
  
      $printer->text("--------------------------------\n");
      /* Total */
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->text(self::getAsString("Total ", number_format($data->price,0), "Rp "));
      $printer->selectPrintMode();
  
      /* Footer */
      $printer->feed(1);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text(self::getSetting()['footer'] . "\n");
      $printer->feed();
      $printer->close();
      // $printer->text($date . "\n");
    }catch(\Exception $e){
      $printer = false;
    }
    
  }

  public static function printKasir($data, $inputs)
  {
    
    // dd($data);
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector(self::getSetting()['IpPrinter'], 9100, 2);

      // // virtualprinter
      // $connector = null;
      // $connector = new WindowsPrintConnector("test2");

      $printer = new Printer($connector, $profile);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      // $printer->text("Cafe&Resto\n");
      // $printer->text("Hayyyysss\n");
      // // // gambar
      $tux = EscposImage::load(public_path(self::getSetting()['logoApp']),true);     
      $printer -> graphics($tux);
      $printer -> feed();
      $printer->selectPrintMode();
      $printer->text(self::getSetting()['Alamat']."\n");
      $printer->text('Telp. '.self::getSetting()['Telp']."\n");
      if(self::getSetting()['headerkasir'] != null){
      $printer->text(self::getSetting()['headerkasir']."\n");
      }

      $printer->feed();
      /* Title of receipt */
      $printer->setEmphasis(true);
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      $printer->text("Nomor Pesanan : " . $data->invoice . "\n");
      $printer->text("Kasir         : ". $inputs['username'] . "\n");
      $printer->text("Tipe Pesanan  : " . $data->orderType . "\n");
      if($data->noTable != null){
        $printer->text("                Meja - " . $data->noTable . "\n");
      }
      $printer->text("Tanggal       : ". $data->date . "\n");
      $printer->setEmphasis(false);
  
      // Body
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      
      $printer->setEmphasis(true);
      // $printer->text(self::getAsString("", $data->price, "Rp "));
      $printer->setEmphasis(false);
      $subTotal = 0;
      $promo = 0;
      if($data->detail){
        $printer->text("================================================\n");
        foreach($data->detail as $item){
          $printer->setEmphasis(true);
          if($item->promo){
            $printer->text($item->text);
            $printer->selectPrintMode(Printer::MODE_UNDERLINE);
            $printer->text("(@".number_format($item->promodiscount).")\n");
            $printer->selectPrintMode(Printer::MODE_FONT_A);
          }else{
            $printer->text($item->text."\n");
          }
          $printer->setEmphasis(false);
          $printer->text(self::getAsStringkasirmenu("" , number_format($item->price), " x ".$item->qty,number_format($item->totalPrice))); // for 58mm Font A
        }
      }
      $printer->text("================================================\n");
      /* Total */
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->text(self::getAsStringkasirtotal("Sub Total ", number_format($data->price), "Rp "));
      $gTotal = $data->price;
      $kembalian = $data->paidprice;
      if($data->discountprice){
        $printer->text(self::getAsStringkasirtotal("Diskon ","-".number_format($data->discountprice), "Rp "));
        $gTotal -= $data->discountprice;
      }else{
        $printer->text(self::getAsStringkasirtotal("Diskon ","-", "Rp "));
      }
      $printer->selectPrintMode(Printer::MODE_FONT_A);
      $printer->text("------------------------------------------------\n");
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->text(self::getAsStringkasirtotal("Grand Total ",number_format($gTotal), "Rp "));
      $kembalian = $data->paidprice - $gTotal;
      $printer->text(self::getAsStringkasirtotal($data->payment , number_format($data->paidprice), "Rp "));
      $printer->selectPrintMode(Printer::MODE_FONT_A);
      $printer->text("------------------------------------------------\n");
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->text(self::getAsStringkasirtotal("Kembalian ", number_format($kembalian), "Rp "));
      

      $printer->selectPrintMode();
  
      /* Footer */
      $printer->feed(1);
      if(self::getSetting()['footerkasir'] != null){
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text(self::getSetting()['footerkasir']."\n");
      $printer->feed(1);
      }
      /* Lisence*/ 
      // $printer -> setFont(Printer::FONT_B);
      // $jam = now()->format('d/m/Y h:i:s');
      // $printer->text(self::getAsStringkasirfooter($jam, '@IkhwanKomputer'));
      // $printer -> pulse();

      $printer -> cut();
      $printer->close();

    }catch(\Exception $e){
      $printer = false;
    }
  }

  public static function bukaLaci($respon)
  {
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector(self::getSetting()['IpPrinter'], 9100, 2);
      // $connector = null;
      // $connector = new WindowsPrintConnector("test2");
      $printer = new Printer($connector, $profile);
      $printer -> pulse();
      $printer->close();
      $respon['status'] = 'success';
    }catch(\Exception $e){
      $printer = false;
      array_push($respon['messages'], 'Periksa Koneksi Printer anda');
      $respon['status'] = "error";
    }
    return $respon;
  }

  public static function ping($respon)
  {
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector(self::getSetting()['IpPrinter'], 9100, 2);
      // $connector = null;
      // $connector = new WindowsPrintConnector("test2");
      $printer = new Printer($connector, $profile);
      $printer->close();
      $respon['status'] = 'success';
    }catch(\Exception $e){
      $printer = false;
      $respon['status'] = "error";
    }
    return $respon;
  }

  public static function getAsString($name, $price, $currency = false)
  {
    $rightCols = 10;
    $width = 32;
    $leftCols = $width - $rightCols;
    if ($currency) {
        $leftCols = $leftCols / 2 - $rightCols / 2;
    }
    $left = str_pad($name, $leftCols);

    $sign = ($currency ? 'Rp ' : '');
    $right = str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$right\n";
  }

  public static function getAsStringkasirmenu($name, $price, $qty, $currency)
  {
    $rightCols = 9;
    $rupiah = 7;
    // $width = 80;
    $leftCols = 6;
    $middle = 13;
    $middle2 = 13;

    $left = str_pad($name, $leftCols);

    $mid2 = str_pad($price, $middle2,' ', STR_PAD_LEFT);

    $mid = str_pad($qty, $middle,' ', STR_PAD_RIGHT);

    $rp = str_pad("Rp. ", $rupiah,' ', STR_PAD_LEFT);

    $right = str_pad($currency, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$mid2$mid$rp$right\n";
  }

  public static function getAsStringkasirtotal($name, $price, $currency)
  {
    $rightCols = 9;
    $width = 80;
    $leftCols = 12;
    $md = 3;
    $left = str_pad($name, $leftCols);

    $sign = str_pad( 'Rp.', $md, ' ', STR_PAD_LEFT);
    $right = str_pad($price, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$sign$right\n";
  }

  public static function getAsStringkasirfooter($jam, $lisen)
  {
    $rightCols = 44;
    $width = 80;
    $leftCols = 20;
    $left = str_pad($jam, $leftCols);

    $right = str_pad($lisen, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$right\n";
  }
}