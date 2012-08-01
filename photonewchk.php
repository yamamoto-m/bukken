<?php
/****************************************/
/* 物件の新規登録確認ページ             */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  //前のページからデータを受け取り
  $uploadfile       = $_FILES[uploadfile][tmp_name];
  $uploadfile_name  = $_FILES[uploadfile][name];
  $uploadfile_size  = $_FILES[uploadfile][size];
  $uploadfile1      = $_FILES[uploadfile1][tmp_name];
  $uploadfile_name1 = $_FILES[uploadfile1][name];
  $uploadfile_size1 = $_FILES[uploadfile1][size];
  $uploadfile2      = $_FILES[uploadfile2][tmp_name];
  $uploadfile_name2 = $_FILES[uploadfile2][name];
  $uploadfile_size2 = $_FILES[uploadfile2][size];
  $uploadfile3      = $_FILES[uploadfile3][tmp_name];
  $uploadfile_name3 = $_FILES[uploadfile3][name];
  $uploadfile_size3 = $_FILES[uploadfile3][size];
  $categoryid      = $_POST[categoryid];
  $comment         = $_POST[comment];
  $jyusyo          = $_POST[jyusyo];
  $moyorieki       = $_POST[moyorieki];
  $zip             = $_POST[zip];
  $sikiti          = $_POST[sikiti];
  $tatetubo        = $_POST[tatetubo];
  $madori          = $_POST[madori];
  $sale            = $_POST[sale];
  $kouzou          = $_POST[kouzou];
  $sonota          = $_POST[sonota];
  $torihiki        = $_POST[torihiki];
  $youto           = $_POST[youto];
  $genjyou         = $_POST[genjyou];
  $kuruma          = $_POST[kuruma];
  $kansei          = $_POST[kansei];
  $hikiwatasi      = $_POST[hikiwatasi];
  $kakunin         = $_POST[kakunin];
  $address         = $_POST[address];
  $kousai          = $_POST[kousai];
  $kenri           = $_POST[kenri];
  $yousekiritu     = $_POST[yousekiritu];
  $kenpeiritu      = $_POST[kenpeiritu];
  $miti            = $_POST[miti];

  //エスケープ文字の除去とタグの無効化を行ないます
  $comment = htmlspecialchars(stripcslashes($comment));
  $sonota  = htmlspecialchars(stripcslashes($sonota));
  $moyorieki =htmlspecialchars(stripcslashes($moyorieki));
  $madori  = htmlspecialchars(stripcslashes($madori));
  $jyusyo  = htmlspecialchars(stripcslashes($jyusyo));
  $sikiti = htmlspecialchars(stripcslashes($sikiti));
  $tatemono = htmlspecialchars(stripcslashes($tatemono));
  $sale = htmlspecialchars(stripcslashes($sale));
  $kouzou = htmlspecialchars(stripcslashes($kouzou));
  $miti   = htmlspecialchars(stripcslashes($miti));
  $youto  = htmlspecialchars(stripcslashes($youto));
  $kousai  = htmlspecialchars(stripcslashes($kousai));
  $kakunin = htmlspecialchars(stripcslashes($kakunin));
  $kenpeiritu  = htmlspecialchars(stripcslashes($kenpeiritu));
  $yousekiritu = htmlspecialchars(stripcslashes($yousekiritu));



  //半角カタカナを全角カタカナに変換します
  $comment = mb_convert_kana($comment, "KV", "utf-8");
  $jyusyo = mb_convert_kana($jyusyo, "KV", "utf-8");
  $moyorieki = mb_convert_kana($moyorieki, "KV", "utf-8");
  $sikiti   = mb_convert_kana($sikiti, "KV", "utf-8");
  $tatemono = mb_convert_kana($tatemono, "KV", "utf-8");
  $madori   = mb_convert_kana($madori, "KV", "utf-8");
  $sale = mb_convert_kana($sale, "KV", "utf-8");
  $kouzou = mb_convert_kana($kouzou, "KV", "utf-8");
  $sonota = mb_convert_kana($sonota, "KV", "utf-8");
  $miti   = mb_convert_kana($miti, "KV", "utf-8");
  $youto  = mb_convert_kana($youto, "KV", "utf-8");
  $kousai = mb_convert_kana($kousai, "KV", "utf-8");
  $kakunin = mb_convert_kana($kakunin, "KV", "utf-8");
  $kenpeiritu = mb_convert_kana($kenpeiritu, "KV", "utf-8");
  $yousekiritu = mb_convert_kana($yousekiritu, "KV", "utf-8");


  //各入力データのチェックを行ないます
  $errmsg = "";
  if (strlen($uploadfile) == 0) {
    $errmsg .= "写真のファイルが指定されていません。<BR>";
  }
  if ($categoryid == 0) {
    $errmsg .= "カテゴリが選択されていません。<BR>";
  }
  if (mb_strlen($sonota,"EUC-JP") >50 ) {
    $errmsg .= "コメントの文字数が多すぎます。<BR>";
  }
  if (mb_strlen($comment, "EUC-JP") >250) {
    $errmsg .= "コメントの文字数が多すぎます。<BR>";
  }
  //改行コードをBRタグに置換します
  $comment = nl2br($comment);
  $sonota = nl2br($sonota);

  //写真ファイルのアップロード処理
  if(strlen($uploadfile) > 0 ) {
    //アップロードされたテンポラリファイルの情報を取得します
    $fileinfo = pathinfo($uploadfile_name);
    $fileext  = strtoupper($fileinfo[extension]);
   }
 
    if ($uploadfile_size > 250000) {
      //アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.25MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size == 0) {
      //アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext != "JPG") {
      //アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    }
     //テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile, "$PHOTOTMP$imagefile"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
      }
    
  //写真ファイルのアップロード処理
   if(strlen($uploadfile1) > 0 ) {
     //アップロードされたテンポラリファイルの情報を取得します
      $fileinfo1 = pathinfo($uploadfile_name1);
      $fileext1  = strtoupper($fileinfo1[extension]);
  }

     if ($uploadfile_size1 > 250000) {
      //1アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.25MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size1 == 0) {
      //1アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext1 != "JPG") {
      //1アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //1アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile1 = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    }

    //1テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile1, "$PHOTOTMP$imagefile1"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
    }

    if(strlen($uploadfile2) > 0 ) {
    //アップロードされたテンポラリファイルの情報を取得します
    $fileinfo2 = pathinfo($uploadfile_name2);
    $fileext2  = strtoupper($fileinfo2[extension]);
    }

      if ($uploadfile_size2 > 250000) {
      //2アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.25MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size2 == 0) {
      //2アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext2 != "JPG") {
      //2アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //2アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile2 = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    }

     //2テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile2, "$PHOTOTMP$imagefile2"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
      }
     
    if(strlen($uploadfile3) > 0 ) {
    //アップロードされたテンポラリファイルの情報を取得します
    $fileinfo3 = pathinfo($uploadfile_name3);
    $fileext3  = strtoupper($fileinfo3[extension]);
    }

      if ($uploadfile_size3 > 1000000) {
      //3アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.1MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size3 == 0) {
      //3アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext3 != "JPG") {
      //3アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //3アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile3 = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    }

     //3テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile3, "$PHOTOTMP$imagefile3"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
    }


  if ($errmsg != "") {
    //いずれかの入力エラーがあったとき
    $body = $errmsg . "<BR><INPUT type='button' value='  戻る ' onClick='history.back()'>";
    //アップロードしたファイルの実体を削除します
    if(strlen($uploadfile) > 0 ) {
      @unlink($uploadfile);
      @unlink("$PHOTOTMP$imagefile");
     }
    if(strlen($uploadfile1) >0) {
      @unlink($uploadfile1);
      @unlink("$PHOTOTMP$imagefile1");
     }
    if(strlen($uploadfile2) >0) {
      @unlink($uploadfile2);
      @unlink("$PHOTOTMP$imagefile2");
    }
    if(strlen($uploadfile3) >0) {
      @unlink($uploadfile3);
      @unlink("$PHOTOTMP$imagefile3");
  }
 }
  else {
    //エラーがなければ確認用画面を組み立てます
    $body = "登録内容を確認してください。よければ[OK]ボタンをクリックしてください。
            <BR><BR>
            <FORM action='photonewexec.php' method='POST'>
            <TABLE class='formtable'>
              <TR>
                <TH>物件のファイル</TH>
                <TD>
                  <IMG src='$PHOTOTMP$imagefile' width='240' height='180'>
                  <IMG src='$PHOTOTMP$imagefile1' width='240' height='180'>
                  <IMG src='$PHOTOTMP$imagefile2' width='240' height='180'>
                  <IMG src='$PHOTOTMP$imagefile3' width='240' height='180'>
                 </TD>
              </TR>
              <TR>
                <TH>カテゴリー</TH>
                <TD>";
           //カテゴリテーブルからカテゴリ名を検索します
              $body .= dfirst("categoryname", "tblcategory", "categoryid=$categoryid", True);
              $body .= "<BR><BR>
              </TD>
              </TR>
              <TR>
                <TH>住所</TH>
                <TD width='400'>$jyusyo<BR>
                </TD>
              </TR>
              <TR>
                 <TH>最寄り駅</TH>
                 <TD width='400'>$moyorieki<BR>
                 </TD></TABLE>
              </TR>

       <TABLE class='formtable'>
              <TR>
               <TH>敷地</TH>
                <TD width='50'>$sikiti 坪<BR>
                </TD>
               <TH>建蔽率</TH>
                <TD width='60'>$kenpeiritu %<BR>
                </TD>
               <TH>間取り</TH>
                <TD width='70'>$madori<BR>
                </TD>
               <TH>完成年</TH>
                <TD width='90'>H. $kansei<BR>
                </TD>
               <TH>駐車場</TH>
                <TD width='70'>$kuruma<BR>
              </TR>

              <TR>
                 <TH>建物</TH>
                 <TD width='40'>$tatetubo 坪<BR>
                 </TD>
               <TH>容積率</TH>
                <TD width='60'>$yousekiritu %<BR>
                </TD>
               <TH>価格</TH>
                <TD width='75'>$sale 万円<BR>
                </TD>
               <TH>引渡し</TH>
                <TD width='90'>$hikiwatasi<BR>
                </TD>
                <TH>現状</TH>
                <TD width='70'>$genjyou<BR>
                </TD></TABLE>
              </TR>
              <TABLE class='formtable'>
              <TR>
                <TH>構造</TH>
                <TD width='245'>$kouzou<BR>
                </TD></TR>
              <TR>
                <TH>用途地域</TH>
                <TD width='245'>$youto<BR>
                </TD>
                <TH>接道状況</TH>
                <TD width='260'>$miti<BR>
                </TD>
              </TR>  
              </TABLE>

              <TABLE class='formtable'>
               <TR>
               <TH>取引態様</TH>
               <TD width='80'>$torihiki<BR>
               </TD>
               <TH>連絡先</TH>
               <TD width='296'>$address<BR>
               </TD>
               <TH>光彩</TH>
               <TD width='100'>$kousai<BR>
               </TD>
               </TABLE>

              <TABLE class='formtable'>
              <TR>
                <TH>建築確認</TH>
                <TD width='150'>$kakunin<BR>
                <TH>その他</TH>
                <TD width='372'>$sonota<BR>
                <BR>
                </TD>
              </TR></TABLE>
              <TABLE class='formtable'>
               <TR>
                 <TH>物件コメント</TH>
                 <TD width='565'>$comment<BR>
                 <BR>
                 </TD>
              </TR>
              <TR>
                <TD colspan='2' align='center'>
                  <INPUT type='submit' name='regok' value='  ＯＫ  '>
                  <INPUT type='button' value='  戻る  ' onclick='history.back()'>
                  <INPUT type='submit' name='cancel' value='キャンセル'>
                  <INPUT type='hidden' name='imagefile' value='$imagefile'>
                  <INPUT type='hidden' name='imagefile1' value='$imagefile1'>
                  <INPUT type='hidden' name='imagefile2' value='$imagefile2'>
                  <INPUT type='hidden' name='imagefile3' value='$imagefile3'>
                  <INPUT type='hidden' name='jyusyo' value=\"$jyusyo\">
                  <INPUT type='hidden' name='moyorieki' value=\"$moyorieki\">
                  <INPUT type='hidden' name='sikiti' value=\"$sikiti\">
                  <INPUT Type='hidden' name='tatemono' value=\"$tatemono\">
                  <INPUT type='hidden' name='madori' value=\"$madori\">
                  <INPUT Type='hidden' name='kouzou' value=\"$kouzou\">
                  <INPUT Type='hidden' name='sale' value=\"$sale\">
                  <INPUT type='hidden' name='sonota' value=\"$sonota\">
                  <INPUT Type='hidden' name='torihiki' value=\"$torihiki\">
                  <INPUT Type='hidden' name='hikiwatasi' value=\"$hikiwatasi\">
                  <INPUT type='hidden' name='youto' value=\"$youto\">
                  <INPUT type='hidden' name='genjyou' value=\"$genjyou\">
                  <INPUT Type='hidden' name='kuruma' value=\"$kuruma\">
                  <INPUT Type='hidden' name='kansei' value=\"$kansei\">
                  <INPUT type='hidden' name='kakunin' value=\"$kakunin\">
                  <INPUT type='hidden' name='kenri' value=\"$kenri\">
                  <INPUT type='hidden' name='miti' value=\"$miti\">
                  <INPUT Type='hidden' name='yousekiritu' value=\"$yousekiritu\">
                  <INPUT Type='hidden' name='kenpeiritu' value=\"$kenpeiritu\">
                  <INPUT type='hidden' name='address' value=\"$address\">
                  <INPUT type='hidden' name='kousai' value=\"$kousai\">
                  <INPUT type='hidden' name='categoryid' value='$categoryid'>
                  <INPUT type='hidden' name='comment' value=\"$comment\">


               </TD>
              </TR>
            </TABLE>
            </FORM>";
  }

  //ページヘッダを出力します
  print htmlheader("物件登録の確認");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
